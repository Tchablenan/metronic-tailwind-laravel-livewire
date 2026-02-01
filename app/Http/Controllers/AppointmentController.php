<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use App\Models\ServiceRequest;
use App\Enums\AppointmentStatus;
use App\Enums\AppointmentType;
use App\Services\AppointmentFilterService;
use App\Services\PatientMatcherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AppointmentController extends Controller
{
    use AuthorizesRequests;

    private AppointmentFilterService $filterService;
    private PatientMatcherService $patientMatcher;

    public function __construct(
        AppointmentFilterService $filterService,
        PatientMatcherService $patientMatcher
    ) {
        $this->filterService = $filterService;
        $this->patientMatcher = $patientMatcher;
    }

    /**
     * Get view prefix based on user role
     */
    private function getViewPrefix(): string
    {
        $role = Auth::user()->role;

        return match ($role) {
            'doctor' => 'demo1.doctor.appointments',
            'secretary' => 'demo1.secretary.appointments',
            'nurse' => 'demo1.nurse.appointments',
            'patient' => 'demo1.patient.appointments',
            default => 'demo1.doctor.appointments',
        };
    }

    /**
     * List appointments with filters
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Appointment::class);

        $query = Appointment::with(['patient', 'doctor', 'nurse']);

        // Apply filters
        $query = $this->filterService->applyFilters($query, $request);
        $query = $this->filterService->applyRoleBasedFilters($query, Auth::user());
        $query = $this->filterService->applySorting($query);

        $appointments = $query->paginate(5)->withQueryString();

        $statuses = AppointmentStatus::options();
        $types = AppointmentType::options();
        $doctors = User::where('role', 'doctor')
            ->where('is_chief', false)
            ->get();
        $patients = User::where('role', 'patient')->get();

        $viewPrefix = $this->getViewPrefix();

        return view("{$viewPrefix}.index", compact(
            'appointments',
            'statuses',
            'types',
            'doctors',
            'patients'
        ));
    }
    /**
     * Show creation form
     */
    public function create(Request $request)
    {
        $this->authorize('create', Appointment::class);

        $serviceRequest = null;
        $patient = null;
        $patientWarning = null;

        // Load from ServiceRequest if provided
        if ($request->has('service_request_id')) {
            $serviceRequest = ServiceRequest::findOrFail($request->service_request_id);

            // Verify doctor has access
            if (Auth::user()->role === 'doctor') {
                if ($serviceRequest->payment_status !== 'paid' || !$serviceRequest->sent_to_doctor) {
                    abort(403, 'Vous n\'avez pas accès à cette demande.');
                }
            }

            // Use PatientMatcherService to find or warn about patient
            $matchResult = $this->patientMatcher->matchOrCreatePatient($serviceRequest);
            $patient = $matchResult['patient'];
            $patientWarning = $matchResult['warning'];
        }

        $patients = User::where('role', 'patient')
            ->where('is_active', true)
            ->orderBy('first_name')
            ->get();

        $doctors = User::where('role', 'doctor')
            ->where('is_active', true)
            ->where('is_chief', false)
            ->orderBy('first_name')
            ->get();

        $nurses = User::where('role', 'nurse')
            ->where('is_active', true)
            ->orderBy('first_name')
            ->get();

        $types = AppointmentType::options();
        $locations = [
            'cabinet' => 'Cabinet médical',
            'domicile' => 'Domicile du patient',
            'hopital' => 'Hôpital',
            'urgence' => 'Urgence',
        ];

        $viewPrefix = $this->getViewPrefix();

        return view("{$viewPrefix}.create", compact(
            'patients',
            'doctors',
            'nurses',
            'types',
            'locations',
            'serviceRequest',
            'patient',
            'patientWarning'
        ));
    }
    /**
     * Store new appointment
     */
    public function store(Request $request)
    {
        $this->authorize('create', Appointment::class);

        $validated = $request->validate([
            'patient_id' => 'nullable|exists:users,id',
            'service_request_id' => 'nullable|exists:service_requests,id',
            'doctor_id' => 'nullable|exists:users,id',
            'nurse_id' => 'nullable|exists:users,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:15|max:240',
            'type' => 'required|in:consultation,followup,exam,emergency,vaccination,home_visit,telehealth',
            'reason' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:2000',
            'patient_notes' => 'nullable|string|max:1000',
            'location' => 'required|in:cabinet,domicile,hopital,urgence',
            'price' => 'nullable|numeric|min:0',
            'is_emergency' => 'boolean',
        ]);

        $serviceRequest = null;
        $accountCreated = false;

        // Handle ServiceRequest conversion
        if ($request->filled('service_request_id')) {
            $serviceRequest = ServiceRequest::findOrFail($request->service_request_id);

            if (Auth::user()->role === 'doctor') {
                if ($serviceRequest->payment_status !== 'paid' || !$serviceRequest->sent_to_doctor) {
                    abort(403, 'Vous ne pouvez pas convertir cette demande.');
                }
            }

            // If no patient selected, try to find or create one
            if (!$request->filled('patient_id')) {
                $matchResult = $this->patientMatcher->matchOrCreatePatient($serviceRequest);
                $patient = $matchResult['patient'];

                if (!$patient) {
                    // Create new patient
                    $patient = $this->patientMatcher->createPatientFromServiceRequest($serviceRequest);
                    $accountCreated = true;
                }

                $validated['patient_id'] = $patient->id;
            }
        }

        // Ensure patient_id is set
        if (!isset($validated['patient_id']) || !$validated['patient_id']) {
            return back()->withErrors([
                'patient_id' => 'Vous devez sélectionner un patient ou fournir une demande de service valide.'
            ])->withInput();
        }

        // Convert time and duration
        $validated['appointment_time'] = $validated['appointment_time'] . ':00';
        $validated['duration'] = (int)$validated['duration'];

        // Set doctor if chief user
        if (!$request->filled('doctor_id') && Auth::user()->isChief()) {
            $validated['doctor_id'] = Auth::id();
        }

        // Check for scheduling conflicts
        if ($request->filled('doctor_id')) {
            $this->checkSchedulingConflict(
                $validated['doctor_id'],
                $validated['appointment_date'],
                $validated['appointment_time'],
                $validated['duration']
            );
        }

        DB::beginTransaction();
        try {
            $appointment = Appointment::create($validated);

            // Update ServiceRequest if applicable
            if ($serviceRequest) {
                $serviceRequest->update([
                    'status' => 'converted',
                    'patient_id' => $appointment->patient_id,
                    'appointment_id' => $appointment->id,
                    'handled_by' => Auth::id(),
                    'handled_at' => now(),
                ]);

                // Send appropriate email
                $this->sendAppointmentEmail($appointment, $accountCreated);
            }

            DB::commit();

            return redirect()->route('appointments.show', $appointment)
                ->with('success', $this->getSuccessMessage($serviceRequest, $accountCreated));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur création rendez-vous: ' . $e->getMessage());

            return back()->withErrors(['error' => 'Erreur lors de la création du rendez-vous: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show appointment details
     */
    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);

        $appointment->load(['patient', 'doctor', 'nurse']);
        $viewPrefix = $this->getViewPrefix();

        return view("{$viewPrefix}.show", compact('appointment'));
    }

    /**
     * Show edit form
     */
    public function edit(Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        if (!$appointment->canBeModified()) {
            return redirect()->route('appointments.show', $appointment)
                ->with('error', 'Ce rendez-vous ne peut plus être modifié.');
        }

        $patients = User::where('role', 'patient')
            ->orderBy('first_name')
            ->get();

        $doctors = User::where('role', 'doctor')
            ->where('is_active', true)
            ->where('is_chief', false)
            ->orderBy('first_name')
            ->get();

        $nurses = User::where('role', 'nurse')
            ->orderBy('first_name')
            ->get();

        $types = AppointmentType::options();
        $locations = [
            'cabinet' => 'Cabinet',
            'domicile' => 'Domicile',
            'urgence' => 'Urgence',
            'hopital' => 'Hôpital',
        ];

        $viewPrefix = $this->getViewPrefix();

        return view("{$viewPrefix}.edit", compact(
            'appointment',
            'patients',
            'doctors',
            'nurses',
            'types',
            'locations'
        ));
    }

    /**
     * Update appointment
     */
    public function update(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        if (!$appointment->canBeModified()) {
            return redirect()->route('appointments.show', $appointment)
                ->with('error', 'Ce rendez-vous ne peut plus être modifié.');
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'doctor_id' => 'nullable|exists:users,id',
            'nurse_id' => 'nullable|exists:users,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:15|max:240',
            'type' => 'required|in:consultation,followup,exam,emergency,vaccination,home_visit,telehealth',
            'status' => 'nullable|in:scheduled,confirmed,in_progress,completed,cancelled,no_show',
            'reason' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:2000',
            'patient_notes' => 'nullable|string|max:1000',
            'cancellation_reason' => 'nullable|string|max:500',
            'location' => 'required|in:cabinet,domicile,urgence,hopital',
            'price' => 'nullable|numeric|min:0',
            'is_emergency' => 'boolean',
        ]);

        $validated['appointment_time'] = $validated['appointment_time'] . ':00';
        $validated['duration'] = (int)$validated['duration'];

        // Check conflicts if doctor changed
        if ($request->filled('doctor_id') && $request->doctor_id !== $appointment->doctor_id) {
            $this->checkSchedulingConflict(
                $validated['doctor_id'],
                $validated['appointment_date'],
                $validated['appointment_time'],
                $validated['duration'],
                $appointment->id
            );
        }

        DB::beginTransaction();
        try {
            $appointment->update($validated);
            DB::commit();

            return redirect()->route('appointments.show', $appointment)
                ->with('success', 'Rendez-vous mis à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erreur: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Delete appointment
     */
    public function destroy(Appointment $appointment)
    {
        $this->authorize('delete', $appointment);

        if (!$appointment->canBeCancelled()) {
            return back()->with('error', 'Ce rendez-vous ne peut pas être supprimé.');
        }

        DB::beginTransaction();
        try {
            $appointment->delete();
            DB::commit();

            return redirect()->route('appointments.index')
                ->with('success', 'Rendez-vous supprimé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la suppression.');
        }
    }

    /**
     * Confirm appointment (AJAX)
     */
    public function confirm(Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        if ($appointment->confirm()) {
            return response()->json([
                'success' => true,
                'message' => 'Rendez-vous confirmé avec succès.',
                'status' => $appointment->status,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Impossible de confirmer ce rendez-vous.',
        ], 400);
    }

    /**
     * Start appointment (AJAX)
     */
    public function start(Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        if ($appointment->start()) {
            return response()->json([
                'success' => true,
                'message' => 'Rendez-vous démarré.',
                'status' => $appointment->status,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Impossible de démarrer ce rendez-vous.',
        ], 400);
    }

    /**
     * Complete appointment (AJAX)
     */
    public function complete(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        $validated = $request->validate([
            'notes' => 'nullable|string|max:2000',
        ]);

        if ($appointment->complete($validated['notes'] ?? null)) {
            return response()->json([
                'success' => true,
                'message' => 'Rendez-vous terminé avec succès.',
                'status' => $appointment->status,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Impossible de terminer ce rendez-vous.',
        ], 400);
    }

    /**
     * Cancel appointment (AJAX)
     */
    public function cancel(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        $validated = $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);

        $cancelledBy = Auth::user()->role;

        if ($appointment->cancel($validated['cancellation_reason'], $cancelledBy)) {
            return response()->json([
                'success' => true,
                'message' => 'Rendez-vous annulé.',
                'status' => $appointment->status,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Impossible d\'annuler ce rendez-vous.',
        ], 400);
    }

    /**
     * Check availability (AJAX)
     */
    public function checkAvailability(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:15',
            'appointment_id' => 'nullable|exists:appointments,id',
        ]);

        $startTime = Carbon::parse($validated['appointment_time']);
        $endTime = $startTime->copy()->addMinutes($validated['duration']);

        $hasConflict = Appointment::where('doctor_id', $validated['doctor_id'])
            ->where('appointment_date', $validated['appointment_date'])
            ->whereIn('status', ['scheduled', 'confirmed', 'in_progress'])
            ->when($request->filled('appointment_id'), function ($q) use ($request) {
                $q->where('id', '!=', $request->appointment_id);
            })
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('appointment_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime) {
                        $q->where('appointment_time', '<=', $startTime)
                            ->whereRaw('DATE_ADD(appointment_time, INTERVAL duration MINUTE) > ?', [$startTime]);
                    });
            })
            ->exists();

        return response()->json([
            'available' => !$hasConflict,
            'message' => $hasConflict
                ? 'Ce créneau n\'est pas disponible.'
                : 'Créneau disponible.',
        ]);
    }

    // ========== PRIVATE HELPERS ==========

    /**
     * Check for scheduling conflicts
     */
    private function checkSchedulingConflict(
        $doctorId,
        $appointmentDate,
        $appointmentTime,
        $duration,
        $excludeAppointmentId = null
    ) {
        $endTime = Carbon::parse($appointmentTime)->addMinutes($duration)->format('H:i:s');

        $query = Appointment::where('doctor_id', $doctorId)
            ->where('appointment_date', $appointmentDate)
            ->whereIn('status', ['scheduled', 'confirmed', 'in_progress'])
            ->where(function ($query) use ($appointmentTime, $endTime) {
                $query->where('appointment_time', '<', $endTime)
                    ->whereRaw('ADDTIME(appointment_time, SEC_TO_TIME(duration * 60)) > ?', [$appointmentTime]);
            });

        if ($excludeAppointmentId) {
            $query->where('id', '!=', $excludeAppointmentId);
        }

        if ($query->exists()) {
            throw new \Exception('Ce créneau horaire n\'est pas disponible pour ce médecin.');
        }
    }

    /**
     * Send appointment confirmation email
     */
    private function sendAppointmentEmail(Appointment $appointment, bool $newAccount): void
    {
        try {
            if ($newAccount) {
                Mail::send('emails.activate-account', [
                    'patient' => $appointment->patient,
                    'appointment' => $appointment,
                ], function ($message) use ($appointment) {
                    $message->to($appointment->patient->email)
                        ->subject('Votre rendez-vous est confirmé - Activez votre compte');
                });
            } else {
                Mail::send('emails.appointment-confirmation', [
                    'patient' => $appointment->patient,
                    'appointment' => $appointment,
                ], function ($message) use ($appointment) {
                    $message->to($appointment->patient->email)
                        ->subject('Confirmation de votre rendez-vous');
                });
            }
        } catch (\Exception $e) {
            Log::error('Erreur envoi email: ' . $e->getMessage());
            // Don't block appointment creation if email fails
        }
    }

    /**
     * Get success message
     */
    private function getSuccessMessage($serviceRequest, bool $accountCreated): string
    {
        $message = 'Rendez-vous créé avec succès.';

        if ($serviceRequest) {
            $message .= ' La demande #' . $serviceRequest->id . ' a été convertie.';
            if ($accountCreated) {
                $message .= ' Un email d\'activation a été envoyé au patient.';
            }
        }

        return $message;
    }

}
