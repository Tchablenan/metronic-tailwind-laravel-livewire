<?php

namespace App\Http\Controllers\Demo1;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ServiceRequestController extends Controller
{
    /**
     * Liste des demandes de service (avec filtrage par rôle)
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // =====================================================
        // RÉCUPÉRER LES PARAMÈTRES DE FILTRE
        // =====================================================
        $search = $request->input('search');
        $status = $request->input('status');
        $serviceType = $request->input('service_type');
        $urgency = $request->input('urgency');
        $paymentStatus = $request->input('payment_status');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $hasInsurance = $request->input('has_insurance');
        $hasMedicalData = $request->input('has_medical_data');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        // =====================================================
        // CONSTRUIRE LA REQUÊTE AVEC FILTRES
        // =====================================================
        $query = ServiceRequest::query();

        // =====================================================
        // FILTRAGE AUTOMATIQUE PAR RÔLE
        // =====================================================
        if ($user->role === 'doctor') {
            // Le médecin voit UNIQUEMENT les demandes payées ET envoyées
            $query->where('payment_status', 'paid')
                  ->where('sent_to_doctor', true);
        }
        // La secrétaire voit TOUT (pas de filtre)

        // =====================================================
        // APPLIQUER LES SCOPES DE FILTRAGE
        // =====================================================

        // Recherche globale
        if ($search) {
            $query->search($search);
        }

        // Filtres individuels
        if ($status) {
            $query->byStatus($status);
        }
        if ($serviceType) {
            $query->byServiceType($serviceType);
        }
        if ($urgency) {
            $query->byUrgency($urgency);
        }
        if ($paymentStatus) {
            $query->byPaymentStatus($paymentStatus);
        }
        if ($hasInsurance) {
            $query->hasInsurance();
        }
        if ($hasMedicalData) {
            $query->hasMedicalData();
        }

        // Filtre de date
        if ($dateFrom || $dateTo) {
            $query->byDateRange($dateFrom, $dateTo);
        }

        // =====================================================
        // APPLIQUER LE TRI
        // =====================================================
        $validSortFields = ['created_at', 'updated_at', 'payment_amount', 'urgency', 'status'];
        if (in_array($sortBy, $validSortFields)) {
            $query->orderBy($sortBy, strtolower($sortOrder) === 'asc' ? 'asc' : 'desc');
        } else {
            $query->orderByDesc('created_at');
        }

        // =====================================================
        // CALCULER LES STATISTIQUES SELON LE RÔLE
        // =====================================================
        $totalQuery = clone $query;

        if ($user->role === 'doctor') {
            // Stats pour le médecin : seulement demandes envoyées et payées
            $statistics = [
                'to_validate' => (clone $totalQuery)->whereNotIn('status', ['converted', 'rejected'])->count(),
                'converted' => (clone $totalQuery)->byStatus('converted')->count(),
                'rejected' => (clone $totalQuery)->byStatus('rejected')->count(),
                'urgent' => (clone $totalQuery)->byUrgency('high')->whereNotIn('status', ['converted', 'rejected'])->count(),
                'total' => $totalQuery->count(),
                'total_amount' => (clone $totalQuery)->sum('payment_amount') ?? 0,
            ];
        } else {
            // Stats pour la secrétaire : toutes les demandes
            $statistics = [
                'pending' => (clone $totalQuery)->byStatus('pending')->count(),
                'in_progress' => (clone $totalQuery)->byStatus('in_progress')->count(),
                'completed' => (clone $totalQuery)->byStatus('completed')->count(),
                'total_amount' => (clone $totalQuery)->sum('payment_amount') ?? 0,
                'total_with_medical_data' => (clone $totalQuery)->hasMedicalData()->count(),
                'total' => $totalQuery->count(),
            ];

            // Calculer le pourcentage
            $statistics['medical_data_percentage'] = $statistics['total'] > 0
                ? round(($statistics['total_with_medical_data'] / $statistics['total']) * 100, 1)
                : 0;
        }

        // =====================================================
        // PAGINER LES RÉSULTATS
        // =====================================================
        $serviceRequests = $query->paginate(10);

        // =====================================================
        // OPTIONS POUR LES FILTRES (DROPDOWNS)
        // =====================================================
        $statusOptions = [
            'pending' => 'En Attente',
            'in_progress' => 'En Traitement',
            'completed' => 'Complétée',
            'cancelled' => 'Annulée',
        ];

        $serviceTypeOptions = [
            'appointment' => 'Rendez-vous',
            'home_visit' => 'Visite à domicile',
            'emergency' => 'Urgence',
            'transport' => 'Transport',
            'consultation' => 'Consultation',
        ];

        $urgencyOptions = [
            'low' => 'Basse',
            'medium' => 'Moyenne',
            'high' => 'Haute',
        ];

        $paymentStatusOptions = [
            'pending' => 'En Attente',
            'paid' => 'Payée',
            'partial' => 'Partiellement Payée',
            'overdue' => 'En Retard',
        ];

        return view('demo1.service-requests.index', compact(
            'serviceRequests',
            'statistics',
            'search',
            'status',
            'serviceType',
            'urgency',
            'paymentStatus',
            'dateFrom',
            'dateTo',
            'hasInsurance',
            'hasMedicalData',
            'statusOptions',
            'serviceTypeOptions',
            'urgencyOptions',
            'paymentStatusOptions',
            'user'
        ));
    }

    /**
     * Voir les détails d'une demande
     */
    public function show(ServiceRequest $serviceRequest)
    {
        // Vérifier les permissions
        $user = Auth::user();

        if ($user->role === 'doctor') {
            // Le médecin ne peut voir QUE les demandes payées ET envoyées
            if ($serviceRequest->payment_status !== 'paid' || !$serviceRequest->sent_to_doctor) {
                abort(403, 'Vous n\'avez pas accès à cette demande.');
            }
        }

        $serviceRequest->load(['patient', 'handler', 'appointment', 'sender']);

        return view('demo1.service-requests.show', compact('serviceRequest'));
    }

