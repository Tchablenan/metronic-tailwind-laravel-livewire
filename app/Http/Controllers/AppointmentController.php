<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;

class AppointmentController extends Controller
{

    use AuthorizesRequests;
    /**
     * Liste des rendez-vous avec filtres
     */

        private function getViewPrefix(): string
    {
        $role = Auth::user()->role;

        return match($role) {
            'doctor' => 'demo1.doctor.appointments',
            'secretary' => 'demo1.secretary.appointments',
            'nurse' => 'demo1.nurse.appointments',
            'patient' => 'demo1.patient.appointments',
            default => 'demo1.doctor.appointments',
        };
    }
    public function index(Request $request)
    {
        $this->authorize('viewAny', Appointment::class);

        $query = Appointment::with(['patient', 'doctor', 'nurse']);

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('patient', function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('reason', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        // Filtres par rôle
        $user = Auth::user();

        if ($user->role === 'patient') {
            $query->where('patient_id', $user->id);
        }

        if ($user->role === 'nurse' && !$request->filled('show_all')) {
            $query->where('nurse_id', $user->id);
        }

        // Tri par défaut
        $appointments = $query->orderBy('appointment_date', 'desc')
                             ->orderBy('appointment_time', 'desc')
                             ->paginate(20)
                             ->withQueryString();

        // Données pour les filtres
        $statuses = [
            'scheduled' => 'Prévu',
            'confirmed' => 'Confirmé',
            'in_progress' => 'En cours',
            'completed' => 'Terminé',
            'cancelled' => 'Annulé',
            'no_show' => 'Absent',
        ];

        $types = [
            'consultation' => 'Consultation',
            'followup' => 'Suivi',
            'exam' => 'Examen',
            'emergency' => 'Urgence',
            'vaccination' => 'Vaccination',
            'home_visit' => 'Visite à domicile',
            'telehealth' => 'Téléconsultation',
        ];

        $doctors = User::where('role', 'doctor')->get();
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
     * Formulaire de création
     */
    public function create()
    {
        $this->authorize('create', Appointment::class);

        $patients = User::where('role', 'patient')
            ->orderBy('first_name')
            ->get();

        $doctors = User::where('role', 'doctor')
            ->orderBy('first_name')
            ->get();

        $nurses = User::where('role', 'nurse')
            ->orderBy('first_name')
            ->get();

        $types = [
            'consultation' => 'Consultation',
            'followup' => 'Suivi',
            'exam' => 'Examen',
            'emergency' => 'Urgence',
            'vaccination' => 'Vaccination',
            'home_visit' => 'Visite à domicile',
            'telehealth' => 'Téléconsultation',
        ];

        $locations = [
            'cabinet' => 'Cabinet',
            'domicile' => 'Domicile',
            'urgence' => 'Urgence',
            'hopital' => 'Hôpital',
        ];

        $viewPrefix = $this->getViewPrefix();

        return view("{$viewPrefix}.create", compact(
            'patients',
            'doctors',
            'nurses',
            'types',
            'locations'
        ));
    }

    /**
     * Enregistrer un nouveau rendez-vous
     */
    public function store(Request $request)
    {
        $this->authorize('create', Appointment::class);

        $validated = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'doctor_id' => 'nullable|exists:users,id',
            'nurse_id' => 'nullable|exists:users,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:15|max:240',
            'type' => 'required|in:consultation,followup,exam,emergency,vaccination,home_visit,telehealth',
            'reason' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:2000',
            'patient_notes' => 'nullable|string|max:1000',
            'location' => 'required|in:cabinet,domicile,urgence,hopital',
            'price' => 'nullable|numeric|min:0',
            'is_emergency' => 'boolean',
        ]);

        // Convertir HH:MM en HH:MM:SS
        $validated['appointment_time'] = $validated['appointment_time'] . ':00';

        // ⬇️⬇️⬇️ AJOUTE CETTE LIGNE - Cast duration en integer ⬇️⬇️⬇️
        $validated['duration'] = (int) $validated['duration'];

        // Vérifier les conflits d'horaire
        if ($request->filled('doctor_id')) {
            $startTime = $validated['appointment_time'];
            // ⬇️ Maintenant $validated['duration'] est un int
            $endTime = Carbon::parse($startTime)->addMinutes($validated['duration'])->format('H:i:s');

            $hasConflict = Appointment::where('doctor_id', $validated['doctor_id'])
                ->where('appointment_date', $validated['appointment_date'])
                ->whereIn('status', ['scheduled', 'confirmed', 'in_progress'])
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->where(function ($q) use ($startTime, $endTime) {
                        $q->where('appointment_time', '<', $endTime)
                            ->whereRaw('ADDTIME(appointment_time, SEC_TO_TIME(duration * 60)) > ?', [$startTime]);
                    });
                })
                ->exists();

            if ($hasConflict) {
                return back()->withErrors([
                    'appointment_time' => 'Ce créneau horaire n\'est pas disponible pour ce médecin.'
                ])->withInput();
            }
        }

