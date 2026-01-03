<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Voir la liste des utilisateurs
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['doctor', 'secretary']);
    }

    /**
     * Voir un utilisateur spécifique
     */
    public function view(User $user, User $model): bool
    {
        // Doctor et secretary peuvent voir tous les utilisateurs
        if (in_array($user->role, ['doctor', 'secretary'])) {
            return true;
        }

        // Chacun peut voir son propre profil
        return $user->id === $model->id;
    }

    /**
     * Créer un utilisateur
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['doctor', 'secretary']);
    }

    /**
     * Modifier un utilisateur
     */
    public function update(User $user, User $model): bool
    {
        // Doctor et secretary peuvent modifier tous les utilisateurs
        if (in_array($user->role, ['doctor', 'secretary'])) {
            return true;
        }

        // Chacun peut modifier son propre profil
        return $user->id === $model->id;
    }

    /**
     * Supprimer un utilisateur
     */
    public function delete(User $user, User $model): bool
    {
        // Seul le doctor peut supprimer
        // Et ne peut pas se supprimer lui-même
        return $user->role === 'doctor' && $user->id !== $model->id;
    }
}
