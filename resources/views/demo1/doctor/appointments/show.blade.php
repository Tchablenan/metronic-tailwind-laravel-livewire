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
            <div
                class="mb-6 px-4 py-3.5 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-800 rounded-lg shadow-sm">
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
            <div
                class="mb-6 px-4 py-3.5 bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 text-red-800 rounded-lg shadow-sm">
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

                <!-- Informations principales -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50/50">
                        <h2 class="text-lg font-semibold text-gray-900">Informations du rendez-vous</h2>
                    </div>

                    <div class="p-7 space-y-5">
                        <!-- Date et Heure -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Date</label>
                                <p class="text-sm font-medium text-gray-900">{{ $appointment->formatted_date }}</p>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Heure</label>
                                <p class="text-sm font-medium text-gray-900">{{ $appointment->formatted_time }}
                                    ({{ $appointment->duration }} min)</p>
                            </div>
                        </div>

                        <!-- Type et Lieu -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Type</label>
                                @php
                                    $typeColors = [
                                        'consultation' =>
                                            'background-color: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe;',
                                        'followup' =>
                                            'background-color: #e9d5ff; color: #6b21a8; border: 1px solid #d8b4fe;',
                                        'exam' =>
                                            'background-color: #cffafe; color: #0e7490; border: 1px solid #a5f3fc;',
                                        'emergency' =>
                                            'background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca;',
                                        'vaccination' =>
                                            'background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0;',
                                        'home_visit' =>
                                            'background-color: #fed7aa; color: #9a3412; border: 1px solid #fdba74;',
                                        'telehealth' =>
                                            'background-color: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe;',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium"
                                    style="{{ $typeColors[$appointment->type] ?? 'background-color: #f3f4f6; color: #374151; border: 1px solid #e5e7eb;' }}">
                                    {{ $appointment->type_label }}
                                </span>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Lieu</label>
                                <p class="text-sm font-medium text-gray-900">{{ $appointment->location_label }}</p>
                            </div>
                        </div>

                        <!-- Statut -->
                        <div>
                            <label
                                class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Statut</label>
                            @php
                                $statusColors = [
                                    'scheduled' =>
                                        'background-color: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe;',
                                    'confirmed' =>
                                        'background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0;',
                                    'in_progress' =>
                                        'background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a;',
                                    'completed' =>
                                        'background-color: #f3f4f6; color: #374151; border: 1px solid #e5e7eb;',
                                    'cancelled' =>
                                        'background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca;',
                                    'no_show' =>
                                        'background-color: #fed7aa; color: #9a3412; border: 1px solid #fdba74;',
                                ];
                            @endphp
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm font-medium"
                                style="{{ $statusColors[$appointment->status] ?? 'background-color: #f3f4f6; color: #374151; border: 1px solid #e5e7eb;' }}">
                                <span class="w-2 h-2 rounded-full"
                                    style="background-color: {{ $appointment->status === 'confirmed' ? '#059669' : ($appointment->status === 'cancelled' ? '#dc2626' : '#6b7280') }};"></span>
                                {{ $appointment->status_label }}
                            </span>
                        </div>

                        <!-- Prix -->
                        @if ($appointment->price)
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Prix</label>
                                <p class="text-lg font-bold text-gray-900">
                                    {{ number_format($appointment->price, 0, ',', ' ') }} FCFA</p>
                            </div>
                        @endif

                        <!-- Urgence -->
                        @if ($appointment->is_emergency)
                            <div class="flex items-center gap-2 px-4 py-3 bg-red-50 border border-red-200 rounded-lg">
                                <i class="ki-filled ki-information text-red-600 text-lg"></i>
                                <span class="text-sm font-medium text-red-800">Rendez-vous urgent</span>
                            </div>
                        @endif

                        <!-- Motif -->
                        @if ($appointment->reason)
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Motif de
                                    consultation</label>
                                <p class="text-sm text-gray-700 leading-relaxed">{{ $appointment->reason }}</p>
                            </div>
                        @endif

                        <!-- Notes internes -->
                        @if ($appointment->notes && in_array(auth()->user()->role, ['doctor', 'nurse', 'secretary']))
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Notes
                                    internes</label>
                                <div class="px-4 py-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <p class="text-sm text-gray-700 leading-relaxed">{{ $appointment->notes }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Notes pour le patient -->
                        @if ($appointment->patient_notes)
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Remarques
                                    pour le patient</label>
                                <div class="px-4 py-3 bg-blue-50 rounded-lg border border-blue-200">
                                    <p class="text-sm text-blue-900 leading-relaxed">{{ $appointment->patient_notes }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Annulation -->
                        @if ($appointment->status === 'cancelled' && $appointment->cancellation_reason)
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Raison
                                    de l'annulation</label>
                                <div class="px-4 py-3 bg-red-50 rounded-lg border border-red-200">
                                    <p class="text-sm text-red-900 leading-relaxed">{{ $appointment->cancellation_reason }}
                                    </p>
                                    <p class="text-xs text-red-600 mt-2">Annulé par:
                                        {{ ucfirst($appointment->cancelled_by) }} le
                                        {{ $appointment->cancelled_at->format('d/m/Y à H:i') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Colonne latérale -->
            <div class="space-y-5">

                <!-- Actions rapides -->
                @can('update', $appointment)
                    <div class="bg-white rounded-xl shadow-md">
                        <div class="px-6 py-5 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Actions</h3>
                        </div>
                        <div class="p-6 space-y-3">
                            @if ($appointment->canBeModified() || auth()->user()->role === 'doctor')
                                <a href="{{ route('appointments.edit', $appointment) }}"
                                    class="flex items-center gap-3 w-full px-5 py-3.5 text-sm font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                    <i class="ki-filled ki-pencil text-lg"></i>
                                    Modifier
                                </a>
                            @endif

                            @if ($appointment->canBeConfirmed())
                                <button type="button"
                                    class="flex items-center gap-3 w-full px-5 py-3.5 text-sm font-medium text-green-700 bg-green-50 rounded-lg hover:bg-green-100 transition-colors btn-confirm-appointment"
                                    data-appointment-id="{{ $appointment->id }}">
                                    <i class="ki-filled ki-check text-lg"></i>
                                    Confirmer
                                </button>
                            @endif

                            @if ($appointment->status === 'confirmed' && in_array(auth()->user()->role, ['doctor', 'nurse']))
                                <button type="button"
                                    class="flex items-center gap-3 w-full px-5 py-3.5 text-sm font-medium text-orange-700 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors btn-start-appointment"
                                    data-appointment-id="{{ $appointment->id }}">
                                    <i class="ki-filled ki-rocket text-lg"></i>
                                    Démarrer
                                </button>
                            @endif

                            @if ($appointment->status === 'in_progress' && in_array(auth()->user()->role, ['doctor', 'nurse']))
                                <button type="button"
                                    class="flex items-center gap-3 w-full px-5 py-3.5 text-sm font-medium text-purple-700 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors btn-complete-appointment"
                                    data-appointment-id="{{ $appointment->id }}">
                                    <i class="ki-filled ki-check-circle text-lg"></i>
                                    Terminer
                                </button>
                            @endif

                            @if ($appointment->canBeCancelled())
                                <button type="button"
                                    class="flex items-center gap-3 w-full px-5 py-3.5 text-sm font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition-colors btn-cancel-appointment"
                                    data-appointment-id="{{ $appointment->id }}">
                                    <i class="ki-filled ki-cross text-lg"></i>
                                    Annuler
                                </button>
                            @endif
                        </div>
                    </div>
                @endcan

                <!-- Patient -->
                <div class="bg-white rounded-xl shadow-md">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Patient</h3>
                    </div>
                    <div class="p-7 space-y-5">
                        <div class="flex items-center gap-4">
                            <img src="{{ $appointment->patient->avatar_url }}"
                                alt="{{ $appointment->patient->full_name }}"
                                class="w-16 h-16 rounded-full object-cover ring-2 ring-gray-100">
                            <div>
                                <h4 class="font-semibold text-gray-900 text-base mb-1">
                                    {{ $appointment->patient->full_name }}</h4>
                                <p class="text-sm text-gray-500">Patient</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center gap-3 text-sm">
                                <i class="ki-filled ki-sms text-gray-400 text-lg"></i>
                                <span class="text-gray-700 break-all">{{ $appointment->patient->email }}</span>
                            </div>
                            <div class="flex items-center gap-3 text-sm">
                                <i class="ki-filled ki-phone text-gray-400 text-lg"></i>
                                <span class="text-gray-700">{{ $appointment->patient->phone_number }}</span>
                            </div>
                        </div>
                        @can('view', $appointment->patient)
                            <div class="pt-4 ">
                                <a href="{{ route('users.show', $appointment->patient) }}"
                                    class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-700">
                                    Voir le profil
                                    <i class="ki-filled ki-right text-xs"></i>
                                </a>
                            </div>
                        @endcan
                    </div>
                </div>

                <!-- Médecin -->
                @if ($appointment->doctor)
                    <div class="bg-white rounded-xl shadow-md">
                        <div class="px-6 py-5 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Médecin</h3>
                        </div>
                        <div class="p-7 space-y-5">
                            <div class="flex items-center gap-4">
                                <img src="{{ $appointment->doctor->avatar_url }}"
                                    alt="{{ $appointment->doctor->full_name }}"
                                    class="w-16 h-16 rounded-full object-cover ring-2 ring-gray-100">
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-base mb-1">Dr.
                                        {{ $appointment->doctor->full_name }}</h4>
                                    @if ($appointment->doctor->speciality)
                                        <p class="text-sm text-gray-500">{{ $appointment->doctor->speciality }}</p>
                                    @else
                                        <p class="text-sm text-gray-500">Médecine Générale</p>
                                    @endif
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="flex items-center gap-3 text-sm">
                                    <i class="ki-filled ki-sms text-gray-400 text-lg"></i>
                                    <span class="text-gray-700 break-all">{{ $appointment->doctor->email }}</span>
                                </div>
                                <div class="flex items-center gap-3 text-sm">
                                    <i class="ki-filled ki-phone text-gray-400 text-lg"></i>
                                    <span class="text-gray-700">{{ $appointment->doctor->phone_number }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Infirmier -->
                @if ($appointment->nurse)
                    <div class="bg-white rounded-xl shadow-md">
                        <div class="px-7 py-5 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Infirmier(ère)</h3>
                        </div>
                        <div class="p-7">
                            <div class="flex items-center gap-4">
                                <img src="{{ $appointment->nurse->avatar_url }}"
                                    alt="{{ $appointment->nurse->full_name }}"
                                    class="w-16 h-16 rounded-full object-cover ring-2 ring-gray-100">
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-base mb-1">
                                        {{ $appointment->nurse->full_name }}</h4>
                                    <p class="text-sm text-gray-500">Infirmier(ère)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Supprimer -->
                @can('delete', $appointment)
                    <div class="bg-white rounded-xl shadow-md ">
                        <div class="px-7 py-5 " style="background-color: #fef2f2;">
                            <h3 class="text-lg font-semibold" style="color: #991b1b;">Zone dangereuse</h3>
                        </div>
                        <div class="p-7">
                            <p class="text-sm text-gray-600 mb-5 leading-relaxed">Une fois supprimé, ce rendez-vous ne peut pas
                                être récupéré.</p>
                            <form action="{{ route('appointments.destroy', $appointment) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="flex items-center gap-3 w-full px-5 py-3.5 text-sm font-medium text-white rounded-lg hover:shadow-lg transition-all btn-delete-appointment"
                                    style="background-color: #dc2626;" onmouseover="this.style.backgroundColor='#b91c1c'"
                                    onmouseout="this.style.backgroundColor='#dc2626'"
                                    data-appointment-id="{{ $appointment->id }}">
                                    <i class="ki-filled ki-trash text-lg"></i>
                                    Supprimer définitivement
                                </button>
                            </form>
                        </div>
                    </div>
                @endcan

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/appointments.js') }}"></script>
@endpush