        DB::beginTransaction();
        try {
            $appointment = Appointment::create($validated);
            DB::commit();

            return redirect()->route('appointments.index')
                ->with('success', 'Rendez-vous créé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erreur: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Afficher les détails d'un rendez-vous
     */
    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);

        $appointment->load(['patient', 'doctor', 'nurse']);
        $viewPrefix = $this->getViewPrefix();

        return view("{$viewPrefix}.show", compact('appointment'));
    }

    /**
     * Formulaire de modification
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
            ->orderBy('first_name')
            ->get();

        $nurses = User::where('role', 'nurse')
            ->orderBy('first_name')
            ->get();

        $types = [
            'consultation' => 'Consultation',
            'followup' => 'Suivi',
            'exam' => 'Examen',
            'emergency' => 'Urgence',
            'vaccination' => 'Vaccination',
            'home_visit' => 'Visite à domicile',
            'telehealth' => 'Téléconsultation',
        ];

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
     * Mettre à jour un rendez-vous
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
            'reason' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:2000',
            'patient_notes' => 'nullable|string|max:1000',
            'location' => 'required|in:cabinet,domicile,urgence,hopital',
            'price' => 'nullable|numeric|min:0',
            'is_emergency' => 'boolean',
        ]);

        // Convertir les valeurs
        $validated['appointment_time'] = $validated['appointment_time'] . ':00';
        $validated['duration'] = (int) $validated['duration']; // ⬅️ AJOUTE CETTE LIGNE

        // Vérifier les conflits d'horaire
        if ($request->filled('doctor_id')) {
            $hasConflict = $appointment->hasConflictWith(
                $request->doctor_id,
                $request->appointment_date,
                $validated['appointment_time'],
                $validated['duration'] // Maintenant c'est un int
            );

            if ($hasConflict) {
                return back()->withErrors([
                    'appointment_time' => 'Ce créneau horaire n\'est pas disponible pour ce médecin.'
                ])->withInput();
            }
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
     * Supprimer un rendez-vous
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
     * Confirmer un rendez-vous (AJAX)
     */
    public function confirm(Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        if ($appointment->confirm()) {
            return response()->json([
                'success' => true,
                'message' => 'Rendez-vous confirmé avec succès.',
                'status' => $appointment->status,
                'status_label' => $appointment->status_label,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Impossible de confirmer ce rendez-vous.',
        ], 400);
    }

    /**
     * Démarrer un rendez-vous (AJAX)
     */
    public function start(Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        if ($appointment->start()) {
            return response()->json([
                'success' => true,
                'message' => 'Rendez-vous démarré.',
                'status' => $appointment->status,
                'status_label' => $appointment->status_label,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Impossible de démarrer ce rendez-vous.',
        ], 400);
    }

    /**
     * Terminer un rendez-vous (AJAX)
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
                'status_label' => $appointment->status_label,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Impossible de terminer ce rendez-vous.',
        ], 400);
    }

    /**
     * Annuler un rendez-vous (AJAX)
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
                'status_label' => $appointment->status_label,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Impossible d\'annuler ce rendez-vous.',
        ], 400);
    }

    /**
     * Vérifier la disponibilité d'un créneau (AJAX)
     */
    public function checkAvailability(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:15',
            'appointment_id' => 'nullable|exists:appointments,id', // Pour l'édition
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
}
