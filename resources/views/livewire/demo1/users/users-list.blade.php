<div class="kt-card kt-card-grid min-w-full">
    {{-- Messages flash --}}
    @if (session('success'))
        <div class="px-6 py-3 bg-green-100 border border-green-400 text-green-800 rounded-lg m-4">
            <div class="flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.parentElement.style.display='none'"
                    class="text-green-600 hover:text-green-800">
                    <i class="ki-filled ki-cross"></i>
                </button>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="px-6 py-3 bg-red-100 border border-red-400 text-red-800 rounded-lg m-4">
            <div class="flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.parentElement.style.display='none'"
                    class="text-red-600 hover:text-red-800">
                    <i class="ki-filled ki-cross"></i>
                </button>
            </div>
        </div>
    @endif

    {{-- Barre de filtres et recherche --}}
    <div class="kt-card-header">
        <div class="flex flex-col lg:flex-row gap-5 w-full">
            {{-- Recherche --}}
            <div class="flex">
                <label class="kt-input">
                    <i class="ki-filled ki-magnifier"></i>
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Rechercher par nom, email, téléphone..." />
                </label>
            </div>

            {{-- Filtre rôle --}}
            <div class="flex flex-wrap gap-2.5">
                <select wire:model.live="filterRole" class="kt-select w-36">
                    <option value="">Tous les rôles</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role }}">{{ $this->getRoleLabel($role) }}</option>
                    @endforeach
                </select>

                {{-- Bouton réinitialiser --}}
                @if ($search || $filterRole)
                    <button wire:click="resetSearch" class="kt-btn kt-btn-outline kt-btn-sm">
                        <i class="ki-filled ki-setting-4"></i>
                        Réinitialiser
                    </button>
                @endif
            </div>

            <div class="flex items-center gap-2.5 ms-auto">
                <button wire:click="openCreateModal" class="kt-btn kt-btn-primary">
                    <i class="ki-filled ki-plus"></i>
                    Ajouter Utilisateur
                </button>
            </div>
        </div>
    </div>

    {{-- Tableau --}}
    <div class="kt-card-content">
        <div data-kt-datatable="true" >
            <div class="kt-scrollable-x-auto">
                <table class="kt-table table-auto kt-table-border">
                    <thead>
                        <tr>
                            <th class="w-[60px] text-center">
                                <input class="kt-checkbox kt-checkbox-sm" type="checkbox" />
                            </th>
                            <th class="min-w-[300px]">
                                <span class="kt-table-col">
                                    <button wire:click="toggleSort('first_name')" class="kt-table-col-label hover:text-primary">
                                        Utilisateur
                                        @if($sortBy === 'first_name')
                                            <i class="ki-filled ki-arrow-{{ $sortOrder === 'asc' ? 'up' : 'down' }} text-xs"></i>
                                        @endif
                                    </button>
                                </span>
                            </th>
                            <th class="min-w-[180px]">
                                <span class="kt-table-col-label">Email</span>
                            </th>
                            <th class="min-w-[150px]">
                                <span class="kt-table-col-label">Téléphone</span>
                            </th>
                            <th class="min-w-[150px]">
                                <span class="kt-table-col">
                                    <button wire:click="toggleSort('role')" class="kt-table-col-label hover:text-primary">
                                        Rôle
                                        @if($sortBy === 'role')
                                            <i class="ki-filled ki-arrow-{{ $sortOrder === 'asc' ? 'up' : 'down' }} text-xs"></i>
                                        @endif
                                    </button>
                                </span>
                            </th>
                            <th class="min-w-[120px]">
                                <span class="kt-table-col-label">Statut</span>
                            </th>
                            <th class="w-[120px] text-end">
                                <span class="kt-table-col-label">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td class="text-center">
                                    <input class="kt-checkbox kt-checkbox-sm" type="checkbox" />
                                </td>

                                {{-- Utilisateur --}}
                                <td>
                                    <div class="flex items-center gap-2.5">
                                        <img alt="{{ $user->full_name }}" src="{{ $user->profile_photo_url }}"
                                            class="rounded-full size-9 shrink-0 object-cover" />
                                        <div class="flex flex-col">
                                            <a href="#" wire:click.prevent="openDetailModal({{ $user->id }})"
                                                class="text-sm font-medium text-mono hover:text-primary mb-px">
                                                {{ $user->full_name }}
                                            </a>
                                            @if ($user->speciality)
                                                <span class="text-xs text-secondary-foreground">
                                                    {{ $user->speciality }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Email --}}
                                <td>
                                    <a href="mailto:{{ $user->email }}"
                                        class="text-sm text-secondary-foreground font-normal hover:text-primary">
                                        {{ $user->email }}
                                    </a>
                                </td>

                                {{-- Téléphone --}}
                                <td>
                                    @if ($user->phone_number)
                                        <a href="tel:{{ $user->phone_number }}"
                                            class="text-sm text-secondary-foreground font-normal hover:text-primary">
                                            {{ $user->phone_number }}
                                        </a>
                                    @else
                                        <span class="text-secondary-foreground">—</span>
                                    @endif
                                </td>

                                {{-- Rôle --}}
                                <td>
                                    <span class="kt-badge {{ $this->getRoleBadgeColor($user->role) }} rounded-[30px]">
                                        {{ $this->getRoleLabel($user->role) }}
                                    </span>
                                </td>

                                {{-- Statut --}}
                                <td>
                                    <button wire:click="toggleUserStatus({{ $user->id }})" @class([
                                        'kt-badge kt-badge-outline rounded-[30px]',
                                        'kt-badge-success' => $user->is_active,
                                        'kt-badge-destructive' => !$user->is_active,
                                    ])>
                                        <span class="kt-badge-dot size-1.5"></span>
                                        {{ $user->is_active ? 'Actif' : 'Inactif' }}
                                    </button>
                                </td>

                                {{-- Actions --}}
                                <td class="text-center">
                                    <div class="kt-menu flex-inline" data-kt-menu="true">
                                        <div class="kt-menu-item" data-kt-menu-item-offset="0, 10px"
                                            data-kt-menu-item-placement="bottom-end" data-kt-menu-item-toggle="dropdown"
                                            data-kt-menu-item-trigger="click">
                                            <button class="kt-menu-toggle kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost">
                                                <i class="ki-filled ki-dots-vertical text-lg"></i>
                                            </button>
                                            <div class="kt-menu-dropdown kt-menu-default w-full max-w-[175px]">
                                                <div class="kt-menu-item">
                                                    <a href="#" wire:click.prevent="openDetailModal({{ $user->id }})"
                                                        class="kt-menu-link">
                                                        <span class="kt-menu-icon">
                                                            <i class="ki-filled ki-search-list"></i>
                                                        </span>
                                                        <span class="kt-menu-title">Voir</span>
                                                    </a>
                                                </div>
                                                <div class="kt-menu-item">
                                                    <a href="#" wire:click.prevent="openEditModal({{ $user->id }})"
                                                        class="kt-menu-link">
                                                        <span class="kt-menu-icon">
                                                            <i class="ki-filled ki-pencil"></i>
                                                        </span>
                                                        <span class="kt-menu-title">Éditer</span>
                                                    </a>
                                                </div>
                                                <div class="kt-menu-item">
                                                    <button wire:click="resetPassword({{ $user->id }})"
                                                        class="kt-menu-link">
                                                        <span class="kt-menu-icon">
                                                            <i class="ki-filled ki-lock"></i>
                                                        </span>
                                                        <span class="kt-menu-title">Réinitialiser MDP</span>
                                                    </button>
                                                </div>
                                                <div class="kt-menu-separator"></div>
                                                <div class="kt-menu-item">
                                                    <button wire:click="openDeleteModal({{ $user->id }})"
                                                        class="kt-menu-link text-danger">
                                                        <span class="kt-menu-icon">
                                                            <i class="ki-filled ki-trash"></i>
                                                        </span>
                                                        <span class="kt-menu-title">Supprimer</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="ki-filled ki-user-multiple text-4xl text-secondary-foreground mb-3"></i>
                                        <p class="text-secondary-foreground font-medium">Aucun utilisateur trouvé</p>
                                        <p class="text-secondary-foreground text-sm">Essayez de modifier vos filtres</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if ($users->hasPages())
            <div class="kt-card-footer justify-center md:justify-between flex-col md:flex-row gap-5">
                <div class="flex items-center gap-2 order-2 md:order-1">
                    <span class="text-sm text-secondary-foreground">
                        Affichage de {{ $users->count() }} sur {{ $users->total() }} utilisateurs
                    </span>
                </div>
                <div class="kt-datatable-pagination order-1 md:order-2">
                    {{ $users->links() }}
                </div>
            </div>
        @endif
    </div>

    {{-- ========================================== --}}
    {{-- MODAL CRÉATION --}}
    {{-- ========================================== --}}
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(0,0,0,0.5);">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="kt-card w-full max-w-2xl">
                    <div class="kt-card-header">
                        <h3 class="kt-card-title">Créer un nouvel utilisateur</h3>
                        <button wire:click="closeCreateModal" class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost">
                            <i class="ki-filled ki-cross text-lg"></i>
                        </button>
                    </div>

                    <form wire:submit.prevent="createUser">
                        <div class="kt-card-content">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                {{-- Photo de profil --}}
                                <div class="col-span-2 text-center">
                                    <label class="form-label font-medium mb-2 block">Photo de profil</label>
                                    <div class="flex justify-center">
                                        @if($avatar)
                                            <div class="relative">
                                                <img src="{{ $avatar->temporaryUrl() }}" class="w-24 h-24 rounded-full object-cover" />
                                                <button type="button" wire:click="$set('avatar', null)"
                                                    class="absolute -top-2 -right-2 kt-btn kt-btn-sm kt-btn-icon kt-btn-danger">
                                                    <i class="ki-filled ki-cross"></i>
                                                </button>
                                            </div>
                                        @else
                                            <label class="cursor-pointer">
                                                <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center">
                                                    <i class="ki-filled ki-picture text-3xl text-gray-400"></i>
                                                </div>
                                                <input type="file" wire:model="avatar" accept="image/*" class="hidden" />
                                            </label>
                                        @endif
                                    </div>
                                    @error('avatar') <span class="text-danger text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- Prénom --}}
                                <div>
                                    <label class="form-label">Prénom <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="first_name" class="kt-input @error('first_name') border-danger @enderror" placeholder="Ex: Jean">
                                    @error('first_name') <span class="text-danger text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- Nom --}}
                                <div>
                                    <label class="form-label">Nom <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="last_name" class="kt-input @error('last_name') border-danger @enderror" placeholder="Ex: Dupont">
                                    @error('last_name') <span class="text-danger text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" wire:model="email" class="kt-input @error('email') border-danger @enderror" placeholder="jean.dupont@example.com">
                                    @error('email') <span class="text-danger text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- Téléphone --}}
                                <div>
                                    <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="phone_number" class="kt-input @error('phone_number') border-danger @enderror" placeholder="+225 01 02 03 04 05">
                                    @error('phone_number') <span class="text-danger text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- Rôle --}}
                                <div>
                                    <label class="form-label">Rôle <span class="text-danger">*</span></label>
                                    <select wire:model.live="role" class="kt-select @error('role') border-danger @enderror">
                                        <option value="patient">Patient</option>
                                        <option value="doctor">Médecin Chef</option>
                                        <option value="nurse">Infirmier</option>
                                        <option value="secretary">Secrétaire</option>
                                        <option value="partner">Partenaire</option>
                                        <option value="home_care_member">Équipe Terrain</option>
                                    </select>
                                    @error('role') <span class="text-danger text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- Statut --}}
                                <div>
                                    <label class="form-label">Statut</label>
                                    <label class="kt-switch flex items-center">
                                        <input type="checkbox" wire:model="is_active" />
                                        <span class="kt-switch-slider"></span>
                                        <span class="kt-switch-label">Actif</span>
                                    </label>
                                </div>

                                {{-- Spécialité (conditionnelle) --}}
                                @if(in_array($role, ['doctor', 'nurse']))
                                    <div>
                                        <label class="form-label">Spécialité <span class="text-danger">*</span></label>
                                        <input type="text" wire:model="speciality" class="kt-input @error('speciality') border-danger @enderror" placeholder="Ex: Cardiologie">
                                        @error('speciality') <span class="text-danger text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="form-label">Numéro de licence <span class="text-danger">*</span></label>
                                        <input type="text" wire:model="license_number" class="kt-input @error('license_number') border-danger @enderror" placeholder="Ex: MED-2024-001">
                                        @error('license_number') <span class="text-danger text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                @endif

                                {{-- Mot de passe --}}
                                <div>
                                    <label class="form-label">Mot de passe <span class="text-danger">*</span></label>
                                    <input type="password" wire:model="password" class="kt-input @error('password') border-danger @enderror" placeholder="Min. 8 caractères">
                                    @error('password') <span class="text-danger text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- Confirmation mot de passe --}}
                                <div>
                                    <label class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
                                    <input type="password" wire:model="password_confirmation" class="kt-input @error('password_confirmation') border-danger @enderror" placeholder="Répétez le mot de passe">
                                    @error('password_confirmation') <span class="text-danger text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="kt-card-footer justify-end">
                            <button type="button" wire:click="closeCreateModal" class="kt-btn kt-btn-light">Annuler</button>
                            <button type="submit" class="kt-btn kt-btn-primary">
                                <span wire:loading.remove wire:target="createUser">Créer l'utilisateur</span>
                                <span wire:loading wire:target="createUser" class="flex items-center gap-2">
                                    <span class="spinner-border spinner-border-sm"></span>
                                    Création en cours...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- ========================================== --}}
    {{-- MODAL ÉDITION --}}
    {{-- ========================================== --}}
    @if($showEditModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(0,0,0,0.5);">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="kt-card w-full max-w-2xl">
                    <div class="kt-card-header">
                        <h3 class="kt-card-title">Modifier l'utilisateur</h3>
                        <button wire:click="closeEditModal" class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost">
                            <i class="ki-filled ki-cross text-lg"></i>
                        </button>
                    </div>

                    <form wire:submit.prevent="updateUser">
                        <div class="kt-card-content">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                {{-- Photo de profil --}}
                                <div class="col-span-2 text-center">
                                    <label class="form-label font-medium mb-2 block">Photo de profil</label>
                                    <div class="flex justify-center">
                                        @if($avatar)
                                            <div class="relative">
                                                <img src="{{ $avatar->temporaryUrl() }}" class="w-24 h-24 rounded-full object-cover" />
                                                <button type="button" wire:click="$set('avatar', null)"
                                                    class="absolute -top-2 -right-2 kt-btn kt-btn-sm kt-btn-icon kt-btn-danger">
                                                    <i class="ki-filled ki-cross"></i>
                                                </button>
                                            </div>
                                        @elseif($current_avatar)
                                            <div class="relative">
                                                <img src="{{ asset('storage/' . $current_avatar) }}" class="w-24 h-24 rounded-full object-cover" />
                                                <label class="absolute -bottom-2 -right-2 kt-btn kt-btn-sm kt-btn-icon kt-btn-primary cursor-pointer">
                                                    <i class="ki-filled ki-pencil"></i>
                                                    <input type="file" wire:model="avatar" accept="image/*" class="hidden" />
                                                </label>
                                            </div>
                                        @else
                                            <label class="cursor-pointer">
                                                <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center">
                                                    <i class="ki-filled ki-picture text-3xl text-gray-400"></i>
                                                </div>
                                                <input type="file" wire:model="avatar" accept="image/*" class="hidden" />
                                            </label>
                                        @endif
                                    </div>
                                    @error('avatar') <span class="text-danger text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- Prénom --}}
                                <div>
                                    <label class="form-label">Prénom <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="first_name" class="kt-input @error('first_name') border-danger @enderror">
                                    @error('first_name') <span class="text-danger text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- Nom --}}
                                <div>
                                    <label class="form-label">Nom <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="last_name" class="kt-input @error('last_name') border-danger @enderror">
                                    @error('last_name') <span class="text-danger text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" wire:model="email" class="kt-input @error('email') border-danger @enderror">
                                    @error('email') <span class="text-danger text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- Téléphone --}}
                                <div>
                                    <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="phone_number" class="kt-input @error('phone_number') border-danger @enderror">
                                    @error('phone_number') <span class="text-danger text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- Rôle --}}
                                <div>
                                    <label class="form-label">Rôle <span class="text-danger">*</span></label>
                                    <select wire:model.live="role" class="kt-select @error('role') border-danger @enderror">
                                        <option value="patient">Patient</option>
                                        <option value="doctor">Médecin Chef</option>
                                        <option value="nurse">Infirmier</option>
                                        <option value="secretary">Secrétaire</option>
                                        <option value="partner">Partenaire</option>
                                        <option value="home_care_member">Équipe Terrain</option>
                                    </select>
                                    @error('role') <span class="text-danger text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- Statut --}}
                                <div>
                                    <label class="form-label">Statut</label>
                                    <label class="kt-switch flex items-center">
                                        <input type="checkbox" wire:model="is_active" />
                                        <span class="kt-switch-slider"></span>
                                        <span class="kt-switch-label">Actif</span>
                                    </label>
                                </div>

                                {{-- Spécialité (conditionnelle) --}}
                                @if(in_array($role, ['doctor', 'nurse']))
                                    <div>
                                        <label class="form-label">Spécialité <span class="text-danger">*</span></label>
                                        <input type="text" wire:model="speciality" class="kt-input @error('speciality') border-danger @enderror">
                                        @error('speciality') <span class="text-danger text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="form-label">Numéro de licence <span class="text-danger">*</span></label>
                                        <input type="text" wire:model="license_number" class="kt-input @error('license_number') border-danger @enderror">
                                        @error('license_number') <span class="text-danger text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="kt-card-footer justify-end">
                            <button type="button" wire:click="closeEditModal" class="kt-btn kt-btn-light">Annuler</button>
                            <button type="submit" class="kt-btn kt-btn-primary">
                                <span wire:loading.remove wire:target="updateUser">Enregistrer</span>
                                <span wire:loading wire:target="updateUser" class="flex items-center gap-2">
                                    <span class="spinner-border spinner-border-sm"></span>
                                    Mise à jour...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- ========================================== --}}
    {{-- MODAL DÉTAILS --}}
    {{-- ========================================== --}}
    @if($showDetailModal && $selectedUser)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(0,0,0,0.5);">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="kt-card w-full max-w-2xl">
                    <div class="kt-card-header">
                        <h3 class="kt-card-title">Détails de l'utilisateur</h3>
                        <button wire:click="closeDetailModal" class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost">
                            <i class="ki-filled ki-cross text-lg"></i>
                        </button>
                    </div>

                    <div class="kt-card-content">
                        {{-- Avatar et nom --}}
                        <div class="flex items-center gap-5 mb-8">
                            <img src="{{ $selectedUser->profile_photo_url }}" alt="{{ $selectedUser->full_name }}"
                                class="w-20 h-20 rounded-full object-cover" />
                            <div>
                                <h2 class="text-2xl font-bold mb-1">{{ $selectedUser->full_name }}</h2>
                                <span class="kt-badge {{ $this->getRoleBadgeColor($selectedUser->role) }} rounded-[30px]">
                                    {{ $this->getRoleLabel($selectedUser->role) }}
                                </span>
                            </div>
                        </div>

                        {{-- Informations --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="form-label text-xs">Email</label>
                                <p class="font-medium">{{ $selectedUser->email }}</p>
                            </div>
                            <div>
                                <label class="form-label text-xs">Téléphone</label>
                                <p class="font-medium">{{ $selectedUser->phone_number }}</p>
                            </div>
                            @if($selectedUser->speciality)
                                <div>
                                    <label class="form-label text-xs">Spécialité</label>
                                    <p class="font-medium">{{ $selectedUser->speciality }}</p>
                                </div>
                            @endif
                            @if($selectedUser->license_number)
                                <div>
                                    <label class="form-label text-xs">Numéro de licence</label>
                                    <p class="font-medium">{{ $selectedUser->license_number }}</p>
                                </div>
                            @endif
                            <div>
                                <label class="form-label text-xs">Statut</label>
                                <p>
                                    <span @class([
                                        'kt-badge kt-badge-outline rounded-[30px]',
                                        'kt-badge-success' => $selectedUser->is_active,
                                        'kt-badge-destructive' => !$selectedUser->is_active,
                                    ])>
                                        {{ $selectedUser->is_active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <label class="form-label text-xs">Date de création</label>
                                <p class="font-medium">{{ $selectedUser->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="kt-card-footer justify-end">
                        <button wire:click="closeDetailModal" class="kt-btn kt-btn-light">Fermer</button>
                        <button wire:click="openEditModal({{ $selectedUser->id }})" class="kt-btn kt-btn-primary">
                            <i class="ki-filled ki-pencil"></i>
                            Modifier
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ========================================== --}}
    {{-- MODAL SUPPRESSION --}}
    {{-- ========================================== --}}
    @if($showDeleteModal && $selectedUser)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(0,0,0,0.5);">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="kt-card w-full max-w-md">
                    <div class="kt-card-header">
                        <h3 class="kt-card-title text-danger">Confirmer la suppression</h3>
                        <button wire:click="closeDeleteModal" class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost">
                            <i class="ki-filled ki-cross text-lg"></i>
                        </button>
                    </div>

                    <div class="kt-card-content text-center">
                        <i class="ki-filled ki-information-5 text-5xl text-danger mb-4"></i>
                        <p class="text-lg mb-2">
                            Êtes-vous sûr de vouloir supprimer<br>
                            <strong>{{ $selectedUser->full_name }}</strong> ?
                        </p>
                        <p class="text-sm text-secondary-foreground">
                            Cette action peut être annulée en restaurant l'utilisateur depuis la corbeille.
                        </p>
                    </div>

                    <div class="kt-card-footer justify-end">
                        <button wire:click="closeDeleteModal" class="kt-btn kt-btn-light">Annuler</button>
                        <button wire:click="deleteUser" class="kt-btn kt-btn-danger">
                            <span wire:loading.remove wire:target="deleteUser">Supprimer</span>
                            <span wire:loading wire:target="deleteUser" class="flex items-center gap-2">
                                <span class="spinner-border spinner-border-sm"></span>
                                Suppression...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fonction pour initialiser les menus
    function initMenus() {
        if (typeof KTMenu !== 'undefined') {
            KTMenu.createInstances('[data-kt-menu="true"]');
        }
    }

    // Au chargement initial
    initMenus();

    // Après chaque update Livewire
    document.addEventListener('livewire:navigated', initMenus);

    Livewire.hook('morph.updated', ({ el, component }) => {
        setTimeout(initMenus, 50);
    });
});
</script>
@endpush
