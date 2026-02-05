@extends('layouts.demo1.base')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between gap-4 pb-8">
        <div class="flex items-center gap-4">
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

        <!-- Bouton Éditer (Secrétaire uniquement) -->
        @if(Auth::user()->role === 'secretary')
            @can('update', $serviceRequest)
                <a href="{{ route('secretary.service-requests.edit', $serviceRequest->id) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 hover:shadow-md active:scale-95 transition-all">
                    <i class="ki-filled ki-pencil"></i>
                    <span>Modifier</span>
                </a>
            @endcan
        @endif
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

            <!-- 🏥 TRIAGE INITIAL -->
            @if($serviceRequest->temperature || $serviceRequest->blood_pressure_systolic || $serviceRequest->weight)
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="ki-filled ki-pulse text-red-600"></i> Triage Initial (Signes Vitaux)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($serviceRequest->temperature)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Température</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ number_format($serviceRequest->temperature, 1) }}°C</p>
                    </div>
                    @endif

                    @if($serviceRequest->blood_pressure_systolic || $serviceRequest->blood_pressure_diastolic)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Tension Artérielle</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $serviceRequest->formatted_blood_pressure ?? 'Non enregistrée' }}</p>
                    </div>
                    @endif

                    @if($serviceRequest->weight)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Poids</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ number_format($serviceRequest->weight, 2) }} kg</p>
                    </div>
                    @endif

                    @if($serviceRequest->height)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Taille</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ number_format($serviceRequest->height, 2) }} cm</p>
                    </div>
                    @endif

                    @if($serviceRequest->weight && $serviceRequest->height)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">IMC (Indice de Masse Corporelle)</label>
                        <p class="mt-1 text-lg font-semibold"
                           style="color: {{ $serviceRequest->bmi < 18.5 ? '#2563eb' : ($serviceRequest->bmi < 25 ? '#16a34a' : ($serviceRequest->bmi < 30 ? '#ca8a04' : '#dc2626')) }}">
                            {{ number_format($serviceRequest->bmi, 2) }}
                            <span class="text-xs font-normal">
                                @if($serviceRequest->bmi < 18.5)
                                    (Maigreur)
                                @elseif($serviceRequest->bmi < 25)
                                    (Normal)
                                @elseif($serviceRequest->bmi < 30)
                                    (Surpoids)
                                @else
                                    (Obésité)
                                @endif
                            </span>
                        </p>
                    </div>
                    @endif
                </div>

                @if($serviceRequest->known_allergies || $serviceRequest->current_medications)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    @if($serviceRequest->known_allergies)
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-600">Allergies Connues</label>
                        <p class="mt-1 text-gray-900 whitespace-pre-wrap">{{ $serviceRequest->known_allergies }}</p>
                    </div>
                    @endif

                    @if($serviceRequest->current_medications)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Médicaments Actuels</label>
                        <p class="mt-1 text-gray-900 whitespace-pre-wrap">{{ $serviceRequest->current_medications }}</p>
                    </div>
                    @endif
                </div>
                @endif
            </div>
            @endif

            <!-- 🛡️ INFORMATIONS ASSURANCE -->
            @if($serviceRequest->has_insurance)
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="ki-filled ki-shield-tick text-green-600"></i> Informations Assurance
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($serviceRequest->insurance_company)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Compagnie d'Assurance</label>
                        <p class="mt-1 text-gray-900 font-semibold">{{ $serviceRequest->insurance_company }}</p>
                    </div>
                    @endif

                    @if($serviceRequest->insurance_policy_number)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Numéro de Police</label>
                        <p class="mt-1 text-gray-900 font-semibold">{{ $serviceRequest->insurance_policy_number }}</p>
                    </div>
                    @endif

                    @if($serviceRequest->insurance_coverage_rate)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Taux de Couverture</label>
                        <p class="mt-1 text-gray-900 font-semibold">{{ $serviceRequest->insurance_coverage_rate }}%</p>
                    </div>
                    @endif

                    @if($serviceRequest->insurance_ceiling)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Plafond</label>
                        <p class="mt-1 text-gray-900 font-semibold">{{ number_format($serviceRequest->insurance_ceiling, 0) }} FCFA</p>
                    </div>
                    @endif

                    @if($serviceRequest->insurance_expiry_date)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Date d'Expiration</label>
                        <p class="mt-1 text-gray-900 font-semibold">{{ $serviceRequest->insurance_expiry_date->format('d/m/Y') }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @else
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="ki-filled ki-shield-tick text-gray-400"></i> Informations Assurance
                </h3>
                <p class="text-gray-500 italic">Le patient n'est pas assuré.</p>
            </div>
            @endif

            <!-- 📋 EXAMENS ANTÉRIEURS -->
            @if($serviceRequest->has_previous_exams)
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="ki-filled ki-file-down text-purple-600"></i> Examens Antérieurs
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($serviceRequest->previous_exam_type)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Type d'Examen</label>
                        <p class="mt-1 text-gray-900 font-semibold">{{ $serviceRequest->getPreviousExamTypeLabel() }}</p>
                    </div>
                    @endif

                    @if($serviceRequest->previous_exam_name)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Nom de l'Examen</label>
                        <p class="mt-1 text-gray-900 font-semibold">{{ $serviceRequest->previous_exam_name }}</p>
                    </div>
                    @endif

                    @if($serviceRequest->previous_exam_facility)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-600">Établissement</label>
                        <p class="mt-1 text-gray-900 font-semibold">{{ $serviceRequest->previous_exam_facility }}</p>
                    </div>
                    @endif

                    @if($serviceRequest->previous_exam_date)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Date de l'Examen</label>
                        <p class="mt-1 text-gray-900 font-semibold">{{ $serviceRequest->previous_exam_date->format('d/m/Y') }}</p>
                    </div>
                    @endif
                </div>

                @if($serviceRequest->hasExamFile())
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <label class="block text-sm font-medium text-gray-600 mb-2">Fichier d'Examen</label>
                    <a href="{{ $serviceRequest->exam_file_url }}"
                       target="_blank"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors">
                        <i class="ki-filled ki-file-down text-blue-700"></i>
                        <span>Télécharger le fichier</span>
                    </a>
                </div>
                @endif
            </div>
            @else
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="ki-filled ki-file-down text-gray-400"></i> Examens Antérieurs
                </h3>
                <p class="text-gray-500 italic">Aucun examen antérieur enregistré.</p>
            </div>
            @endif

            <!-- Statut -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center justify-between">
                    <span class="flex items-center gap-2">
                        <i class="ki-filled ki-document text-blue-600"></i>
                        Statut de la Demande
                    </span>
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">État Actuel</label>
                        <div class="flex items-center gap-3">
                            <span class="inline-block px-4 py-2 rounded-lg text-sm font-bold
                                {{ $serviceRequest->status === 'pending' ? 'bg-yellow-100 text-yellow-800 border border-yellow-300' : '' }}
                                {{ $serviceRequest->status === 'contacted' ? 'bg-blue-100 text-blue-800 border border-blue-300' : '' }}
                                {{ $serviceRequest->status === 'converted' ? 'bg-green-100 text-green-800 border border-green-300' : '' }}
                                {{ $serviceRequest->status === 'rejected' ? 'bg-red-100 text-red-800 border border-red-300' : '' }}">
                                @if($serviceRequest->status === 'pending')
                                    ⏳ En attente
                                @elseif($serviceRequest->status === 'contacted')
                                    📞 Contactée
                                @elseif($serviceRequest->status === 'converted')
                                    ✅ Convertie
                                @elseif($serviceRequest->status === 'rejected')
                                    ❌ Rejetée
                                @else
                                    {{ ucfirst($serviceRequest->status) }}
                                @endif
                            </span>
                        </div>
                    </div>

                    @if($serviceRequest->status === 'rejected' && $serviceRequest->rejection_reason)
                    <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
                        <p class="text-sm font-semibold text-red-900 mb-1">🔴 Raison du rejet:</p>
                        <p class="text-sm text-red-800">{{ $serviceRequest->rejection_reason }}</p>
                    </div>
                    @endif

                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <label class="block font-medium text-gray-600 mb-1">Créée le</label>
                            <p class="text-gray-900">{{ $serviceRequest->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div>
                            <label class="block font-medium text-gray-600 mb-1">Modifiée le</label>
                            <p class="text-gray-900">{{ $serviceRequest->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>

                    @if($serviceRequest->status === 'rejected')
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-900 mb-3">
                            <i class="ki-filled ki-information-2"></i>
                            Cette demande a été rejetée. Vous pouvez la modifier et la renvoyer au médecin chef.
                        </p>
                        <a href="{{ route('secretary.service-requests.edit', $serviceRequest->id) }}"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="ki-filled ki-pencil"></i>
                            Modifier et Renvoyer
                        </a>
                    </div>
                    @endif
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
