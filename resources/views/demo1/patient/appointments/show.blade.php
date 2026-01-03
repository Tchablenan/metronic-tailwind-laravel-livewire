@extends('layouts.demo1.base')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center gap-4 pb-8">
        <a href="{{ route('appointments.index') }}"
           class="inline-flex items-center justify-center w-10 h-10 rounded-lg hover:bg-gray-100 transition-colors">
            <i class="ki-filled ki-left text-xl text-gray-600"></i>
        </a>
        <div class="flex flex-col gap-1">
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                Détails du Rendez-vous
            </h1>
            <p class="text-sm text-gray-600">
                {{ $appointment->full_datetime }}
            </p>
        </div>
    </div>

    <!-- Messages flash -->
    @if (session('success'))
        <div class="mb-6 px-4 py-3.5 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-800 rounded-lg shadow-sm">
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
        <div class="mb-6 px-4 py-3.5 bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 text-red-800 rounded-lg shadow-sm">
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
        <div class="lg:col-span-2 space-y-6">

            <!-- Informations du rendez-vous -->
            <div class="bg-white rounded-xl shadow-md">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100">
                    <h2 class="text-lg font-semibold text-gray-900">Informations du rendez-vous</h2>
                </div>

                <div class="p-6">
                    <!-- Date et Heure -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Date</label>
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="ki-filled ki-calendar text-blue-600 text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-base font-semibold text-gray-900">{{ $appointment->formatted_date }}</p>
                                    <p class="text-xs text-gray-500">{{ $appointment->appointment_date->translatedFormat('l') }}</p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Heure</label>
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="ki-filled ki-time text-purple-600 text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-base font-semibold text-gray-900">{{ $appointment->formatted_time }}</p>
                                    <p class="text-xs text-gray-500">Durée : {{ $appointment->duration }} minutes</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-6 mb-6"></div>

                    <!-- Type et Lieu -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Type de consultation</label>
                            @php
                            $typeColors = [
                                'consultation' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'followup' => 'bg-purple-50 text-purple-700 border-purple-200',
                                'exam' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                                'emergency' => 'bg-red-50 text-red-700 border-red-200',
                                'vaccination' => 'bg-green-50 text-green-700 border-green-200',
                                'home_visit' => 'bg-orange-50 text-orange-700 border-orange-200',
                                'telehealth' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                            ];
                            @endphp
                            <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold border {{ $typeColors[$appointment->type] ?? 'bg-gray-50 text-gray-700 border-gray-200' }}">
                                {{ $appointment->type_label }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Lieu</label>
                            <div class="flex items-center gap-2">
                                <i class="ki-filled ki-geolocation text-gray-400 text-lg"></i>
                                <p class="text-base font-semibold text-gray-900">{{ $appointment->location_label }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-6 mb-6"></div>

                    <!-- Statut -->
                    <div class="mb-6">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Statut</label>
                        @php
                        $statusColors = [
                            'scheduled' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'confirmed' => 'bg-green-50 text-green-700 border-green-200',
                            'in_progress' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                            'completed' => 'bg-gray-50 text-gray-700 border-gray-200',
                            'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                            'no_show' => 'bg-orange-50 text-orange-700 border-orange-200',
                        ];
                        @endphp
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold border {{ $statusColors[$appointment->status] ?? 'bg-gray-50 text-gray-700 border-gray-200' }}">
                            <span class="w-2 h-2 rounded-full bg-current"></span>
                            {{ $appointment->status_label }}
                        </span>
                    </div>

                    <!-- Prix -->
                    @if($appointment->price)
                    <div class="border-t border-gray-100 pt-6 mb-6">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Coût de la consultation</label>
                        <p class="text-3xl font-bold text-blue-600">{{ number_format($appointment->price, 0, ',', ' ') }} FCFA</p>
                    </div>
                    @endif

                    <!-- Urgence -->
                    @if($appointment->is_emergency)
                    <div class="mb-6">
                        <div class="flex items-center gap-3 px-4 py-3 bg-red-50 border-l-4 border-red-500 rounded-lg">
                            <i class="ki-filled ki-information text-red-600 text-xl"></i>
                            <span class="text-sm font-semibold text-red-800">Rendez-vous marqué comme urgent</span>
                        </div>
                    </div>
                    @endif

                    <!-- Motif -->
                    @if($appointment->reason)
                    <div class="border-t border-gray-100 pt-6 mb-6">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Votre motif de consultation</label>
                        <p class="text-sm text-gray-700 leading-relaxed bg-gray-50 p-4 rounded-lg border border-gray-200">{{ $appointment->reason }}</p>
                    </div>
                    @endif

                    <!-- Remarques pour le patient -->
                    @if($appointment->patient_notes)
                    <div class="border-t border-gray-100 pt-6 mb-6">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Remarques importantes</label>
                        <div class="px-4 py-3 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                            <div class="flex items-start gap-3">
                                <i class="ki-filled ki-information text-blue-600 text-lg mt-0.5"></i>
                                <p class="text-sm text-blue-900 leading-relaxed">{{ $appointment->patient_notes }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Annulation -->
                    @if($appointment->status === 'cancelled' && $appointment->cancellation_reason)
                    <div class="border-t border-gray-100 pt-6">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Raison de l'annulation</label>
                        <div class="px-4 py-3 bg-red-50 rounded-lg border border-red-200">
                            <p class="text-sm text-red-900 leading-relaxed mb-2">{{ $appointment->cancellation_reason }}</p>
                            <p class="text-xs text-red-600">Annulé le {{ $appointment->cancelled_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- Colonne latérale -->
        <div class="space-y-6">

            <!-- Actions rapides -->
            <div class="bg-white rounded-xl shadow-md">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    @if($appointment->canBeCancelled())
                    <button type="button"
                            class="flex items-center gap-3 w-full px-5 py-3.5 text-sm font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition-colors btn-cancel-appointment"
                            data-appointment-id="{{ $appointment->id }}">
                        <i class="ki-filled ki-cross text-lg"></i>
                        Annuler le rendez-vous
                    </button>
                    @endif

                    <a href="{{ route('appointments.index') }}"
                       class="flex items-center gap-3 w-full px-5 py-3.5 text-sm font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="ki-filled ki-left text-lg"></i>
                        Retour à mes RDV
                    </a>

                    @if($appointment->doctor)
                    <a href="tel:{{ $appointment->doctor->phone_number }}"
                       class="flex items-center gap-3 w-full px-5 py-3.5 text-sm font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                        <i class="ki-filled ki-phone text-lg"></i>
                        Appeler le médecin
                    </a>
                    @endif
                </div>
            </div>

            <!-- Médecin assigné -->
            @if($appointment->doctor)
            <div class="bg-white rounded-xl shadow-md">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Votre médecin</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-4 mb-5">
                        <img src="{{ $appointment->doctor->avatar_url }}"
                             alt="{{ $appointment->doctor->full_name }}"
                             class="w-16 h-16 rounded-full object-cover ring-4 ring-blue-50">
                        <div>
                            <h4 class="font-semibold text-gray-900 text-base mb-1">Dr. {{ $appointment->doctor->full_name }}</h4>
                            @if($appointment->doctor->speciality)
                            <p class="text-sm text-gray-500">{{ $appointment->doctor->speciality }}</p>
                            @else
                            <p class="text-sm text-gray-500">Médecine Générale</p>
                            @endif
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 text-sm">
                            <i class="ki-filled ki-sms text-gray-400 text-lg"></i>
                            <span class="text-gray-700 break-all">{{ $appointment->doctor->email }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <i class="ki-filled ki-phone text-gray-400 text-lg"></i>
                            <a href="tel:{{ $appointment->doctor->phone_number }}" class="text-blue-600 hover:underline">
                                {{ $appointment->doctor->phone_number }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Infirmier si assigné -->
            @if($appointment->nurse)
            <div class="bg-white rounded-xl shadow-md">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Infirmier(ère) assigné(e)</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-4">
                        <img src="{{ $appointment->nurse->avatar_url }}"
                             alt="{{ $appointment->nurse->full_name }}"
                             class="w-14 h-14 rounded-full object-cover ring-4 ring-purple-50">
                        <div>
                            <h4 class="font-semibold text-gray-900 text-base mb-1">{{ $appointment->nurse->full_name }}</h4>
                            <p class="text-sm text-gray-500">Infirmier(ère)</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Besoin d'aide -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-md p-6 border-2 border-blue-200">
                <div class="flex items-start gap-3 mb-4">
                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="ki-filled ki-information text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Besoin d'aide ?</h3>
                        <p class="text-sm text-gray-700">Contactez notre service patient pour toute question.</p>
                    </div>
                </div>
                <a href="tel:+22500000000" class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                    <i class="ki-filled ki-phone"></i>
                    Appeler le +225 00 00 00 00
                </a>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/appointments.js') }}"></script>
@endpush
