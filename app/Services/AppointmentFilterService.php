<?php

namespace App\Services;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentFilterService
{
    /**
     * Apply filters to appointment query based on request
     */
    public function applyFilters(Builder $query, Request $request): Builder
    {
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                    ->orWhere('reason', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Date filter
        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        // Doctor filter
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        // Patient filter
        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        return $query;
    }

    /**
     * Apply role-based filters
     */
    public function applyRoleBasedFilters(Builder $query, $user): Builder
    {
        // ✅ PATIENT : Voit uniquement son rendez-vous
        if ($user->role === 'patient') {
            $query->where('patient_id', $user->id);
        }
        // ✅ INFIRMIER : Voit ses rendez-vous assignés (sauf si show_all)
        elseif ($user->role === 'nurse') {
            // Allow nurses to see all if explicitly requested
            if (!request()->filled('show_all')) {
                $query->where('nurse_id', $user->id);
            }
        }
        // ✅ MÉDECIN RÉGULIER : Voit UNIQUEMENT ses propres rendez-vous
        elseif ($user->role === 'doctor' && !$user->isChief()) {
            $query->where('doctor_id', $user->id);
        }
        // ✅ MÉDECIN CHEF : Voit TOUS les rendez-vous (pas de filtre)
        // ✅ SECRÉTAIRE : Voit TOUS les rendez-vous (pas de filtre)

        return $query;
    }

    /**
     * Get default sort order
     */
    public function applySorting(Builder $query): Builder
    {
        return $query->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc');
    }
}
