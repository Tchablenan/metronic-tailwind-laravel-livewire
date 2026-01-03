<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ServiceRequestController extends Controller
{
    /**
     * Créer une nouvelle demande de service (API publique)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'service_type' => ['required', Rule::in(['appointment', 'home_visit', 'emergency', 'transport', 'consultation', 'other'])],
            'message' => 'nullable|string|max:1000',
            'preferred_date' => 'nullable|date|after_or_equal:today',
            'preferred_time' => 'nullable|date_format:H:i',
            'urgency' => ['nullable', Rule::in(['low', 'medium', 'high'])],
        ], [
            'first_name.required' => 'Le prénom est obligatoire.',
            'last_name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être valide.',
            'phone_number.required' => 'Le numéro de téléphone est obligatoire.',
            'service_type.required' => 'Le type de service est obligatoire.',
            'preferred_date.after_or_equal' => 'La date doit être aujourd\'hui ou dans le futur.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $serviceRequest = ServiceRequest::create($validator->validated());

            // TODO: Envoyer notification email à l'équipe
            // TODO: Envoyer SMS de confirmation au patient

            return response()->json([
                'success' => true,
                'message' => 'Votre demande a été envoyée avec succès. Nous vous contacterons dans les plus brefs délais.',
                'data' => [
                    'reference' => $serviceRequest->id,
                    'email' => $serviceRequest->email,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'envoi de votre demande. Veuillez réessayer.'
            ], 500);
        }
    }

    /**
     * Vérifier le statut d'une demande (optionnel)
     */
    public function status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'reference' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $serviceRequest = ServiceRequest::where('id', $request->reference)
            ->where('email', $request->email)
            ->first();

        if (!$serviceRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Demande introuvable.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'reference' => $serviceRequest->id,
                'status' => $serviceRequest->status,
                'created_at' => $serviceRequest->created_at->format('d/m/Y H:i'),
                'handled' => $serviceRequest->handled_at ? true : false,
            ]
        ]);
    }
}
