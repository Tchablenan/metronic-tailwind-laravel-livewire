@extends('layouts.demo1.base')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-5 pb-8">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                Gestion des Utilisateurs
            </h1>
            <p class="text-sm text-gray-600 font-medium">
                Gérez les utilisateurs et leurs permissions
            </p>
        </div>
        <!-- BOUTON AJOUTER ICI DANS LE HEADER -->
        <a href="{{ route('users.create') }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 hover:shadow-md active:scale-95 transition-all">
            <i class="ki-filled ki-plus"></i>
            Ajouter Utilisateur
        </a>
    </div>

    <!-- Card principal -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">

        <!-- Messages flash -->
        @if (session('success'))
            <div class="m-4 px-4 py-3.5 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-800 rounded-lg shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="flex items-center gap-2.5 font-medium">
                        <i class="ki-filled ki-check-circle text-green-600"></i>
                        {{ session('success') }}
                    </span>
                    <button onclick="this.parentElement.parentElement.remove()"
                            class="text-green-600 hover:text-green-800 hover:bg-green-100 rounded-full p-1 transition-all">
                        <i class="ki-filled ki-cross"></i>
                    </button>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="m-4 px-4 py-3.5 bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 text-red-800 rounded-lg shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="flex items-center gap-2.5 font-medium">
                        <i class="ki-filled ki-cross-circle text-red-600"></i>
                        {{ session('error') }}
                    </span>
                    <button onclick="this.parentElement.parentElement.remove()"
                            class="text-red-600 hover:text-red-800 hover:bg-red-100 rounded-full p-1 transition-all">
                        <i class="ki-filled ki-cross"></i>
                    </button>
                </div>
            </div>
        @endif

        <!-- Barre de filtres - SANS LE BOUTON AJOUTER -->
        <div class="p-5 bg-gray-50/50 border-b border-gray-200">
            <form method="GET" action="{{ route('users.index') }}" class="flex flex-col lg:flex-row gap-3 w-full">

                <!-- Recherche -->
                <div class="w-80">
                    <div class="flex items-center gap-2 px-3 py-2 border border-gray-300 rounded-lg bg-white focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 focus-within:shadow-sm transition-all">
                        <i class="ki-filled ki-magnifier text-gray-400 text-sm flex-shrink-0"></i>
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Rechercher par nom, email, téléphone..."
                               class="flex-1 text-sm outline-none bg-transparent placeholder:text-gray-400" />
                    </div>
                </div>

                <!-- Filtre rôle -->
                <select name="role"
                        class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white hover:border-gray-400 transition-all cursor-pointer">
                    <option value="">Tous les rôles</option>
                    @foreach($roles as $value => $label)
                        <option value="{{ $value }}" {{ request('role') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                <button type="submit"
                        class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 hover:shadow-md active:scale-95 transition-all">
                    <i class="ki-filled ki-magnifier text-sm"></i>
                    Filtrer
                </button>



                @if(request('search') || request('role'))
                <a href="{{ route('users.index') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 hover:shadow-sm active:scale-95 transition-all">
                    <i class="ki-filled ki-setting-4 text-sm"></i>
                    Réinitialiser
                </a>
                @endif

            </form>
        </div>

        <!-- Tableau - SANS TRAITS NOIRS -->
<!-- Tableau - BORDURES ULTRA-FINES -->
<div class="overflow-x-auto">
    <table class="w-full">
        <thead>
            <tr class="border-b" style="border-color: #f0f0f0;">
                <th class="w-12 px-4 py-4 text-center font-medium text-gray-600 text-xs">
                    <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-blue-600 cursor-pointer" />
                </th>
                <th class="px-4 py-4 text-left font-medium text-gray-600 text-xs uppercase tracking-wide">
                    Utilisateur
                </th>
                <th class="px-4 py-4 text-left font-medium text-gray-600 text-xs uppercase tracking-wide">
                    Email
                </th>
                <th class="px-4 py-4 text-left font-medium text-gray-600 text-xs uppercase tracking-wide">
                    Téléphone
                </th>
                <th class="px-4 py-4 text-left font-medium text-gray-600 text-xs uppercase tracking-wide">
                    Rôle
                </th>
                <th class="px-4 py-4 text-left font-medium text-gray-600 text-xs uppercase tracking-wide">
                    Statut
                </th>
                <th class="w-20 px-4 py-4 text-center font-medium text-gray-600 text-xs uppercase tracking-wide">
                    Actions
                </th>
            </tr>
        </thead>
        <tbody class="bg-white">
            @forelse($users as $user)
            <tr class="border-b hover:bg-gray-50/50 transition-colors" style="border-color: #f5f5f5;">
                <td class="px-4 py-4 text-center">
                    <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-blue-600 cursor-pointer" />
                </td>
                <td class="px-4 py-4">
                    <div class="flex items-center gap-3">
                        <img alt="{{ $user->full_name }}"
                             src="{{ $user->avatar_url }}"
                             class="w-9 h-9 rounded-full object-cover" />
                        <div class="flex flex-col">
                            <a href="{{ route('users.show', $user->id) }}"
                               class="text-sm font-medium text-gray-900 hover:text-blue-600 transition-colors">
                                {{ $user->full_name }}
                            </a>
                            @if($user->speciality)
                            <span class="text-xs text-gray-500">{{ $user->speciality }}</span>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-4 py-4">
                    <a href="mailto:{{ $user->email }}" class="text-sm text-gray-700 hover:text-blue-600 transition-colors">
                        {{ $user->email }}
                    </a>
                </td>
                <td class="px-4 py-4">
                    @if($user->phone_number)
                    <a href="tel:{{ $user->phone_number }}" class="text-sm text-gray-700 hover:text-blue-600 transition-colors">
                        {{ $user->phone_number }}
                    </a>
                    @else
                    <span class="text-sm text-gray-400">—</span>
                    @endif
                </td>
                <td class="px-4 py-4">
                    @if($user->role === 'doctor')
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium"
                              style="background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca;">
                            {{ $roles[$user->role] }}
                        </span>
                    @elseif($user->role === 'nurse')
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium"
                              style="background-color: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe;">
                            {{ $roles[$user->role] }}
                        </span>
                    @elseif($user->role === 'secretary')
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium"
                              style="background-color: #e9d5ff; color: #6b21a8; border: 1px solid #d8b4fe;">
                            {{ $roles[$user->role] }}
                        </span>
                    @elseif($user->role === 'patient')
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium"
                              style="background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0;">
                            {{ $roles[$user->role] }}
                        </span>
                    @elseif($user->role === 'partner')
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium"
                              style="background-color: #fed7aa; color: #9a3412; border: 1px solid #fdba74;">
                            {{ $roles[$user->role] }}
                        </span>
                    @elseif($user->role === 'home_care_member')
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium"
                              style="background-color: #fce7f3; color: #9f1239; border: 1px solid #fbcfe8;">
                            {{ $roles[$user->role] }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium"
                              style="background-color: #f3f4f6; color: #374151; border: 1px solid #e5e7eb;">
                            {{ $roles[$user->role] }}
                        </span>
                    @endif
                </td>
                <td class="px-4 py-4">
                    <button type="button"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium transition-all btn-toggle-status"
                            style="{{ $user->is_active ? 'background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0;' : 'background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca;' }}"
                            data-user-id="{{ $user->id }}">
                        <span class="w-1.5 h-1.5 rounded-full" style="{{ $user->is_active ? 'background-color: #059669;' : 'background-color: #dc2626;' }}"></span>
                        {{ $user->is_active ? 'Actif' : 'Inactif' }}
                    </button>
                </td>
                <td class="px-4 py-4 text-center">
                    <div class="relative inline-block" x-data="{ open: false }">
                        <button @click="open = !open"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="ki-filled ki-dots-vertical text-lg text-gray-500"></i>
                        </button>

                        <div x-show="open"
                             @click.away="open = false"
                             x-cloak
                             class="absolute right-0 top-full mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                            <a href="{{ route('users.show', $user->id) }}"
                               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="ki-filled ki-eye text-gray-400 text-base"></i>
                                Voir
                            </a>
                            <a href="{{ route('users.edit', $user->id) }}"
                               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="ki-filled ki-pencil text-gray-400 text-base"></i>
                                Éditer
                            </a>
                            <button type="button"
                                    class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 w-full text-left transition-colors btn-reset-password"
                                    data-user-id="{{ $user->id }}"
                                    data-user-name="{{ $user->full_name }}">
                                <i class="ki-filled ki-lock text-gray-400 text-base"></i>
                                Réinitialiser MDP
                            </button>
                            <div style="height: 1px; background-color: #f0f0f0; margin: 4px 0;"></div>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 w-full text-left transition-colors btn-delete-user"
                                        data-user-name="{{ $user->full_name }}">
                                    <i class="ki-filled ki-trash text-red-500 text-base"></i>
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-16 text-center">
                    <div class="flex flex-col items-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                            <i class="ki-filled ki-user-multiple text-3xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-700 font-medium">Aucun utilisateur trouvé</p>
                        @if(request('search') || request('role'))
                        <p class="text-gray-500 text-sm mt-1">Essayez de modifier vos filtres</p>
                        @endif
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
        <!-- Pagination -->
        @if($users->hasPages())
        <div class="px-5 py-4 bg-gray-50/50 border-t border-gray-200 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-sm text-gray-600 font-medium">
                Affichage de <span class="font-bold text-gray-900">{{ $users->firstItem() }}</span> à <span class="font-bold text-gray-900">{{ $users->lastItem() }}</span> sur <span class="font-bold text-gray-900">{{ $users->total() }}</span> utilisateurs
            </div>
            <div>
                {{ $users->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/users.js') }}"></script>
@endpush
