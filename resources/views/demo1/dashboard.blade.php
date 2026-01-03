@extends('layouts.demo1.base')

@section('content')
<div class="kt-container-fixed">
    <div class="flex flex-wrap items-center lg:items-end justify-between gap-5 pb-7.5">
        <div class="flex flex-col justify-center gap-2">
            <h1 class="text-xl font-medium leading-none text-mono">
                Dashboard
            </h1>
            <div class="flex items-center flex-wrap gap-1.5 font-medium">
                <span class="text-base text-secondary-foreground">
                    Bienvenue {{ auth()->user()->first_name }} !
                </span>
            </div>
        </div>
    </div>

    {{-- Contenu du dashboard --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="kt-card">
            <div class="kt-card-content">
                <h3 class="text-lg font-semibold mb-2">Utilisateurs</h3>
                <p class="text-3xl font-bold text-primary">{{ \App\Models\User::count() }}</p>
            </div>
        </div>

        <div class="kt-card">
            <div class="kt-card-content">
                <h3 class="text-lg font-semibold mb-2">Actifs</h3>
                <p class="text-3xl font-bold text-success">{{ \App\Models\User::where('is_active', true)->count() }}</p>
            </div>
        </div>

        <div class="kt-card">
            <div class="kt-card-content">
                <h3 class="text-lg font-semibold mb-2">Inactifs</h3>
                <p class="text-3xl font-bold text-danger">{{ \App\Models\User::where('is_active', false)->count() }}</p>
            </div>
        </div>
    </div>

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
