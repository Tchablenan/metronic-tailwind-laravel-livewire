<?php

namespace App\Policies;

use App\Models\ServiceRequest;
use App\Models\User;

class ServiceRequestPolicy
{
    /**
     * Voir la liste des demandes de service
     * 
     * Permissions:
     * - Médecin chef : PEUT voir TOUTES les demandes
     * - Secrétaire : PEUT voir TOUTES les demandes
     * - Médecin régulier : NE PEUT PAS accéder
     */
    public function viewAny(User $user): bool
    {
        // ✅ Médecin chef peut voir
        if ($user->isChief()) {
            return true;
        }

        // ✅ Secrétaire peut voir
        if ($user->role === 'secretary') {
            return true;
        }

        // ❌ Médecin régulier NE peut pas voir
        return false;
    }

    /**
     * Voir une demande spécifique
     * 
     * Permissions:
     * - Médecin chef : PEUT voir n'importe quelle demande
     * - Secrétaire : PEUT voir n'importe quelle demande
     * - Médecin régulier : NE PEUT PAS accéder
     */
    public function view(User $user, ServiceRequest $serviceRequest): bool
    {
        // ✅ Médecin chef peut voir
        if ($user->isChief()) {
            return true;
        }

        // ✅ Secrétaire peut voir
        if ($user->role === 'secretary') {
            return true;
        }

        // ❌ Médecin régulier NE peut pas voir
        return false;
    }

    /**
     * Créer une demande
     * Seule la secrétaire peut créer
     */
    public function create(User $user): bool
    {
        return $user->role === 'secretary';
    }

    /**
     * Mettre à jour une demande
     *
     * Permissions:
     * - Admin peut modifier n'importe quelle demande
     * - Chef médecin peut modifier n'importe quelle demande
     * - Secrétaire peut modifier UNIQUEMENT si:
     *   1. Statut est "pending" (En attente)
     *   2. Aucune restriction d'ownership (tous les secrétaires peuvent modifier les pending)
     */
    public function update(User $user, ServiceRequest $serviceRequest): bool
    {
        // Admin peut modifier
        if ($user->role === 'admin') {
            return true;
        }

        // Médecin chef peut modifier
        if ($user->isChief()) {
            return true;
        }

        // Secrétaire peut modifier uniquement les demandes en attente
        if ($user->role === 'secretary') {
            return $serviceRequest->canBeEdited();
        }

        return false;
    }

    /**
     * Supprimer une demande (soft delete)
     */
    public function delete(User $user, ServiceRequest $serviceRequest): bool
    {
        // Seule l'admin peut supprimer
        return $user->role === 'admin';
    }

    /**
     * Restaurer une demande supprimée
     */
    public function restore(User $user, ServiceRequest $serviceRequest): bool
    {
        // Seule l'admin peut restaurer
        return $user->role === 'admin';
    }

    /**
     * Supprimer définitivement une demande
     */
    public function forceDelete(User $user, ServiceRequest $serviceRequest): bool
    {
        // Seule l'admin peut supprimer définitivement
        return $user->role === 'admin';
    }
}
