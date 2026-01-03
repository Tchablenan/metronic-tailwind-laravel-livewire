<?php

namespace App\Livewire\Demo1\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UsersList extends Component
{
    use WithPagination, WithFileUploads;

    // Filtres et recherche
    public string $search = '';
    public string $filterRole = '';
    public string $sortBy = 'created_at';
    public string $sortOrder = 'desc';

    // Modals
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDetailModal = false;
    public $showDeleteModal = false;

    // Formulaire
    public $userId;
    public $first_name = '';
    public $last_name = '';
    public $email = '';
    public $phone_number = '';
    public $password = '';
    public $password_confirmation = '';
    public $role = 'patient';
    public $speciality = '';
    public $license_number = '';
    public $is_active = true;
    public $avatar;
    public $current_avatar = '';

    // Détails utilisateur
    public $selectedUser;

    /**
     * ============================================
     * LIFECYCLE
     * ============================================
     */
    public function mount()
    {
        // Vérifier que l'utilisateur est docteur (admin)
        if (!auth()->user()?->hasRole('doctor')) {
            abort(403, 'Accès refusé. Seuls les docteurs peuvent gérer les utilisateurs.');
        }
    }

    /**
     * ============================================
     * RÈGLES DE VALIDATION
     * ============================================
     */
    protected function rules()
    {
        $rules = [
            'first_name' => 'required|string|min:2|max:255',
            'last_name' => 'required|string|min:2|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->userId)
            ],
            'phone_number' => 'required|string|max:20',
            'role' => 'required|in:doctor,nurse,secretary,patient,partner,home_care_member',
            'is_active' => 'boolean',
            'avatar' => 'nullable|image|max:2048', // 2MB max
        ];

        // Mot de passe obligatoire seulement à la création
        if (!$this->userId) {
            $rules['password'] = 'required|string|min:8|confirmed';
            $rules['password_confirmation'] = 'required|string|min:8';
        }

        // Spécialité et licence seulement pour doctors/nurses
        if (in_array($this->role, ['doctor', 'nurse'])) {
            $rules['speciality'] = 'required|string|max:255';
            $rules['license_number'] = 'required|string|max:255';
        }

