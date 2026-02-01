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
                Nouvelle Demande de Service
            </h1>
            <p class="text-sm text-gray-600">
                Créer une demande pour un patient qui vient au cabinet
            </p>
        </div>
    </div>

    <!-- Info box -->
    <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4">
        <div class="flex items-start gap-3">
            <i class="ki-filled ki-information text-blue-600 text-xl flex-shrink-0"></i>
            <div class="text-sm text-blue-900">
                <p class="font-semibold mb-1">Comment ça fonctionne ?</p>
                <ol class="list-decimal list-inside space-y-1 text-xs">
                    <li>Remplissez les informations du patient</li>
                    <li>La demande sera automatiquement marquée comme "payée"</li>
                    <li>Le médecin chef sera notifié immédiatement</li>
                    <li>Le chef convertira la demande en rendez-vous</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Erreurs -->
    @if ($errors->any())
    <div class="mb-6 px-4 py-3.5 bg-red-50 border border-red-200 text-red-800 rounded-lg">
        <div class="flex items-center gap-2 font-semibold mb-2">
            <i class="ki-filled ki-information-2 text-red-600"></i>
            Erreurs de validation
        </div>
        <ul class="list-disc list-inside text-sm space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Formulaire -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <form method="POST" action="{{ route('secretary.service-requests.store') }}" class="p-6">
            @csrf

            <!-- Section : Informations Patient -->
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ki-filled ki-user text-blue-600"></i>
                    Informations du Patient
                </h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Prénom -->
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Prénom <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="first_name" id="first_name"
                               value="{{ old('first_name') }}" required
                               placeholder="Ex: Jean"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('first_name') border-red-500 @enderror">
                        @error('first_name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nom -->
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="last_name" id="last_name"
                               value="{{ old('last_name') }}" required
                               placeholder="Ex: Koné"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('last_name') border-red-500 @enderror">
                        @error('last_name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email"
                               value="{{ old('email') }}" required
                               placeholder="patient@example.com"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Téléphone -->
                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Téléphone <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="phone_number" id="phone_number"
                               value="{{ old('phone_number') }}" required
                               placeholder="+225 07 XX XX XX XX"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone_number') border-red-500 @enderror">
                        @error('phone_number')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section : Détails de la Demande -->
            <div class="mb-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ki-filled ki-calendar text-blue-600"></i>
                    Détails de la Demande
                </h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Type de service -->
                    <div>
                        <label for="service_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Type de service <span class="text-red-500">*</span>
                        </label>
                        <select name="service_type" id="service_type" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('service_type') border-red-500 @enderror">
                            <option value="">Sélectionner...</option>
                            <option value="consultation" {{ old('service_type') == 'consultation' ? 'selected' : '' }}>Consultation</option>
                            <option value="appointment" {{ old('service_type', 'appointment') == 'appointment' ? 'selected' : '' }}>Rendez-vous</option>
                            <option value="home_visit" {{ old('service_type') == 'home_visit' ? 'selected' : '' }}>Visite à domicile</option>
                            <option value="emergency" {{ old('service_type') == 'emergency' ? 'selected' : '' }}>Urgence</option>
                            <option value="transport" {{ old('service_type') == 'transport' ? 'selected' : '' }}>Transport médicalisé</option>
                        </select>
                        @error('service_type')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Urgence -->
                    <div>
                        <label for="urgency" class="block text-sm font-medium text-gray-700 mb-2">
                            Niveau d'urgence <span class="text-red-500">*</span>
                        </label>
                        <select name="urgency" id="urgency" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('urgency') border-red-500 @enderror">
                            <option value="low" {{ old('urgency') == 'low' ? 'selected' : '' }}>🟢 Faible</option>
                            <option value="medium" {{ old('urgency', 'medium') == 'medium' ? 'selected' : '' }}>🟡 Moyenne</option>
                            <option value="high" {{ old('urgency') == 'high' ? 'selected' : '' }}>🔴 Élevée</option>
                        </select>
                        @error('urgency')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date préférée -->
                    <div>
                        <label for="preferred_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Date préférée
                        </label>
                        <input type="date" name="preferred_date" id="preferred_date"
                               value="{{ old('preferred_date', now()->addDay()->format('Y-m-d')) }}"
                               min="{{ now()->format('Y-m-d') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('preferred_date') border-red-500 @enderror">
                        @error('preferred_date')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Heure préférée -->
                    <div>
                        <label for="preferred_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Heure préférée
                        </label>
                        <input type="time" name="preferred_time" id="preferred_time"
                               value="{{ old('preferred_time', '10:00') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('preferred_time') border-red-500 @enderror">
                        @error('preferred_time')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Message (sur toute la largeur) -->
                    <div class="lg:col-span-2">
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                            Message / Motif de consultation
                        </label>
                        <textarea name="message" id="message" rows="4"
                                  placeholder="Décrivez le motif de la demande ou les symptômes du patient..."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section : Paiement -->
            <div class="mb-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ki-filled ki-dollar text-blue-600"></i>
                    Informations de Paiement
                </h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Montant payé -->
                    <div>
                        <label for="payment_amount" class="block text-sm font-medium text-gray-700 mb-2">
                            Montant payé (FCFA) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="payment_amount" id="payment_amount"
                               value="{{ old('payment_amount', 2000) }}" required
                               min="0" step="100"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('payment_amount') border-red-500 @enderror">
                        @error('payment_amount')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Méthode de paiement -->
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                            Méthode de paiement <span class="text-red-500">*</span>
                        </label>
                        <select name="payment_method" id="payment_method" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('payment_method') border-red-500 @enderror">
                            <option value="cash" {{ old('payment_method', 'cash') == 'cash' ? 'selected' : '' }}>💵 Espèces</option>
                            <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>💳 Carte bancaire</option>
                            <option value="mobile_money" {{ old('payment_method') == 'mobile_money' ? 'selected' : '' }}>📱 Mobile Money</option>
                            <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>🏦 Virement bancaire</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Boutons -->
            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 12px; padding-top: 24px; border-top: 1px solid #e5e7eb;">
                <a href="{{ route('secretary.service-requests.index') }}"
                   style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; font-size: 14px; font-weight: 500; color: #374151; background-color: white; border: 1px solid #d1d5db; border-radius: 8px; transition: background-color 0.2s;">
                    <i class="ki-filled ki-cross text-sm"></i>
                    Annuler
                </a>
                <button type="submit"
                        style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 24px; font-size: 14px; font-weight: 500; color: white; background-color: rgb(34, 197, 94); border: none; border-radius: 8px; cursor: pointer; transition: all 0.2s;">
                    <i class="ki-filled ki-check text-sm"></i>
                    Créer la demande
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
