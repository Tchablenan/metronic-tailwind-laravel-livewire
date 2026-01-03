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
     * Liste des demandes de service
     */
    public function index(Request $request)
    {
        $query = ServiceRequest::with(['patient', 'handler', 'appointment'])
            ->orderBy('created_at', 'desc');

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
        }

        if ($request->filled('urgency')) {
            $query->where('urgency', $request->urgency);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        $serviceRequests = $query->paginate(20)->withQueryString();

        $statuses = [
            'pending' => 'En attente',
            'contacted' => 'Contacté',
            'converted' => 'Converti en RDV',
            'rejected' => 'Rejeté',
            'cancelled' => 'Annulé',
        ];

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

        return view('demo1.service-requests.index', compact(
            'serviceRequests',
            'statuses',
            'serviceTypes',
            'urgencies'
        ));
    }

    /**
     * Voir les détails d'une demande
     */
    public function show(ServiceRequest $serviceRequest)
    {
        $serviceRequest->load(['patient', 'handler', 'appointment']);

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
    public function convertToAppointment(ServiceRequest $serviceRequest)
    {
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
                'password' => Hash::make(Str::random(32)), // Mot de passe aléatoire temporaire
                'is_active' => false, // Compte inactif jusqu'à activation
                'email_verified_at' => null, // Email non vérifié
                'activation_token' => $activationToken,
                'activation_token_expires_at' => now()->addDays(7), // Token valide 7 jours
            ]);

            $accountCreated = true;
        }
                // Créer le rendez-vous
        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $request->doctor_id ?? null,
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
                // Log l'erreur mais continue
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
