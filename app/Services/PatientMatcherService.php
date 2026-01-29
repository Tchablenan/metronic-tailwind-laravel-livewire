<?php

namespace App\Services;

use App\Models\User;
use App\Models\ServiceRequest;
use Illuminate\Support\Str;

class PatientMatcherService
{
    /**
     * Find or create patient from service request
     * Implements intelligent matching: Perfect match > Email match > Phone match
     */
    public function matchOrCreatePatient(ServiceRequest $serviceRequest): array
    {
        $patient = null;
        $warning = null;

        // 1. Perfect match: email AND phone number
        $perfectMatch = User::where('role', 'patient')
            ->where('email', $serviceRequest->email)
            ->where('phone_number', $serviceRequest->phone_number)
            ->first();

        if ($perfectMatch) {
            return [
                'patient' => $perfectMatch,
                'warning' => null,
                'created' => false,
            ];
        }

        // 2. Email match (more reliable than phone)
        $emailMatch = User::where('role', 'patient')
            ->where('email', $serviceRequest->email)
            ->first();

        if ($emailMatch) {
            // Check if name matches
            if (
                strtolower($emailMatch->first_name) !== strtolower($serviceRequest->first_name) ||
                strtolower($emailMatch->last_name) !== strtolower($serviceRequest->last_name)
            ) {
                $warning = "⚠️ Attention : Le nom du patient trouvé ({$emailMatch->full_name}) ne correspond pas au demandeur ({$serviceRequest->full_name}).";
            }

            return [
                'patient' => $emailMatch,
                'warning' => $warning,
                'created' => false,
            ];
        }

        // 3. Phone match (least reliable)
        $phoneMatch = User::where('role', 'patient')
            ->where('phone_number', $serviceRequest->phone_number)
            ->first();

        if ($phoneMatch && strtolower($phoneMatch->email) === strtolower($serviceRequest->email)) {
            return [
                'patient' => $phoneMatch,
                'warning' => null,
                'created' => false,
            ];
        }

        // 4. No match found - return with warning if phone match but different email
        if ($phoneMatch) {
            $warning = "⚠️ Un patient avec ce numéro existe ({$phoneMatch->full_name} - {$phoneMatch->email}), mais l'email ne correspond pas. Veuillez sélectionner manuellement le bon patient ou laisser vide pour créer un nouveau compte.";
        }

        return [
            'patient' => null,
            'warning' => $warning,
            'created' => false,
        ];
    }

    /**
     * Create new patient from service request
     */
    public function createPatientFromServiceRequest(ServiceRequest $serviceRequest): User
    {
        return User::create([
            'first_name' => $serviceRequest->first_name,
            'last_name' => $serviceRequest->last_name,
            'email' => $serviceRequest->email,
            'phone_number' => $serviceRequest->phone_number,
            'password' => bcrypt(Str::random(16)),
            'role' => 'patient',
            'is_active' => true,
        ]);
    }
}
