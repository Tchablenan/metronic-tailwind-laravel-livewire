<?php

namespace App\Http\Controllers\Demo1;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SecretaryServiceRequestController extends Controller
{
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
     * Envoyer au médecin chef
     */
    public function sendToDoctor(ServiceRequest $serviceRequest)
    {
        // Vérifier que c'est payé
        if ($serviceRequest->payment_status !== 'paid') {
            return redirect()->back()->with('error', 'Le patient doit d\'abord payer avant d\'envoyer au médecin.');
        }

        $serviceRequest->update([
            'sent_to_doctor' => true,
            'sent_to_doctor_at' => now(),
            'sent_by' => Auth::id(),
            'status' => 'contacted', // Marquer comme contacté
        ]);

        // TODO: Envoyer notification au médecin chef

        return redirect()->back()->with('success', 'Demande envoyée au médecin chef avec succès.');
    }

    /**
     * Annuler l'envoi au médecin (si erreur)
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
