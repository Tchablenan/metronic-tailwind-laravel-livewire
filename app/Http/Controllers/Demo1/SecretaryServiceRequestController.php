<?php

namespace App\Http\Controllers\Demo1;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Notifications\ServiceRequestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SecretaryServiceRequestController extends Controller
{
    /**
     * Lister les demandes de service (secrétaire)
     */
    public function index()
    {
        $this->authorize('viewAny', ServiceRequest::class);

        $serviceRequests = ServiceRequest::query()
            ->orderByDesc('created_at')
            ->paginate(5);

        return view('demo1.secretary.service-requests.index', compact('serviceRequests'));
    }

    /**
     * Afficher une demande de service
     */
    public function show(ServiceRequest $serviceRequest)
    {
        $this->authorize('view', $serviceRequest);

        return view('demo1.secretary.service-requests.show', compact('serviceRequest'));
    }

    /**
     * Formulaire de création d'une ServiceRequest
     */
    public function create()
    {
        $this->authorize('create', ServiceRequest::class);

        return view('demo1.secretary.service-requests.create');
    }

    /**
     * Enregistrer une nouvelle ServiceRequest créée par la secrétaire
     */
    public function store(Request $request)
    {
        $this->authorize('create', ServiceRequest::class);

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'service_type' => 'required|in:appointment,home_visit,emergency,transport,consultation',
            'urgency' => 'required|in:low,medium,high',
            'message' => 'nullable|string|max:2000',
            'preferred_date' => 'nullable|date|after_or_equal:today',
            'preferred_time' => 'nullable|date_format:H:i',
            'payment_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,mobile_money,bank_transfer',
        ], [
            'first_name.required' => 'Le prénom est obligatoire.',
            'last_name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être valide.',
            'phone_number.required' => 'Le téléphone est obligatoire.',
            'service_type.required' => 'Le type de service est obligatoire.',
            'urgency.required' => 'Le niveau d\'urgence est obligatoire.',
            'payment_amount.required' => 'Le montant est obligatoire.',
            'payment_amount.numeric' => 'Le montant doit être un nombre.',
            'payment_method.required' => 'La méthode de paiement est obligatoire.',
        ]);

        DB::beginTransaction();

        try {
            // Compléter les données
            $validated['status'] = 'pending';
            $validated['payment_status'] = 'paid'; // Déjà payé au cabinet
            $validated['created_by_secretary'] = true; // Flag pour identifier la source
            $validated['handled_by_secretary'] = Auth::id();
            $validated['paid_at'] = now();
            $validated['handled_by'] = Auth::id();
            $validated['handled_at'] = now();

            $serviceRequest = ServiceRequest::create($validated);

            // Notifier le médecin chef
            $chief = User::where('role', 'doctor')
                ->where('is_chief', true)
                ->where('is_active', true)
                ->first();

            if ($chief) {
                $chief->notify(new ServiceRequestNotification($serviceRequest, 'forwarded'));
            }

            DB::commit();

            return redirect()->route('secretary.service-requests.show', $serviceRequest)
                ->with('success', 'Demande créée avec succès. Le médecin chef a été notifié.');
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Erreur création ServiceRequest par secrétaire: ' . $e->getMessage());

            return back()->withErrors(['error' => 'Erreur lors de la création : ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Marquer comme payé
     */
    public function markPaid(Request $request, ServiceRequest $serviceRequest)
    {
        $request->validate([
            'payment_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,mobile_money,card,insurance',
        ], [
            'payment_amount.required' => 'Le montant est obligatoire.',
            'payment_amount.numeric' => 'Le montant doit être un nombre.',
            'payment_amount.min' => 'Le montant doit être positif.',
            'payment_method.required' => 'La méthode de paiement est obligatoire.',
        ]);

        $serviceRequest->update([
            'payment_status' => 'paid',
            'payment_amount' => $request->payment_amount,
            'payment_method' => $request->payment_method,
            'paid_at' => now(),
            'handled_by' => Auth::id(),
            'handled_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Paiement enregistré avec succès.');
    }

    /**
     * Send to chief doctor
     */
    public function sendToDoctor(ServiceRequest $serviceRequest)
    {
        // Check payment
        if ($serviceRequest->payment_status !== 'paid') {
            return redirect()->back()->with('error', 'Le patient doit d\'abord payer avant d\'envoyer au médecin.');
        }

        $serviceRequest->update([
            'sent_to_doctor' => true,
            'sent_to_doctor_at' => now(),
            'sent_by' => Auth::id(),
            'status' => 'contacted',
        ]);

        // Notify chief doctors
        $chiefDoctors = User::where('role', 'doctor')
            ->where('is_chief', true)
            ->where('is_active', true)
            ->get();

        foreach ($chiefDoctors as $doctor) {
            $doctor->notify(new \App\Notifications\ServiceRequestNotification($serviceRequest, 'forwarded'));
        }

        return redirect()->back()->with('success', 'Demande envoyée au médecin chef avec succès. Les notifications ont été envoyées.');
    }

    /**
     * Cancel doctor notification (if error)
     */
    public function cancelSendToDoctor(ServiceRequest $serviceRequest)
    {
        $serviceRequest->update([
            'sent_to_doctor' => false,
            'sent_to_doctor_at' => null,
            'sent_by' => null,
        ]);

        return redirect()->back()->with('success', 'Envoi au médecin annulé.');
    }
}
