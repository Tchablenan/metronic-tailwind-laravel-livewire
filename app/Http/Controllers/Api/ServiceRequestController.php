<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Notifications\ServiceRequestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
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

            // Send notification to team (all secretaries)
            $secretaries = User::where('role', 'secretary')
                ->where('is_active', true)
                ->get();

            foreach ($secretaries as $secretary) {
                $secretary->notify(new \App\Notifications\ServiceRequestNotification($serviceRequest, 'received'));
            }

            // Send confirmation to patient
            try {
                $patientEmail = $serviceRequest->email;
                Mail::send('emails.service-request-confirmation', [
                    'serviceRequest' => $serviceRequest,
                ], function ($message) use ($patientEmail) {
                    $message->to($patientEmail)
                        ->subject('Confirmation de réception de votre demande de service');
                });
                Log::info('Service request confirmation email sent to: ' . $patientEmail);
            } catch (\Exception $e) {
                Log::error('Error sending confirmation email: ' . $e->getMessage());
            }

            // TODO: SMS de confirmation au patient (nécessite configuration Twilio ou similaire)
            // if ($serviceRequest->phone_number) {
            //     SmsService::send($serviceRequest->phone_number, 'Votre demande a été reçue. Référence: ' . $serviceRequest->id);
            // }

            return response()->json([
                'success' => true,
                'message' => 'Votre demande a été envoyée avec succès. Nous vous contacterons dans les plus brefs délais.',
                'data' => [
                    'reference' => $serviceRequest->id,
                    'email' => $serviceRequest->email,
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error('ServiceRequest creation error: ' . $e->getMessage());
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