        return $rules;
    }

    protected $messages = [
        'first_name.required' => 'Le prénom est obligatoire',
        'last_name.required' => 'Le nom est obligatoire',
        'email.required' => 'L\'email est obligatoire',
        'email.email' => 'L\'email doit être valide',
        'email.unique' => 'Cet email est déjà utilisé',
        'phone_number.required' => 'Le téléphone est obligatoire',
        'role.required' => 'Le rôle est obligatoire',
        'password.required' => 'Le mot de passe est obligatoire',
        'password.min' => 'Le mot de passe doit contenir au moins 8 caractères',
        'password.confirmed' => 'Les mots de passe ne correspondent pas',
        'speciality.required' => 'La spécialité est obligatoire pour ce rôle',
        'license_number.required' => 'Le numéro de licence est obligatoire pour ce rôle',
        'avatar.image' => 'Le fichier doit être une image',
        'avatar.max' => 'L\'image ne doit pas dépasser 2MB',
    ];

    /**
     * ============================================
     * ACTIONS DE FILTRAGE
     * ============================================
     */
    public function resetSearch()
    {
        $this->search = '';
        $this->filterRole = '';
        $this->resetPage();
    }

    public function toggleSort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortOrder = $this->sortOrder === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortOrder = 'asc';
        }
    }

    /**
     * ============================================
     * GESTION DES MODALS
     * ============================================
     */
    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openEditModal($userId)
    {
        $user = User::findOrFail($userId);

        $this->userId = $user->id;
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->email = $user->email;
        $this->phone_number = $user->phone_number;
        $this->role = $user->role;
        $this->speciality = $user->speciality ?? '';
        $this->license_number = $user->license_number ?? '';
        $this->is_active = $user->is_active;
        $this->current_avatar = $user->avatar_url;

        $this->showEditModal = true;
    }

    public function openDetailModal($userId)
    {
        $this->selectedUser = User::findOrFail($userId);
        $this->showDetailModal = true;
    }

    public function openDeleteModal($userId)
    {
        $this->userId = $userId;
        $this->selectedUser = User::findOrFail($userId);
        $this->showDeleteModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedUser = null;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->userId = null;
        $this->selectedUser = null;
    }

    /**
     * ============================================
     * ACTIONS CRUD
     * ============================================
     */
    public function createUser()
    {
        $this->validate();

        try {
            $data = [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'phone_number' => $this->phone_number,
                'password' => Hash::make($this->password),
                'role' => $this->role,
                'is_active' => $this->is_active,
            ];

            // Champs conditionnels pour doctors/nurses
            if (in_array($this->role, ['doctor', 'nurse'])) {
                $data['speciality'] = $this->speciality;
                $data['license_number'] = $this->license_number;
            }

            // Upload de l'avatar
            if ($this->avatar) {
                $data['avatar_url'] = $this->avatar->store('avatars', 'public');
            }

            $user = User::create($data);

            // Assigner le rôle Spatie
            $user->assignRole($this->role);

            session()->flash('success', 'Utilisateur créé avec succès !');
            $this->closeCreateModal();
            $this->resetForm();

        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    public function updateUser()
    {
        $this->validate();

        try {
            $user = User::findOrFail($this->userId);

            // Empêcher de désactiver son propre compte
            if ($user->id === auth()->id() && !$this->is_active) {
                session()->flash('error', 'Vous ne pouvez pas désactiver votre propre compte!');
                return;
            }

            $data = [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'phone_number' => $this->phone_number,
                'role' => $this->role,
                'is_active' => $this->is_active,
            ];

            // Champs conditionnels pour doctors/nurses
            if (in_array($this->role, ['doctor', 'nurse'])) {
                $data['speciality'] = $this->speciality;
                $data['license_number'] = $this->license_number;
            } else {
                $data['speciality'] = null;
                $data['license_number'] = null;
            }

            // Upload du nouvel avatar
            if ($this->avatar) {
                // Supprimer l'ancien avatar
                if ($user->avatar_url) {
                    Storage::disk('public')->delete($user->avatar_url);
                }
                $data['avatar_url'] = $this->avatar->store('avatars', 'public');
            }

            $user->update($data);

            // Mettre à jour le rôle Spatie
            $user->syncRoles([$this->role]);

            session()->flash('success', 'Utilisateur mis à jour avec succès !');
            $this->closeEditModal();
            $this->resetForm();

        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    public function deleteUser()
    {
        try {
            $user = User::findOrFail($this->userId);

            // Empêcher de supprimer son propre compte
            if ($user->id === auth()->id()) {
                session()->flash('error', 'Vous ne pouvez pas supprimer votre propre compte!');
                return;
            }

            $userName = $user->full_name;
            $user->delete();

            session()->flash('success', "L'utilisateur {$userName} a été supprimé avec succès !");
            $this->closeDeleteModal();

        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    public function toggleUserStatus($userId)
    {
        $user = User::findOrFail($userId);

        // Empêcher de désactiver son propre compte
        if ($user->id === auth()->id()) {
            session()->flash('error', 'Vous ne pouvez pas désactiver votre propre compte!');
            return;
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activé' : 'désactivé';
        session()->flash('success', "Utilisateur {$status} avec succès!");
    }

    public function resetPassword($userId)
    {
        $user = User::findOrFail($userId);

        // Réinitialiser à un mot de passe temporaire
        $tempPassword = 'Temp@' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $user->update(['password' => bcrypt($tempPassword)]);

        // TODO: Envoyer email avec mot de passe temporaire
        session()->flash('success', "Mot de passe réinitialisé! Mot de passe temporaire: {$tempPassword}");
    }

    /**
     * ============================================
     * HELPERS
     * ============================================
     */
    public function resetForm()
    {
        $this->userId = null;
        $this->first_name = '';
        $this->last_name = '';
        $this->email = '';
        $this->phone_number = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = 'patient';
        $this->speciality = '';
        $this->license_number = '';
        $this->is_active = true;
        $this->avatar = null;
        $this->current_avatar = '';
        $this->resetValidation();
    }

    public function getRoleBadgeColor($role)
    {
        return match($role) {
            'doctor' => 'bg-red-100 text-red-800',
            'nurse' => 'bg-blue-100 text-blue-800',
            'secretary' => 'bg-purple-100 text-purple-800',
            'patient' => 'bg-green-100 text-green-800',
            'partner' => 'bg-yellow-100 text-yellow-800',
            'home_care_member' => 'bg-indigo-100 text-indigo-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getRoleLabel($role)
    {
        return match($role) {
            'doctor' => 'Médecin Chef',
            'nurse' => 'Infirmier',
            'secretary' => 'Secrétaire',
            'patient' => 'Patient',
            'partner' => 'Partenaire',
            'home_care_member' => 'Équipe Terrain',
            default => $role,
        };
    }

    /**
     * ============================================
     * QUERY BUILDER
     * ============================================
     */
    private function getQuery()
    {
        $query = User::query();

        // Recherche
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('first_name', 'like', "%{$this->search}%")
                  ->orWhere('last_name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
                  ->orWhere('phone_number', 'like', "%{$this->search}%");
            });
        }

        // Filtre par rôle
        if ($this->filterRole) {
            $query->where('role', $this->filterRole);
        }

        // Tri
        $query->orderBy($this->sortBy, $this->sortOrder);

        return $query;
    }

    /**
     * ============================================
     * RENDER
     * ============================================
     */
    public function render()
    {
        $users = $this->getQuery()->paginate(15);
        $roles = ['doctor', 'nurse', 'secretary', 'patient', 'partner', 'home_care_member'];

        return view('livewire.demo1.users.users-list', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }
}
