@extends('layouts.demo1.base')

@section('content')
<div class="kt-container-fixed">
    <div class="flex flex-wrap items-center lg:items-end justify-between gap-5 pb-7.5">
        <div class="flex flex-col justify-center gap-2">
            <h1 class="text-xl font-medium leading-none">Dashboard</h1>
            <p class="text-base text-secondary-foreground">
                Bienvenue {{ auth()->user()->first_name }}!
            </p>
        </div>
    </div>

    <!-- Stats - À implémenter avec Livewire Component -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold mb-2">Utilisateurs</h3>
            <p class="text-3xl font-bold text-primary">-</p>
            <p class="text-xs text-secondary-foreground mt-2">À charger via Livewire</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold mb-2">Actifs</h3>
            <p class="text-3xl font-bold text-success">-</p>
            <p class="text-xs text-secondary-foreground mt-2">À charger via Livewire</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold mb-2">Inactifs</h3>
            <p class="text-3xl font-bold text-danger">-</p>
            <p class="text-xs text-secondary-foreground mt-2">À charger via Livewire</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 mt-5">
        <h3 class="text-lg font-semibold mb-4">Actions rapides</h3>
        <div class="flex gap-3">
            <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                <i class="ki-filled ki-people"></i>
                Gérer les utilisateurs
            </a>
        </div>
    </div>
</div>
@endsection

    {{-- Lien rapide --}}
    <div class="kt-card mt-5">
        <div class="kt-card-content">
            <h3 class="text-lg font-semibold mb-4">Actions rapides</h3>
            <div class="flex gap-3">
                <a href="{{ route('users.index') }}" class="kt-btn kt-btn-primary">
                    <i class="ki-filled ki-user"></i>
                    Gérer les utilisateurs
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
