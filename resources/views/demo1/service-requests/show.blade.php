@extends('layouts.demo1.base')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header avec breadcrumb -->
    <div class="mb-6">
        <nav class="flex items-center gap-2 text-sm text-gray-600 mb-4">
            <a href="{{ route('service-requests.index') }}" class="hover:text-gray-900">
                Demandes
            </a>
            <i class="ki-filled ki-right text-xs"></i>
            <span class="text-gray-900 font-medium">Demande #{{ $serviceRequest->id }}</span>
        </nav>

        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Demande de {{ $serviceRequest->full_name }}
                </h1>
                <p class="text-sm text-gray-600 mt-1">
                    Reçue le {{ $serviceRequest->created_at->format('d/m/Y à H:i') }}
                </p>
            </div>

            <!-- Badges de statut -->
            <div class="flex flex-col items-end gap-2">
                @php
                $statusStyles = [
                    'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                    'contacted' => 'bg-purple-50 text-purple-700 border-purple-200',
                    'converted' => 'bg-green-50 text-green-700 border-green-200',
                    'rejected' => 'bg-red-50 text-red-700 border-red-200',
                ];
                $urgencyStyles = [
                    'low' => 'bg-green-50 text-green-700 border-green-200',
                    'medium' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                    'high' => 'bg-red-50 text-red-700 border-red-200',
                ];
                @endphp
                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-sm font-medium border {{ $statusStyles[$serviceRequest->status] ?? 'bg-gray-50 text-gray-700' }}">
                    <span class="w-2 h-2 rounded-full bg-current"></span>
                    @switch($serviceRequest->status)
                        @case('pending') En attente @break
                        @case('contacted') Contacté @break
                        @case('converted') Converti en RDV @break
                        @case('rejected') Rejeté @break
                        @default {{ $serviceRequest->status }}
                    @endswitch
                </span>
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium border {{ $urgencyStyles[$serviceRequest->urgency] ?? 'bg-gray-50 text-gray-700' }}">
                    @if($serviceRequest->urgency === 'high') 🔴 Urgence élevée
                    @elseif($serviceRequest->urgency === 'medium') 🟡 Urgence moyenne
                    @else 🟢 Urgence faible
                    @endif
                </span>
            </div>
        </div>
    </div>

    <!-- Messages flash -->
    @if (session('success'))
    <div class="mb-6 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-lg">
        <div class="flex items-center justify-between">
            <span class="flex items-center gap-2 text-sm font-medium">
                <i class="ki-filled ki-check-circle text-green-600"></i>
                {{ session('success') }}
            </span>
            <button onclick="this.parentElement.parentElement.remove()" class="text-green-600 hover:text-green-800 p-1">
                <i class="ki-filled ki-cross text-xs"></i>
            </button>
        </div>
    </div>
    @endif

    @if (session('error'))
    <div class="mb-6 px-4 py-3 bg-red-50 border border-red-200 text-red-800 rounded-lg">
        <div class="flex items-center justify-between">
            <span class="flex items-center gap-2 text-sm font-medium">
                <i class="ki-filled ki-cross-circle text-red-600"></i>
                {{ session('error') }}
            </span>
            <button onclick="this.parentElement.parentElement.remove()" class="text-red-600 hover:text-red-800 p-1">
                <i class="ki-filled ki-cross text-xs"></i>
            </button>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Colonne principale (2/3) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Informations du demandeur -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ki-filled ki-profile-user text-blue-600"></i>
                    Informations du demandeur
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-medium text-gray-600 uppercase">Nom complet</label>
                        <p class="text-sm text-gray-900 font-medium mt-1">{{ $serviceRequest->full_name }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 uppercase">Email</label>
                        <p class="text-sm text-gray-900 mt-1">
                            <a href="mailto:{{ $serviceRequest->email }}" class="text-blue-600 hover:underline">
                                {{ $serviceRequest->email }}
                            </a>
                        </p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 uppercase">Téléphone</label>
                        <p class="text-sm text-gray-900 mt-1">
                            <a href="tel:{{ $serviceRequest->phone_number }}" class="text-blue-600 hover:underline">
                                {{ $serviceRequest->phone_number }}
                            </a>
                        </p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600 uppercase">Type de service</label>
                        <p class="text-sm text-gray-900 mt-1">
                            @switch($serviceRequest->service_type)
                                @case('appointment') 📅 Rendez-vous médical @break
                                @case('home_visit') 🏠 Visite à domicile @break
                                @case('emergency') 🚨 Urgence @break
                                @case('transport') 🚑 Transport médicalisé @break
                                @case('consultation') 🩺 Consultation @break
                                @default {{ $serviceRequest->service_type }}
                            @endswitch
                        </p>
                    </div>
                </div>
            </div>

            <!-- Détails de la demande -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ki-filled ki-document text-blue-600"></i>
                    Détails de la demande
                </h2>
                <div class="space-y-4">
                    @if($serviceRequest->preferred_date)
                    <div>
                        <label class="text-xs font-medium text-gray-600 uppercase">Date souhaitée</label>
                        <p class="text-sm text-gray-900 mt-1">
                            {{ $serviceRequest->preferred_date->format('d/m/Y') }}
                            @if($serviceRequest->preferred_time)
                                à {{ \Carbon\Carbon::parse($serviceRequest->preferred_time)->format('H:i') }}
                            @endif
                        </p>
                    </div>
                    @endif

                    @if($serviceRequest->message)
                    <div>
                        <label class="text-xs font-medium text-gray-600 uppercase">Message</label>
                        <p class="text-sm text-gray-900 mt-2 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            {{ $serviceRequest->message }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Informations de paiement -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ki-filled ki-dollar text-green-600"></i>
                    Informations de paiement
                </h2>

                @if($serviceRequest->payment_status === 'paid')
                    <!-- Paiement effectué -->
                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                                <i class="ki-filled ki-check-circle text-white text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-green-900">Paiement confirmé</p>
                                <p class="text-xs text-green-700">Montant : {{ number_format($serviceRequest->payment_amount, 0, ',', ' ') }} FCFA</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div>
                                <span class="text-gray-600">Méthode :</span>
                                <span class="font-medium text-gray-900">
                                    @switch($serviceRequest->payment_method)
                                        @case('cash') Espèces @break
                                        @case('mobile_money') Mobile Money @break
                                        @case('card') Carte bancaire @break
                                        @case('insurance') Assurance @break
                                        @default {{ $serviceRequest->payment_method }}
                                    @endswitch
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-600">Date :</span>
                                <span class="font-medium text-gray-900">{{ $serviceRequest->paid_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>

                        @if($serviceRequest->sent_to_doctor)
                        <div class="mt-3 pt-3 border-t border-green-300">
                            <div class="flex items-center gap-2 text-sm">
                                <i class="ki-filled ki-send text-blue-600"></i>
                                <span class="text-gray-700">
                                    Envoyé au médecin chef le {{ $serviceRequest->sent_to_doctor_at->format('d/m/Y à H:i') }}
                                    @if($serviceRequest->sender)
                                        par {{ $serviceRequest->sender->full_name }}
                                    @endif
                                </span>
                            </div>
                        </div>
                        @endif
                    </div>

                    @if(Auth::user()->role === 'secretary' && !$serviceRequest->sent_to_doctor && !in_array($serviceRequest->status, ['converted', 'rejected']))
                    <!-- Bouton envoyer au médecin -->
                    <form action="{{ route('secretary.service-requests.send-to-doctor', $serviceRequest) }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 shadow-lg hover:shadow-xl transition-all animate-pulse">
                            <i class="ki-filled ki-send"></i>
                            Envoyer au médecin chef
                        </button>
                    </form>
                    @endif

                    @if(Auth::user()->role === 'secretary' && $serviceRequest->sent_to_doctor && $serviceRequest->status === 'contacted')
                    <!-- Bouton annuler l'envoi -->
                    <form action="{{ route('secretary.service-requests.cancel-send', $serviceRequest) }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit"
                                onclick="return confirm('Êtes-vous sûr de vouloir annuler l\'envoi au médecin ?')"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-yellow-100 text-yellow-700 text-sm font-medium rounded-lg hover:bg-yellow-200 border border-yellow-300">
                            <i class="ki-filled ki-cross-circle"></i>
                            Annuler l'envoi au médecin
                        </button>
                    </form>
                    @endif

                @else
                    <!-- Paiement non effectué -->
                    @if(Auth::user()->role === 'secretary')
                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center">
                                <i class="ki-filled ki-cross-circle text-white text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-red-900">Paiement non effectué</p>
                                <p class="text-xs text-red-700">Veuillez enregistrer le paiement ci-dessous</p>
                            </div>
                        </div>
                    </div>

                    <!-- Formulaire de paiement (SECRÉTAIRE) -->
                    <form action="{{ route('secretary.service-requests.mark-paid', $serviceRequest) }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Montant payé (FCFA) <span class="text-red-500">*</span>
                            </label>
                            <input type="number"
                                   name="payment_amount"
                                   min="0"
                                   step="100"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                   placeholder="25000">
                            @error('payment_amount')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Méthode de paiement <span class="text-red-500">*</span>
                            </label>
                            <select name="payment_method"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">Sélectionner...</option>
                                <option value="cash">Espèces</option>
                                <option value="mobile_money">Mobile Money</option>
                                <option value="card">Carte bancaire</option>
                                <option value="insurance">Assurance</option>
                            </select>
                            @error('payment_method')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 shadow-md hover:shadow-lg transition-all">
                            <i class="ki-filled ki-check-circle"></i>
                            Confirmer le paiement
                        </button>
                    </form>
                    @else
                    <!-- Vue médecin : paiement non effectué -->
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-sm text-yellow-800">
                            <i class="ki-filled ki-information text-yellow-600"></i>
                            Cette demande n'a pas encore été payée. La secrétaire doit d'abord enregistrer le paiement.
                        </p>
                    </div>
                    @endif
                @endif
            </div>

            <!-- Notes internes -->
            @if($serviceRequest->internal_notes)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ki-filled ki-note text-purple-600"></i>
                    Notes internes
                </h2>
                <p class="text-sm text-gray-900 p-4 bg-purple-50 rounded-lg border border-purple-200">
                    {{ $serviceRequest->internal_notes }}
                </p>
            </div>
            @endif

        </div>

        <!-- Colonne latérale (1/3) -->
        <div class="space-y-6">

            <!-- Actions rapides -->
<!-- Actions rapides -->
<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-base font-bold text-gray-900 mb-4">Actions</h3>
    <div class="space-y-3">

        @if(Auth::user()->role === 'secretary')
            <!-- Actions secrétaire -->
            @if($serviceRequest->status === 'pending')
            <form action="{{ route('service-requests.contacted', $serviceRequest) }}" method="POST">
                @csrf
                <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700">
                    <i class="ki-filled ki-phone"></i>
                    Marquer comme contacté
                </button>
            </form>
            @endif

        @elseif(Auth::user()->role === 'doctor')
            <!-- Actions médecin -->
            @if(!in_array($serviceRequest->status, ['converted', 'rejected']))

            <!-- Convertir en RDV : Rediriger vers appointments.create -->
            <a href="{{ route('appointments.create', ['service_request_id' => $serviceRequest->id]) }}"
               class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-green-500 text-white text-sm font-medium rounded-lg hover:bg-green-700 shadow-md hover:shadow-lg transition-all">
                <i class="ki-filled ki-calendar-add"></i>
                Convertir en rendez-vous
            </a>

            <!-- Rejeter : Formulaire inline simple -->
            <div>
                <button type="button"
                        onclick="document.getElementById('rejectForm').classList.toggle('hidden')"
                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-red-100 text-red-700 text-sm font-medium rounded-lg hover:bg-red-200 border border-red-300">
                    <i class="ki-filled ki-cross-circle"></i>
                    Rejeter la demande
                </button>

                <!-- Formulaire inline caché -->
                <div id="rejectForm" class="hidden mt-3 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <form action="{{ route('service-requests.reject', $serviceRequest) }}" method="POST" class="space-y-3">
                        @csrf

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Raison du rejet <span class="text-red-500">*</span>
                            </label>
                            <textarea name="rejection_reason"
                                      rows="3"
                                      required
                                      class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500"
                                      placeholder="Expliquez pourquoi cette demande est rejetée..."></textarea>
                        </div>

                        <div class="flex gap-2">
                            <button type="button"
                                    onclick="document.getElementById('rejectForm').classList.add('hidden')"
                                    class="flex-1 px-3 py-2 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                                Annuler
                            </button>
                            <button type="submit"
                                    class="flex-1 px-3 py-2 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                                <i class="ki-filled ki-cross-circle"></i>
                                Rejeter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        @endif

        <!-- Ajouter des notes (pour tous) -->
        <div>
            <button type="button"
                    onclick="document.getElementById('notesForm').classList.toggle('hidden')"
                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">
                <i class="ki-filled ki-note"></i>
                {{ $serviceRequest->internal_notes ? 'Modifier les notes' : 'Ajouter des notes' }}
            </button>

            <!-- Formulaire inline caché -->
            <div id="notesForm" class="hidden mt-3 p-4 bg-purple-50 border border-purple-200 rounded-lg">
                <form action="{{ route('service-requests.notes', $serviceRequest) }}" method="POST" class="space-y-3">
                    @csrf

                    <div>
                        <textarea name="internal_notes"
                                  rows="4"
                                  required
                                  class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                                  placeholder="Notes internes visibles par le personnel...">{{ $serviceRequest->internal_notes }}</textarea>
                    </div>

                    <div class="flex gap-2">
                        <button type="button"
                                onclick="document.getElementById('notesForm').classList.add('hidden')"
                                class="flex-1 px-3 py-2 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            Annuler
                        </button>
                        <button type="submit"
                                class="flex-1 px-3 py-2 text-xs font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700">
                            <i class="ki-filled ki-save"></i>
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
            <!-- Informations complémentaires -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-base font-bold text-gray-900 mb-4">Informations</h3>
                <div class="space-y-3 text-sm">

                    @if($serviceRequest->handler)
                    <div class="flex items-center gap-2 pb-3 border-b border-gray-100">
                        <i class="ki-filled ki-profile-user text-gray-400"></i>
                        <div>
                            <p class="text-xs text-gray-600">Traité par</p>
                            <p class="text-gray-900 font-medium">{{ $serviceRequest->handler->full_name }}</p>
                        </div>
                    </div>
                    @endif

                    @if($serviceRequest->handled_at)
                    <div class="flex items-center gap-2 pb-3 border-b border-gray-100">
                        <i class="ki-filled ki-time text-gray-400"></i>
                        <div>
                            <p class="text-xs text-gray-600">Traité le</p>
                            <p class="text-gray-900">{{ $serviceRequest->handled_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($serviceRequest->appointment)
                    <div class="flex items-center gap-2 pb-3 border-b border-gray-100">
                        <i class="ki-filled ki-calendar-tick text-green-500"></i>
                        <div>
                            <p class="text-xs text-gray-600">Rendez-vous créé</p>
                            <a href="{{ route('appointments.show', $serviceRequest->appointment) }}"
                               class="text-blue-600 hover:underline font-medium">
                                Voir le RDV #{{ $serviceRequest->appointment->id }}
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($serviceRequest->patient)
                    <div class="flex items-center gap-2">
                        <i class="ki-filled ki-profile-circle text-blue-500"></i>
                        <div>
                            <p class="text-xs text-gray-600">Patient lié</p>
                            <a href="{{ route('users.show', $serviceRequest->patient) }}"
                               class="text-blue-600 hover:underline font-medium">
                                {{ $serviceRequest->patient->full_name }}
                            </a>
                        </div>
                    </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal Convertir en RDV (MÉDECIN) -->
@if(Auth::user()->role === 'doctor' && !in_array($serviceRequest->status, ['converted', 'rejected']))
<div id="convertModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900">Convertir en rendez-vous</h3>
                <button onclick="document.getElementById('convertModal').classList.add('hidden')"
                        class="text-gray-400 hover:text-gray-600">
                    <i class="ki-filled ki-cross text-xl"></i>
                </button>
            </div>
        </div>
        <form action="{{ route('service-requests.convert', $serviceRequest) }}" method="POST" class="p-6 space-y-4">
            @csrf

            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-800">
                    <i class="ki-filled ki-information text-blue-600"></i>
                    Un compte patient sera créé automatiquement si l'email ou le téléphone n'existe pas déjà.
                </p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date du rendez-vous</label>
                    <input type="date"
                           name="appointment_date"
                           value="{{ $serviceRequest->preferred_date ? $serviceRequest->preferred_date->format('Y-m-d') : '' }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Heure</label>
                    <input type="time"
                           name="appointment_time"
                           value="{{ $serviceRequest->preferred_time ? \Carbon\Carbon::parse($serviceRequest->preferred_time)->format('H:i') : '' }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Durée (minutes)</label>
                <select name="duration" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="15">15 minutes</option>
                    <option value="30" selected>30 minutes</option>
                    <option value="45">45 minutes</option>
                    <option value="60">1 heure</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button"
                        onclick="document.getElementById('convertModal').classList.add('hidden')"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                    Annuler
                </button>
                <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                    Créer le rendez-vous
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Rejeter (MÉDECIN) -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900">Rejeter la demande</h3>
                <button onclick="document.getElementById('rejectModal').classList.add('hidden')"
                        class="text-gray-400 hover:text-gray-600">
                    <i class="ki-filled ki-cross text-xl"></i>
                </button>
            </div>
        </div>
        <form action="{{ route('service-requests.reject', $serviceRequest) }}" method="POST" class="p-6 space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Raison du rejet <span class="text-red-500">*</span>
                </label>
                <textarea name="rejection_reason"
                          rows="4"
                          required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500"
                          placeholder="Expliquez pourquoi cette demande est rejetée..."></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button"
                        onclick="document.getElementById('rejectModal').classList.add('hidden')"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                    Annuler
                </button>
                <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                    Rejeter
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<!-- Modal Ajouter des notes -->
<div id="notesModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900">Ajouter des notes internes</h3>
                <button onclick="document.getElementById('notesModal').classList.add('hidden')"
                        class="text-gray-400 hover:text-gray-600">
                    <i class="ki-filled ki-cross text-xl"></i>
                </button>
            </div>
        </div>
        <form action="{{ route('service-requests.notes', $serviceRequest) }}" method="POST" class="p-6 space-y-4">
            @csrf

            <div>
                <textarea name="internal_notes"
                          rows="5"
                          required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                          placeholder="Ajoutez des notes internes visibles uniquement par le personnel...">{{ $serviceRequest->internal_notes }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button"
                        onclick="document.getElementById('notesModal').classList.add('hidden')"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                    Annuler
                </button>
                <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
