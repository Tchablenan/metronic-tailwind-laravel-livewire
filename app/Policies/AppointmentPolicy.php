<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    /**
     * Voir la liste des rendez-vous
     *
     * Note : Cette méthode autorise l'accès à la page de liste.
     * Le filtrage des RDV visibles se fait dans le Controller.
     */
    public function viewAny(User $user): bool
    {
        // Les médecins (chef + réguliers), secrétaires et infirmiers peuvent voir la liste
        // Le filtrage selon le rôle se fait dans AppointmentController@index
        return in_array($user->role, ['doctor', 'secretary', 'nurse', 'patient', 'home_care_member']);
    }

    /**
     * Voir un rendez-vous spécifique
     *
     * Permissions :
     * - Médecin chef : Voit TOUS les RDV
     * - Médecin régulier : Voit UNIQUEMENT ses RDV
     * - Infirmier : Voit ses RDV assignés
     * - Patient : Voit son RDV
     * - Secrétaire : Voit TOUS les RDV
     */
    public function view(User $user, Appointment $appointment): bool
    {
        // ✅ Médecin chef peut voir TOUS les rendez-vous (supervision globale)
        if ($user->isChief()) {
            return true;
        }

        // ✅ Médecin régulier peut voir UNIQUEMENT ses propres RDV
        if ($user->role === 'doctor' && $appointment->doctor_id === $user->id) {
            return true;
        }

        // ✅ Infirmier peut voir les RDV où il est assigné
        if ($user->role === 'nurse' && $appointment->nurse_id === $user->id) {
            return true;
        }

        // ✅ Patient peut voir son propre rendez-vous
        if ($user->role === 'patient' && $appointment->patient_id === $user->id) {
            return true;
        }

        // ✅ Secrétaire peut voir tous les rendez-vous (gestion administrative)
        if ($user->role === 'secretary') {
            return true;
        }

        // ❌ Tous les autres cas : accès refusé
        return false;
    }

    /**
     * Créer un rendez-vous
     */
    public function create(User $user): bool
    {


        return in_array($user->role, ['doctor', 'secretary', 'nurse', 'patient']);
    }

    /**
     * Modifier un rendez-vous
     *
     * Permissions :
     * - Médecin chef : Modifie TOUS les RDV
     * - Médecin régulier : Modifie UNIQUEMENT ses RDV
     * - Patient : Modifie son RDV (si modifiable)
     */
    public function update(User $user, Appointment $appointment): bool
    {
        // ✅ Le médecin chef peut modifier TOUS les rendez-vous
        if ($user->isChief()) {
            return true;
        }

        // ✅ Un médecin régulier peut modifier UNIQUEMENT ses propres RDV
        if ($user->role === 'doctor' && $appointment->doctor_id === $user->id) {
            return $appointment->canBeModified();
        }

        // ✅ Un patient peut modifier son propre rendez-vous (avec restrictions)
        if ($user->role === 'patient' && $appointment->patient_id === $user->id) {
            return $appointment->canBeModified();
        }

        // ❌ Tous les autres rôles ne peuvent PAS modifier
        return false;
    }
    /**
     * Supprimer/Annuler un rendez-vous
     *
     * Permissions :
     * - Médecin chef : Supprime TOUS les RDV
     * - Médecin régulier : Supprime UNIQUEMENT ses RDV
     * - Secrétaire : Supprime les RDV (si annulables)
     */
    public function delete(User $user, Appointment $appointment): bool
    {
        // ✅ Médecin chef peut supprimer tous les rendez-vous
        if ($user->isChief()) {
            return true;
        }

        // ✅ Médecin régulier peut supprimer UNIQUEMENT ses propres RDV
        if ($user->role === 'doctor' && $appointment->doctor_id === $user->id) {
            return $appointment->canBeCancelled();
        }

        // ✅ Secrétaire peut supprimer les rendez-vous (si annulables)
        if ($user->role === 'secretary') {
            return $appointment->canBeCancelled();
        }

        // ❌ Autres rôles ne peuvent pas supprimer
        return false;
    }
}
