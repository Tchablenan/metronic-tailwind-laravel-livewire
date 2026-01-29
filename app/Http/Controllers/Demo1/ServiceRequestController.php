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

        // Base query avec relations
        $query = ServiceRequest::with(['patient', 'handler', 'appointment', 'sender'])
            ->orderBy('created_at', 'desc');

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
        // FILTRES UTILISATEUR
        // =====================================================

        // Filtre par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtre par type de service
        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
        }

        // Filtre par urgence
        if ($request->filled('urgency')) {
            $query->where('urgency', $request->urgency);
        }

        // Filtre par statut de paiement
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filtre "Payé mais non envoyé" (pour secrétaire)
        if ($request->filled('paid_not_sent') && $user->role === 'secretary') {
            $query->where('payment_status', 'paid')
                  ->where('sent_to_doctor', false)
                  ->whereNotIn('status', ['converted', 'rejected']);
        }

        // Filtre par envoi au médecin
        if ($request->filled('sent_to_doctor')) {
            $query->where('sent_to_doctor', $request->sent_to_doctor === 'true');
        }

        // Recherche globale
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        // Filtre par date de création
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Tri personnalisé
        if ($request->filled('sort_by')) {
            $sortBy = $request->sort_by;
            $sortOrder = $request->get('sort_order', 'desc');

            switch ($sortBy) {
                case 'urgency':
                    $query->orderByRaw("CASE urgency WHEN 'high' THEN 1 WHEN 'medium' THEN 2 ELSE 3 END");
                    break;
                case 'payment_date':
                    $query->orderBy('paid_at', $sortOrder);
                    break;
                case 'amount':
                    $query->orderBy('payment_amount', $sortOrder);
                    break;
                default:
                    $query->orderBy($sortBy, $sortOrder);
            }
        }

        // Pagination
        $serviceRequests = $query->paginate(20)->withQueryString();

        // =====================================================
        // STATISTIQUES SELON LE RÔLE
        // =====================================================

        if ($user->role === 'doctor') {
            // Stats pour le médecin : seulement demandes envoyées
            $stats = [
                'total' => ServiceRequest::where('payment_status', 'paid')
                                        ->where('sent_to_doctor', true)
                                        ->count(),
                'to_validate' => ServiceRequest::where('payment_status', 'paid')
                                              ->where('sent_to_doctor', true)
                                              ->whereNotIn('status', ['converted', 'rejected'])
                                              ->count(),
                'converted' => ServiceRequest::where('status', 'converted')
                                            ->where('sent_to_doctor', true)
                                            ->count(),
                'rejected' => ServiceRequest::where('status', 'rejected')
                                           ->where('sent_to_doctor', true)
                                           ->count(),
                'urgent' => ServiceRequest::where('payment_status', 'paid')
                                         ->where('sent_to_doctor', true)
                                         ->where('urgency', 'high')
                                         ->whereNotIn('status', ['converted', 'rejected'])
                                         ->count(),
            ];

            // Statuts disponibles pour le médecin
            $statuses = [
                'contacted' => 'En attente de validation',
                'converted' => 'Converti en RDV',
                'rejected' => 'Rejeté',
            ];
        } else {
            // Stats pour la secrétaire : toutes les demandes
            $stats = [
                'total' => ServiceRequest::count(),
                'pending' => ServiceRequest::where('status', 'pending')->count(),
                'unpaid' => ServiceRequest::where('payment_status', 'unpaid')
                                         ->whereIn('status', ['pending', 'contacted'])
                                         ->count(),
                'paid_not_sent' => ServiceRequest::where('payment_status', 'paid')
                                                 ->where('sent_to_doctor', false)
                                                 ->whereNotIn('status', ['converted', 'rejected'])
                                                 ->count(),
                'sent_to_doctor' => ServiceRequest::where('sent_to_doctor', true)->count(),
                'converted' => ServiceRequest::where('status', 'converted')->count(),
                'total_amount_today' => ServiceRequest::where('payment_status', 'paid')
                                                     ->whereDate('paid_at', today())
                                                     ->sum('payment_amount'),
            ];

            // Statuts disponibles pour la secrétaire
            $statuses = [
                'pending' => 'En attente',
                'contacted' => 'Contacté',
                'converted' => 'Converti en RDV',
                'rejected' => 'Rejeté',
                'cancelled' => 'Annulé',
            ];
        }

        // =====================================================
        // OPTIONS DE FILTRES (identiques pour tous)
        // =====================================================

        $serviceTypes = [
            'appointment' => 'Rendez-vous médical',
            'home_visit' => 'Visite à domicile',
            'emergency' => 'Urgence',
            'transport' => 'Transport médicalisé',
            'consultation' => 'Consultation',
            'other' => 'Autre',
        ];

        $urgencies = [
            'low' => 'Faible',
            'medium' => 'Moyenne',
            'high' => 'Élevée',
        ];

        $paymentStatuses = [
            'unpaid' => 'Non payé',
            'paid' => 'Payé',
            'refunded' => 'Remboursé',
        ];

        $paymentMethods = [
            'cash' => 'Espèces',
            'mobile_money' => 'Mobile Money',
            'card' => 'Carte bancaire',
            'insurance' => 'Assurance',
        ];

        return view('demo1.service-requests.index', compact(
            'serviceRequests',
            'stats',
            'statuses',
            'serviceTypes',
            'urgencies',
            'paymentStatuses',
            'paymentMethods'
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
