@extends('layouts.demo1.base')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center gap-4 pb-8">
        <a href="{{ route('secretary.service-requests.index') }}"
           class="inline-flex items-center justify-center w-10 h-10 rounded-lg hover:bg-gray-100 transition-colors">
            <i class="ki-filled ki-left text-xl text-gray-600"></i>
        </a>
        <div class="flex flex-col gap-1">
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                {{ $serviceRequest->full_name }}
            </h1>
            <p class="text-sm text-gray-600">
                Demande de {{ ucfirst(str_replace('_', ' ', $serviceRequest->service_type)) }}
            </p>
        </div>
    </div>

    <!-- Messages flash -->
    @if (session('success'))
        <div class="mb-6 px-4 py-3.5 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-800 rounded-lg shadow-sm">
            <span class="flex items-center gap-2.5 font-medium">
                <i class="ki-filled ki-check-circle text-green-600"></i>
                {{ session('success') }}
            </span>
        </div>
    @endif

    <!-- Contenu principal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne principale -->
        <div class="lg:col-span-2">
            <!-- Informations du patient -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Informations du Patient</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Prénom</label>
                        <p class="mt-1 text-gray-900 font-semibold">{{ $serviceRequest->first_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Nom</label>
                        <p class="mt-1 text-gray-900 font-semibold">{{ $serviceRequest->last_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Email</label>
                        <p class="mt-1 text-gray-900"><a href="mailto:{{ $serviceRequest->email }}" class="text-blue-600 hover:underline">{{ $serviceRequest->email }}</a></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Téléphone</label>
                        <p class="mt-1 text-gray-900"><a href="tel:{{ $serviceRequest->phone_number }}" class="text-blue-600 hover:underline">{{ $serviceRequest->phone_number }}</a></p>
                    </div>
                </div>
            </div>

            <!-- Détails de la demande -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Détails de la Demande</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Type de service</label>
                        <span class="mt-1 inline-block px-3 py-1.5 bg-blue-100 text-blue-800 rounded-lg text-sm font-medium">
                            {{ ucfirst(str_replace('_', ' ', $serviceRequest->service_type)) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Urgence</label>
                        <span class="mt-1 inline-block px-3 py-1.5 rounded-lg text-sm font-medium
                            {{ $serviceRequest->urgency === 'high' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $serviceRequest->urgency === 'medium' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $serviceRequest->urgency === 'low' ? 'bg-green-100 text-green-800' : '' }}">
                            {{ ucfirst($serviceRequest->urgency) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Date préférée</label>
                        <p class="mt-1 text-gray-900">{{ $serviceRequest->preferred_date?->format('d/m/Y') ?? 'Non spécifiée' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Heure préférée</label>
                        <p class="mt-1 text-gray-900">{{ $serviceRequest->preferred_time ?? 'Non spécifiée' }}</p>
                    </div>
                </div>

                @if($serviceRequest->message)
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-600">Message</label>
                    <div class="mt-1 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-gray-900 whitespace-pre-wrap">{{ $serviceRequest->message }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Colonne latérale -->
        <div>
            <!-- Informations de paiement -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">💰 Paiement</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Montant</label>
                        <p class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($serviceRequest->payment_amount, 0) }} FCFA</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Méthode</label>
                        <p class="mt-1 text-gray-900">{{ $serviceRequest->payment_method_label }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Statut</label>
                        <span class="mt-1 inline-block px-3 py-1.5 rounded-lg text-sm font-medium
                            {{ $serviceRequest->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $serviceRequest->payment_status_label }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Statut -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">📋 Statut</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Statut</label>
                        <span class="mt-1 inline-block px-3 py-1.5 rounded-lg text-sm font-medium bg-blue-100 text-blue-800">
                            {{ ucfirst($serviceRequest->status) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Créée le</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $serviceRequest->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            @if($serviceRequest->payment_status === 'paid' && $serviceRequest->status === 'pending')
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">⚡ Actions</h3>
                <form method="POST" action="{{ route('secretary.service-requests.send-to-doctor', $serviceRequest) }}">
                    @csrf
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 hover:shadow-md active:scale-95 transition-all">
                        <i class="ki-filled ki-send text-sm"></i>
                        Envoyer au médecin chef
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
