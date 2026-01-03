<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    /**
     * Voir la liste des rendez-vous
     */
    public function viewAny(User $user): bool
    {
        // Tous les rôles sauf partner
        return in_array($user->role, ['doctor', 'nurse', 'secretary', 'patient', 'home_care_member']);
    }

    /**
     * Voir un rendez-vous spécifique
     */
    public function view(User $user, Appointment $appointment): bool
    {
        // Doctor et secretary peuvent voir tous les rendez-vous
        if (in_array($user->role, ['doctor', 'secretary'])) {
            return true;
        }

        // Patient ne peut voir que ses propres rendez-vous
        if ($user->role === 'patient') {
            return $appointment->patient_id === $user->id;
        }

        // Nurse et home_care_member peuvent voir les rendez-vous où ils sont assignés
        if (in_array($user->role, ['nurse', 'home_care_member'])) {
            return $appointment->doctor_id === $user->id
                || $appointment->nurse_id === $user->id;
        }

        return false;
    }

    /**
     * Créer un rendez-vous
     */
    public function create(User $user): bool
    {


        return in_array($user->role, ['doctor', 'secretary', 'nurse','patient']);
    }

    /**
     * Modifier un rendez-vous
     */
    public function update(User $user, Appointment $appointment): bool
    {
        // Doctor et secretary peuvent modifier tous les rendez-vous
        if (in_array($user->role, ['doctor', 'secretary'])) {
            return true;
        }

        // Nurse peut modifier les rendez-vous où il est assigné
        if ($user->role === 'nurse') {
            return $appointment->nurse_id === $user->id;
        }

        // Patient peut modifier (annuler) ses propres rendez-vous
        if ($user->role === 'patient') {
            return $appointment->patient_id === $user->id
                && $appointment->canBeModified();
        }

        return false;
    }

    /**
     * Supprimer un rendez-vous
     */
    public function delete(User $user, Appointment $appointment): bool
    {
        // Seuls doctor et secretary peuvent supprimer
        return in_array($user->role, ['doctor', 'secretary']);
    }
}