    /**
     * Marquer comme contacté
     */
    public function markContacted(ServiceRequest $serviceRequest)
    {
        $serviceRequest->update([
            'status' => 'contacted',
            'handled_by' => Auth::id(),
            'handled_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Demande marquée comme contactée.');
    }

    /**
     * Convertir en rendez-vous
     */
    public function convertToAppointment(Request $request, ServiceRequest $serviceRequest)
    {
        // Vérifier que c'est payé (pour le médecin)
        if (Auth::user()->role === 'doctor' && $serviceRequest->payment_status !== 'paid') {
            return redirect()->back()->with('error', 'Le patient doit d\'abord payer.');
        }

        // Chercher ou créer le patient
        $patient = User::where('email', $serviceRequest->email)
            ->orWhere('phone_number', $serviceRequest->phone_number)
            ->where('role', 'patient')
            ->first();

        $accountCreated = false;

        if (!$patient) {
            // Créer un nouveau patient
            $activationToken = Str::random(64);
            $patient = User::create([
                'first_name' => $serviceRequest->first_name,
                'last_name' => $serviceRequest->last_name,
                'email' => $serviceRequest->email,
                'phone_number' => $serviceRequest->phone_number,
                'role' => 'patient',
                'password' => Hash::make(Str::random(32)),
                'is_active' => false,
                'email_verified_at' => null,
                'activation_token' => $activationToken,
                'activation_token_expires_at' => now()->addDays(7),
            ]);

            $accountCreated = true;
        }

        // Créer le rendez-vous
        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $request->doctor_id ?? Auth::id(),
            'nurse_id' => $request->nurse_id ?? null,
            'appointment_date' => $serviceRequest->preferred_date ?? now()->addDays(1),
            'appointment_time' => $serviceRequest->preferred_time ?? '10:00',
            'duration' => $request->duration ?? 30,
            'type' => $this->mapServiceTypeToAppointmentType($serviceRequest->service_type),
            'location' => $request->location ?? 'cabinet',
            'status' => 'scheduled',
            'reason' => $serviceRequest->message,
            'is_emergency' => $serviceRequest->urgency === 'high',
            'patient_notes' => 'Rendez-vous créé suite à votre demande en ligne.',
        ]);

        // Mettre à jour la ServiceRequest
        $serviceRequest->update([
            'status' => 'converted',
            'patient_id' => $patient->id,
            'appointment_id' => $appointment->id,
            'handled_by' => Auth::id(),
            'handled_at' => now(),
        ]);

        // Envoyer l'email d'activation si compte créé
        if ($accountCreated) {
            try {
                Mail::send('emails.activate-account', [
                    'patient' => $patient,
                    'appointment' => $appointment,
                    'serviceRequest' => $serviceRequest,
                    'activationLink' => route('account.activate', ['token' => $patient->activation_token])
                ], function($message) use ($patient) {
                    $message->to($patient->email)
                            ->subject('Votre rendez-vous est confirmé - Finalisez votre inscription');
                });
            } catch (\Exception $e) {
                \Log::error('Erreur envoi email activation: ' . $e->getMessage());
            }
        } else {
            // Le patient existe déjà, envoyer juste une confirmation de RDV
            try {
                Mail::send('emails.appointment-confirmation', [
                    'patient' => $patient,
                    'appointment' => $appointment,
                ], function($message) use ($patient) {
                    $message->to($patient->email)
                            ->subject('Votre rendez-vous est confirmé');
                });
            } catch (\Exception $e) {
                \Log::error('Erreur envoi email confirmation: ' . $e->getMessage());
            }
        }

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Rendez-vous créé avec succès. ' . ($accountCreated ? 'Un email d\'activation a été envoyé au patient.' : ''));
    }

    /**
     * Mapper le type de service à un type de rendez-vous
     */
    private function mapServiceTypeToAppointmentType(string $serviceType): string
    {
        return match($serviceType) {
            'appointment' => 'consultation',
            'home_visit' => 'home_visit',
            'emergency' => 'emergency',
            'transport' => 'consultation',
            'consultation' => 'consultation',
            default => 'consultation',
        };
    }

    /**
     * Rejeter une demande
     */
    public function reject(Request $request, ServiceRequest $serviceRequest)
    {
        // Seul le médecin peut rejeter
        if (Auth::user()->role !== 'doctor') {
            abort(403, 'Seul le médecin chef peut rejeter une demande.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $serviceRequest->update([
            'status' => 'rejected',
            'handled_by' => Auth::id(),
            'handled_at' => now(),
            'internal_notes' => $request->rejection_reason,
        ]);

        return redirect()->back()->with('success', 'Demande rejetée.');
    }

    /**
     * Ajouter des notes internes
     */
    public function addNotes(Request $request, ServiceRequest $serviceRequest)
    {
        $request->validate([
            'internal_notes' => 'required|string|max:1000',
        ]);

        $serviceRequest->update([
            'internal_notes' => $request->internal_notes,
        ]);

        return redirect()->back()->with('success', 'Notes ajoutées avec succès.');
    }
}
