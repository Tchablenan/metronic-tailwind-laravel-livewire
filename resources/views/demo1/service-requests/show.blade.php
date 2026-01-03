@extends('layouts.demo1.base')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb + Bouton retour -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('service-requests.index') }}"
               class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-white border border-gray-300 hover:bg-gray-50 hover:shadow-sm transition-all">
                <i class="ki-filled ki-left text-gray-700"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Demande #{{ $serviceRequest->id }}
                </h1>
                <p class="text-sm text-gray-600 font-medium">
                    Reçue le {{ $serviceRequest->created_at->format('d/m/Y à H:i') }}
                </p>
            </div>
        </div>

        <!-- Badges statut + paiement -->
        <div class="flex items-center gap-3">
            @php
            $statusConfig = [
                'pending' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-700', 'border' => 'border-orange-200', 'icon' => 'ki-time'],
                'contacted' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-200', 'icon' => 'ki-phone'],
                'converted' => ['bg' => 'bg-green-50', 'text' => 'text-green-700', 'border' => 'border-green-200', 'icon' => 'ki-check-circle'],
                'rejected' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-200', 'icon' => 'ki-cross-circle'],
                'cancelled' => ['bg' => 'bg-gray-50', 'text' => 'text-gray-700', 'border' => 'border-gray-200', 'icon' => 'ki-cross'],
            ];
            $config = $statusConfig[$serviceRequest->status] ?? $statusConfig['pending'];

            $paymentConfig = [
                'unpaid' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-200', 'icon' => 'ki-cross-circle'],
                'paid' => ['bg' => 'bg-green-50', 'text' => 'text-green-700', 'border' => 'border-green-200', 'icon' => 'ki-check-circle'],
                'refunded' => ['bg' => 'bg-gray-50', 'text' => 'text-gray-700', 'border' => 'border-gray-200', 'icon' => 'ki-arrow-left'],
            ];
            $payConfig = $paymentConfig[$serviceRequest->payment_status] ?? $paymentConfig['unpaid'];
            @endphp

            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold border {{ $config['bg'] }} {{ $config['text'] }} {{ $config['border'] }}">
                <i class="ki-filled {{ $config['icon'] }}"></i>
                @switch($serviceRequest->status)
                    @case('pending') En attente @break
                    @case('contacted') Contacté @break
                    @case('converted') Converti en RDV @break
                    @case('rejected') Rejeté @break
                    @case('cancelled') Annulé @break
                    @default {{ $serviceRequest->status }}
                @endswitch
            </span>

            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold border {{ $payConfig['bg'] }} {{ $payConfig['text'] }} {{ $payConfig['border'] }}">
                <i class="ki-filled {{ $payConfig['icon'] }}"></i>
                {{ $serviceRequest->payment_status_label }}
            </span>

            @if($serviceRequest->sent_to_doctor)
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold border bg-blue-50 text-blue-700 border-blue-200">
                <i class="ki-filled ki-send"></i>
                Envoyé au médecin
            </span>
            @endif
        </div>
    </div>

    <!-- Messages flash -->
    @if (session('success'))
        <div class="mb-4 px-4 py-3.5 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-800 rounded-lg shadow-sm">
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
        <div class="mb-4 px-4 py-3.5 bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 text-red-800 rounded-lg shadow-sm">
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne principale -->
        <div class="lg:col-span-2 space-y-4">

            <!-- Informations du demandeur -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-4 py-4 bg-gradient-to-r from-blue-50 to-cyan-50 border-b border-blue-500">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="ki-filled ki-profile-circle text-blue-600"></i>
                        Informations du demandeur
                    </h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">
                                Nom complet
                            </label>
                            <p class="text-base font-semibold text-gray-900">
                                {{ $serviceRequest->full_name }}
                            </p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">
                                Email
                            </label>
                            <a href="mailto:{{ $serviceRequest->email }}"
                               class="text-base font-medium text-blue-600 hover:text-blue-700 hover:underline flex items-center gap-1.5">
                                <i class="ki-filled ki-sms text-sm"></i>
                                {{ $serviceRequest->email }}
                            </a>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">
                                Téléphone
                            </label>
                            <a href="tel:{{ $serviceRequest->phone_number }}"
                               class="text-base font-medium text-blue-600 hover:text-blue-700 hover:underline flex items-center gap-1.5">
                                <i class="ki-filled ki-phone text-sm"></i>
                                {{ $serviceRequest->phone_number }}
                            </a>
                        </div>
                        @if($serviceRequest->patient)
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">
                                Patient lié
                            </label>
                            <a href="{{ route('users.show', $serviceRequest->patient) }}"
                               class="text-base font-medium text-blue-600 hover:text-blue-700 hover:underline flex items-center gap-1.5">
                                <i class="ki-filled ki-user text-sm"></i>
                                Voir le profil patient
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Détails de la demande -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-4 py-4 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-purple-100">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="ki-filled ki-document text-purple-600"></i>
                        Détails de la demande
                    </h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">
                                Type de service
                            </label>
                            @php
                            $serviceColors = [
                                'appointment' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'home_visit' => 'bg-orange-50 text-orange-700 border-orange-200',
                                'emergency' => 'bg-red-50 text-red-700 border-red-200',
                                'transport' => 'bg-purple-50 text-purple-700 border-purple-200',
                                'consultation' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                                'other' => 'bg-gray-50 text-gray-700 border-gray-200',
                            ];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium border {{ $serviceColors[$serviceRequest->service_type] ?? 'bg-gray-50 text-gray-700 border-gray-200' }}">
                                @switch($serviceRequest->service_type)
                                    @case('appointment') Rendez-vous médical @break
                                    @case('home_visit') Visite à domicile @break
                                    @case('emergency') Urgence @break
                                    @case('transport') Transport médicalisé @break
                                    @case('consultation') Consultation @break
                                    @case('other') Autre service @break
                                    @default {{ $serviceRequest->service_type }}
                                @endswitch
                            </span>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">
                                Niveau d'urgence
                            </label>
                            @php
                            $urgencyColors = [
                                'low' => 'bg-green-50 text-green-700 border-green-200',
                                'medium' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                'high' => 'bg-red-50 text-red-700 border-red-200',
                            ];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium border {{ $urgencyColors[$serviceRequest->urgency] ?? 'bg-gray-50 text-gray-700 border-gray-200' }}">
                                @switch($serviceRequest->urgency)
                                    @case('low') Faible @break
                                    @case('medium') Moyenne @break
                                    @case('high') Élevée @break
                                    @default {{ $serviceRequest->urgency }}
                                @endswitch
                            </span>
                        </div>
                        @if($serviceRequest->preferred_date)
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">
                                Date souhaitée
                            </label>
                            <p class="text-base font-medium text-gray-900 flex items-center gap-1.5">
                                <i class="ki-filled ki-calendar text-gray-400"></i>
                                {{ \Carbon\Carbon::parse($serviceRequest->preferred_date)->format('d/m/Y') }}
                            </p>
                        </div>
                        @endif
                        @if($serviceRequest->preferred_time)
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">
                                Heure souhaitée
                            </label>
                            <p class="text-base font-medium text-gray-900 flex items-center gap-1.5">
                                <i class="ki-filled ki-time text-gray-400"></i>
                                {{ \Carbon\Carbon::parse($serviceRequest->preferred_time)->format('H:i') }}
                            </p>
                        </div>
                        @endif
                    </div>

                    @if($serviceRequest->message)
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2 block">
                            Message / Préoccupation
                        </label>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $serviceRequest->message }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Section PAIEMENT (NOUVELLE) -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-4 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-green-500">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="ki-filled ki-dollar text-green-600"></i>
                        Informations de paiement
                    </h3>
                </div>
                <div class="p-4">
                    @if($serviceRequest->payment_status === 'paid')
                        <!-- Paiement déjà effectué -->
                        <div class="bg-green-50 border-2 border-green-200 rounded-xl p-6 mb-4">
                            <div class="flex items-start gap-4">
                                <div class="w-14 h-14 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0">
                                    <i class="ki-filled ki-check text-3xl text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-lg font-bold text-green-900 mb-2">Paiement effectué ✓</p>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-xs font-semibold text-green-700 uppercase tracking-wide mb-1">Montant</p>
                                            <p class="text-2xl font-bold text-green-900">{{ number_format($serviceRequest->payment_amount, 0, ',', ' ') }} FCFA</p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold text-green-700 uppercase tracking-wide mb-1">Méthode</p>
                                            <p class="text-base font-semibold text-green-900">{{ $serviceRequest->payment_method_label }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold text-green-700 uppercase tracking-wide mb-1">Payé le</p>
                                            <p class="text-sm text-green-800">{{ $serviceRequest->paid_at->format('d/m/Y à H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Formulaire d'enregistrement du paiement -->
                        <div class="bg-orange-50 border-2 border-orange-200 rounded-xl p-6 mb-6">
                            <div class="flex items-start gap-3 mb-4">
                                <i class="ki-filled ki-information-2 text-2xl text-orange-600"></i>
                                <div>
                                    <p class="font-semibold text-orange-900 mb-1">Paiement requis</p>
                                    <p class="text-sm text-orange-800">Le patient doit effectuer le paiement avant que la demande ne soit envoyée au médecin chef.</p>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('secretary.service-requests.mark-paid', $serviceRequest) }}" method="POST" class="space-y-4">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-semibold text-gray-700 mb-2 block">
                                        Montant payé (FCFA) *
                                    </label>
                                    <input type="number"
                                           name="payment_amount"
                                           step="0.01"
                                           min="0"
                                           required
                                           placeholder="Ex: 25000"
                                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-base font-semibold">
                                    @error('payment_amount')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="text-sm font-semibold text-gray-700 mb-2 block">
                                        Méthode de paiement *
                                    </label>
                                    <select name="payment_method"
                                            required
                                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-base font-semibold cursor-pointer">
                                        <option value="">Sélectionner...</option>
                                        <option value="cash">💵 Espèces</option>
                                        <option value="mobile_money">📱 Mobile Money</option>
                                        <option value="card">💳 Carte bancaire</option>
                                        <option value="insurance">🏥 Assurance</option>
                                    </select>
                                    @error('payment_method')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 bg-green-500 text-white text-base font-bold rounded-lg hover:bg-green-700 hover:shadow-lg active:scale-95 transition-all">
                                <i class="ki-filled ki-check-circle text-xl"></i>
                                Confirmer le paiement
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Notes internes -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-amber-50 to-orange-50 border-b border-amber-100">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="ki-filled ki-note-2 text-amber-600"></i>
                        Notes internes
                    </h3>
                </div>
                <div class="p-6">
                    @if($serviceRequest->internal_notes)
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-4">
                            <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $serviceRequest->internal_notes }}</p>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 italic mb-4">Aucune note interne pour le moment</p>
                    @endif

                    <!-- Formulaire ajout de notes -->
                    <form action="{{ route('service-requests.notes', $serviceRequest) }}" method="POST">
                        @csrf
                        <textarea
                            name="internal_notes"
                            rows="3"
                            placeholder="Ajouter ou modifier les notes internes..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-sm resize-none"
                        >{{ old('internal_notes', $serviceRequest->internal_notes) }}</textarea>
                        @error('internal_notes')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <button type="submit"
                                class="mt-3 inline-flex items-center gap-2 px-4 py-2 bg-amber-600 text-white text-sm font-medium rounded-lg hover:bg-amber-700 hover:shadow-md active:scale-95 transition-all">
                            <i class="ki-filled ki-check text-sm"></i>
                            Enregistrer les notes
                        </button>
                    </form>
                </div>
            </div>

            <!-- Rendez-vous créé (si converti) -->
            @if($serviceRequest->appointment)
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-green-100">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="ki-filled ki-calendar-tick text-green-600"></i>
                        Rendez-vous créé
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">
                                Date du RDV
                            </label>
                            <p class="text-base font-medium text-gray-900">
                                {{ $serviceRequest->appointment->formatted_date }}
                            </p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">
                                Heure
                            </label>
                            <p class="text-base font-medium text-gray-900">
                                {{ $serviceRequest->appointment->formatted_time }}
                            </p>
                        </div>
                        @if($serviceRequest->appointment->doctor)
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">
                                Médecin
                            </label>
                            <p class="text-base font-medium text-gray-900">
                                Dr. {{ $serviceRequest->appointment->doctor->full_name }}
                            </p>
                        </div>
                        @endif
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">
                                Statut RDV
                            </label>
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium border bg-green-50 text-green-700 border-green-200">
                                {{ $serviceRequest->appointment->status_label }}
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('appointments.show', $serviceRequest->appointment) }}"
                       class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 hover:shadow-md active:scale-95 transition-all">
                        <i class="ki-filled ki-eye text-sm"></i>
                        Voir le rendez-vous
                    </a>
                </div>
            </div>
            @endif

        </div>

        <!-- Colonne actions -->
        <div class="space-y-4">

            <!-- Actions rapides -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-4 py-4 bg-gradient-to-r from-indigo-50 to-blue-50 border-b border-violet-200">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="ki-filled ki-setting-3 text-secondary-foreground"></i>
                        Actions
                    </h3>
                </div>
                <div class="p-4 space-y-4">

                    @if($serviceRequest->status === 'pending' && $serviceRequest->payment_status !== 'paid')
                        <!-- Marquer comme contacté -->
                        <form action="{{ route('service-requests.contacted', $serviceRequest) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-violet-500 text-white text-sm font-medium rounded-lg hover:bg-purple-700 hover:shadow-md active:scale-95 transition-all">
                                <i class="ki-filled ki-phone"></i>
                                Marquer comme contacté
                            </button>
                        </form>
                    @endif

                    @if($serviceRequest->payment_status === 'paid' && !$serviceRequest->sent_to_doctor && !$serviceRequest->appointment)
                        <!-- Envoyer au médecin chef (NOUVELLE ACTION) -->
                        <form action="{{ route('secretary.service-requests.send-to-doctor', $serviceRequest) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 hover:shadow-md active:scale-95 transition-all animate-pulse">
                                <i class="ki-filled ki-send"></i>
                                Envoyer au médecin chef
                            </button>
                        </form>
                    @endif

                    @if($serviceRequest->sent_to_doctor && !$serviceRequest->appointment && Auth::user()->role === 'secretary')
                        <!-- Annuler l'envoi (si erreur) -->
                        <form action="{{ route('secretary.service-requests.cancel-send', $serviceRequest) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-gray-100 text-black text-sm font-medium rounded-lg hover:bg-gray-700 hover:shadow-md active:scale-95 transition-all">
                                <i class="ki-filled ki-arrow-left"></i>
                                Annuler l'envoi
                            </button>
                        </form>
                    @endif

                    @if(in_array($serviceRequest->status, ['pending', 'contacted']) && !$serviceRequest->appointment && Auth::user()->role === 'doctor')
                        <!-- Convertir en RDV (SEULEMENT POUR LE MEDECIN) -->
                        <button onclick="document.getElementById('convertModal').classList.remove('hidden')"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-green-500 text-white text-sm font-medium rounded-lg hover:bg-green-700 hover:shadow-md active:scale-95 transition-all">
                            <i class="ki-filled ki-check-circle"></i>
                            Convertir en rendez-vous
                        </button>

                        <!-- Rejeter -->
                        <button onclick="document.getElementById('rejectModal').classList.remove('hidden')"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 hover:shadow-md active:scale-95 transition-all">
                            <i class="ki-filled ki-cross-circle"></i>
                            Rejeter la demande
                        </button>
                    @endif

                    <!-- Appeler le patient -->
                    <a href="tel:{{ $serviceRequest->phone_number }}"
                       class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-primary text-white text-sm font-medium rounded-lg hover:bg-cyan-700 hover:shadow-md active:scale-95 transition-all">
                        <i class="ki-filled ki-phone"></i>
                        Appeler le patient
                    </a>

                    <!-- Envoyer un email -->
                    <a href="mailto:{{ $serviceRequest->email }}"
                       class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-mono text-white text-sm font-medium rounded-lg hover:bg-indigo-700 hover:shadow-md active:scale-95 transition-all">
                        <i class="ki-filled ki-sms"></i>
                        Envoyer un email
                    </a>
                </div>
            </div>

            <!-- Traçabilité -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-4 py-4 bg-gradient-to-r from-gray-50 to-slate-50 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="ki-filled ki-information text-gray-600"></i>
                        Traçabilité
                    </h3>
                </div>
                <div class="p-4 space-y-4">
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">
                            Demande reçue le
                        </label>
                        <p class="text-sm text-gray-900 font-medium">
                            {{ $serviceRequest->created_at->format('d/m/Y à H:i') }}
                        </p>
                    </div>

                    @if($serviceRequest->handled_by)
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">
                            Traitée par
                        </label>
                        <p class="text-sm text-gray-900 font-medium">
                            {{ $serviceRequest->handler->full_name }}
                        </p>
                    </div>
                    @endif

                    @if($serviceRequest->handled_at)
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">
                            Traitée le
                        </label>
                        <p class="text-sm text-gray-900 font-medium">
                            {{ $serviceRequest->handled_at->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                    @endif

                    @if($serviceRequest->sent_to_doctor)
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">
                            Envoyée au médecin le
                        </label>
                        <p class="text-sm text-gray-900 font-medium">
                            {{ $serviceRequest->sent_to_doctor_at->format('d/m/Y à H:i') }}
                        </p>
                        @if($serviceRequest->sender)
                        <p class="text-xs text-gray-600 mt-1">
                            Par {{ $serviceRequest->sender->full_name }}
                        </p>
                        @endif
                    </div>
                    @endif

                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">
                            Dernière modification
                        </label>
                        <p class="text-sm text-gray-900 font-medium">
                            {{ $serviceRequest->updated_at->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal Convertir en RDV (SEULEMENT POUR MEDECIN) -->
@if(Auth::user()->role === 'doctor')
<div id="convertModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-green-100">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <i class="ki-filled ki-check-circle text-green-600"></i>
                Convertir en rendez-vous
            </h3>
        </div>
        <form action="{{ route('service-requests.convert', $serviceRequest) }}" method="POST">
            @csrf
            <div class="p-6">
                <p class="text-sm text-gray-600 mb-4">
                    Cette action va créer un compte patient (si nécessaire) et un rendez-vous.
                    Le patient recevra un email pour activer son compte.
                </p>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <p class="text-sm text-blue-800">
                        <strong>Note :</strong> Vous serez redirigé vers le formulaire de création de rendez-vous avec les informations pré-remplies.
                    </p>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                <button type="button"
                        onclick="document.getElementById('convertModal').classList.add('hidden')"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all">
                    Annuler
                </button>
                <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-all">
                    Convertir en RDV
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Rejeter -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <div class="px-6 py-4 bg-gradient-to-r from-red-50 to-rose-50 border-b border-red-100">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <i class="ki-filled ki-cross-circle text-red-600"></i>
                Rejeter la demande
            </h3>
        </div>
        <form action="{{ route('service-requests.reject', $serviceRequest) }}" method="POST">
            @csrf
            <div class="p-6">
                <label class="text-sm font-semibold text-gray-700 mb-2 block">
                    Raison du rejet *
                </label>
                <textarea
                    name="rejection_reason"
                    rows="4"
                    required
                    placeholder="Expliquez pourquoi cette demande est rejetée..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm resize-none"
                ></textarea>
                @error('rejection_reason')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                <button type="button"
                        onclick="document.getElementById('rejectModal').classList.add('hidden')"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all">
                    Annuler
                </button>
                <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-all">
                    Rejeter la demande
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection
