@extends('layouts.demo1.base')

@section('content')
<div class="kt-container-fixed">
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 pb-7.5">
        <a href="{{ route('users.index') }}" class="text-sm text-secondary-foreground hover:text-primary">
            Utilisateurs
        </a>
        <i class="ki-filled ki-right text-xs text-secondary-foreground"></i>
        <span class="text-sm font-medium">Détails utilisateur</span>
    </div>

    {{-- Header avec actions --}}
    <div class="flex flex-wrap items-center justify-between gap-5 pb-7.5">
        <div class="flex flex-col justify-center gap-2">
            <h1 class="text-xl font-medium leading-none text-mono">
                Détails de l'utilisateur
            </h1>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('users.edit', $user->id) }}" class="kt-btn kt-btn-primary">
                <i class="ki-filled ki-pencil"></i>
                Modifier
            </a>
        </div>
    </div>

    {{-- Messages flash --}}
    @if(session('success'))
    <div class="px-6 py-3 bg-green-100 border border-green-400 text-green-800 rounded-lg mb-5">
        <div class="flex items-center justify-between">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="text-green-600 hover:text-green-800">
                <i class="ki-filled ki-cross"></i>
            </button>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Colonne gauche - Carte profil --}}
        <div class="lg:col-span-1">
            <div class="kt-card">
                <div class="kt-card-content text-center">
                    {{-- Avatar --}}
                    <div class="flex justify-center mb-5">
                        <div class="relative">
                            <img src="{{ $user->avatar_url }}"
                                 alt="{{ $user->full_name }}"
                                 class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg" />
                            {{-- Badge statut --}}
                            <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2">
                                <span class="kt-badge kt-badge-outline {{ $user->is_active ? 'kt-badge-success' : 'kt-badge-destructive' }} rounded-[30px]">
                                    <span class="kt-badge-dot size-1.5"></span>
                                    {{ $user->is_active ? 'Actif' : 'Inactif' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Nom complet --}}
                    <h2 class="text-xl font-bold text-mono mb-2">
                        {{ $user->full_name }}
                    </h2>

                    {{-- Badge rôle --}}
                    @php
                    $roleColors = [
                        'doctor' => 'bg-red-100 text-red-800',
                        'nurse' => 'bg-blue-100 text-blue-800',
                        'secretary' => 'bg-purple-100 text-purple-800',
                        'patient' => 'bg-green-100 text-green-800',
                        'partner' => 'bg-yellow-100 text-yellow-800',
                        'home_care_member' => 'bg-indigo-100 text-indigo-800'
                    ];
                    $roleColor = $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800';

                    $roleLabels = [
                        'doctor' => 'Médecin Chef',
                        'nurse' => 'Infirmier(ère)',
                        'secretary' => 'Secrétaire',
                        'patient' => 'Patient',
                        'partner' => 'Partenaire',
                        'home_care_member' => 'Équipe à domicile'
                    ];
                    $roleLabel = $roleLabels[$user->role] ?? $user->role;
                    @endphp

                    <span class="kt-badge {{ $roleColor }} rounded-[30px] mb-5 inline-block">
                        {{ $roleLabel }}
                    </span>

                    {{-- Spécialité si applicable --}}
                    @if($user->speciality)
                    <div class="text-sm text-secondary-foreground mb-5">
                        <i class="ki-filled ki-medal-star text-primary"></i>
                        {{ $user->speciality }}
                    </div>
                    @endif

                    {{-- Divider --}}
                    <div class="border-t border-gray-200 my-5"></div>

                    {{-- Actions rapides --}}
                    <div class="space-y-2">
                        <button type="button"
                                class="kt-btn kt-btn-light kt-btn-sm w-full justify-start btn-reset-password"
                                data-user-id="{{ $user->id }}"
                                data-user-name="{{ $user->full_name }}">
                            <i class="ki-filled ki-lock"></i>
                            Réinitialiser le mot de passe
                        </button>

                        <button type="button"
                                class="kt-btn kt-btn-light kt-btn-sm w-full justify-start btn-toggle-status"
                                data-user-id="{{ $user->id }}">
                            <i class="ki-filled ki-toggle-{{ $user->is_active ? 'off' : 'on' }}"></i>
                            {{ $user->is_active ? 'Désactiver' : 'Activer' }} le compte
                        </button>

                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="kt-btn kt-btn-light kt-btn-sm w-full justify-start text-danger hover:bg-red-50">
                                <i class="ki-filled ki-trash"></i>
                                Supprimer l'utilisateur
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Colonne droite - Détails --}}
        <div class="lg:col-span-2">
            {{-- Informations personnelles --}}
            <div class="kt-card mb-5">
                <div class="kt-card-header">
                    <h3 class="kt-card-title">
                        <i class="ki-filled ki-profile-circle text-primary"></i>
                        Informations personnelles
                    </h3>
                </div>
                <div class="kt-card-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        {{-- Prénom --}}
                        <div>
                            <label class="text-xs font-medium text-secondary-foreground uppercase tracking-wider mb-2 block">
                                Prénom
                            </label>
                            <p class="text-sm font-medium text-mono">{{ $user->first_name }}</p>
                        </div>

                        {{-- Nom --}}
                        <div>
                            <label class="text-xs font-medium text-secondary-foreground uppercase tracking-wider mb-2 block">
                                Nom
                            </label>
                            <p class="text-sm font-medium text-mono">{{ $user->last_name }}</p>
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="text-xs font-medium text-secondary-foreground uppercase tracking-wider mb-2 block">
                                Email
                            </label>
                            <a href="mailto:{{ $user->email }}" class="text-sm font-medium text-primary hover:underline">
                                {{ $user->email }}
                            </a>
                        </div>

                        {{-- Téléphone --}}
                        <div>
                            <label class="text-xs font-medium text-secondary-foreground uppercase tracking-wider mb-2 block">
                                Téléphone
                            </label>
                            <a href="tel:{{ $user->phone_number }}" class="text-sm font-medium text-primary hover:underline">
                                {{ $user->phone_number }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Informations professionnelles (si doctor ou nurse) --}}
            @if(in_array($user->role, ['doctor', 'nurse']))
            <div class="kt-card mb-5">
                <div class="kt-card-header">
                    <h3 class="kt-card-title">
                        <i class="ki-filled ki-badge text-primary"></i>
                        Informations professionnelles
                    </h3>
                </div>
                <div class="kt-card-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        {{-- Spécialité --}}
                        <div>
                            <label class="text-xs font-medium text-secondary-foreground uppercase tracking-wider mb-2 block">
                                Spécialité
                            </label>
                            <p class="text-sm font-medium text-mono">
                                {{ $user->speciality ?? '—' }}
                            </p>
                        </div>

                        {{-- Numéro de licence --}}
                        <div>
                            <label class="text-xs font-medium text-secondary-foreground uppercase tracking-wider mb-2 block">
                                Numéro de licence
                            </label>
                            <p class="text-sm font-medium text-mono">
                                {{ $user->license_number ?? '—' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Informations système --}}
            <div class="kt-card">
                <div class="kt-card-header">
                    <h3 class="kt-card-title">
                        <i class="ki-filled ki-information-5 text-primary"></i>
                        Informations système
                    </h3>
                </div>
                <div class="kt-card-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        {{-- ID --}}
                        <div>
                            <label class="text-xs font-medium text-secondary-foreground uppercase tracking-wider mb-2 block">
                                ID Utilisateur
                            </label>
                            <p class="text-sm font-medium text-mono">#{{ $user->id }}</p>
                        </div>

                        {{-- Rôle --}}
                        <div>
                            <label class="text-xs font-medium text-secondary-foreground uppercase tracking-wider mb-2 block">
                                Rôle
                            </label>
                            <span class="kt-badge {{ $roleColor }} rounded-[30px]">
                                {{ $roleLabel }}
                            </span>
                        </div>

                        {{-- Statut --}}
                        <div>
                            <label class="text-xs font-medium text-secondary-foreground uppercase tracking-wider mb-2 block">
                                Statut du compte
                            </label>
                            <span class="kt-badge kt-badge-outline {{ $user->is_active ? 'kt-badge-success' : 'kt-badge-destructive' }} rounded-[30px]">
                                <span class="kt-badge-dot size-1.5"></span>
                                {{ $user->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </div>

                        {{-- Date de création --}}
                        <div>
                            <label class="text-xs font-medium text-secondary-foreground uppercase tracking-wider mb-2 block">
                                Membre depuis
                            </label>
                            <p class="text-sm font-medium text-mono">
                                {{ $user->created_at->format('d/m/Y à H:i') }}
                            </p>
                        </div>

                        {{-- Dernière modification --}}
                        <div>
                            <label class="text-xs font-medium text-secondary-foreground uppercase tracking-wider mb-2 block">
                                Dernière modification
                            </label>
                            <p class="text-sm font-medium text-mono">
                                {{ $user->updated_at->format('d/m/Y à H:i') }}
                            </p>
                        </div>

                        {{-- Créé il y a --}}
                        <div>
                            <label class="text-xs font-medium text-secondary-foreground uppercase tracking-wider mb-2 block">
                                Ancienneté
                            </label>
                            <p class="text-sm font-medium text-mono">
                                {{ $user->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bouton retour en bas --}}
    <div class="flex justify-between items-center mt-5">
        <a href="{{ route('users.index') }}" class="kt-btn kt-btn-light">
            <i class="ki-filled ki-left"></i>
            Retour à la liste
        </a>
        <a href="{{ route('users.edit', $user->id) }}" class="kt-btn kt-btn-primary">
            <i class="ki-filled ki-pencil"></i>
            Modifier cet utilisateur
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/users.js') }}"></script>
@endpush
