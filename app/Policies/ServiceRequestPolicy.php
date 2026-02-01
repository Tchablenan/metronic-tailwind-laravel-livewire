<?php

namespace App\Policies;

use App\Models\ServiceRequest;
use App\Models\User;

class ServiceRequestPolicy
{
    /**
     * Voir la liste des demandes de service
     */
    public function viewAny(User $user): bool
    {
        // Chef et Secretary peuvent voir les demandes
        return in_array($user->role, ['doctor', 'secretary']);
    }

    /**
     * Voir une demande spécifique
     */
    public function view(User $user, ServiceRequest $serviceRequest): bool
    {
        // Chef et Secretary peuvent voir
        return in_array($user->role, ['doctor', 'secretary']);
    }

    /**
     * Créer une demande
     * Secrétaire et Chef médecin peuvent créer
     */
    public function create(User $user): bool
    {
        // Secrétaire peut créer (au cabinet)
        if ($user->role === 'secretary') {
            return true;
        }

        // Chef médecin peut créer (admin)
        if ($user->role === 'doctor' && $user->is_chief) {
            return true;
        }

        return false;
    }

    /**
     * Modifier une demande
     */
    public function update(User $user, ServiceRequest $serviceRequest): bool
    {
        // Chef et Secretary seulement
        return in_array($user->role, ['doctor', 'secretary']);
    }

    /**
     * Supprimer une demande
     */
    public function delete(User $user, ServiceRequest $serviceRequest): bool
    {
        // Chef et Secretary seulement
        return in_array($user->role, ['doctor', 'secretary']);
    }
}
