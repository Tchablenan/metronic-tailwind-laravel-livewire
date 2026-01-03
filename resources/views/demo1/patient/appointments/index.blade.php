@extends('layouts.demo1.base')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-5 pb-8">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                Mes Rendez-vous
            </h1>
            <p class="text-sm text-gray-600 font-medium">
                Consultez vos rendez-vous passés et à venir
            </p>
        </div>

        <a href="{{ route('appointments.create') }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 hover:shadow-md active:scale-95 transition-all">
            <i class="ki-filled ki-plus"></i>
            Demander un rendez-vous
        </a>
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

    <!-- Filtres rapides - 4 par ligne -->
    <div class="flex flex-wrap gap-4 mb-8">
        <!-- Tous -->
        <a href="{{ route('appointments.index') }}"
           class="flex-1 min-w-[220px] p-5 bg-white rounded-xl shadow-md {{ !request('status') ? 'ring-2 ring-blue-300 bg-blue-50' : 'hover:shadow-lg' }} transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Tous</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $appointments->total() }}</p>
                </div>
                <div class="w-14 h-14 rounded-full flex items-center justify-center" style="background-color: #DBEAFE;">
                    <i class="ki-filled ki-calendar text-3xl" style="color: #2563EB;"></i>
                </div>
            </div>
        </a>

        <!-- Confirmés -->
        <a href="{{ route('appointments.index', ['status' => 'confirmed']) }}"
           class="flex-1 min-w-[220px] p-5 bg-white rounded-xl shadow-md {{ request('status') === 'confirmed' ? 'ring-2 ring-green-200 bg-green-50' : 'hover:shadow-lg' }} transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Confirmés</p>
                    <p class="text-3xl font-bold text-gray-900">
                        {{ \App\Models\Appointment::where('patient_id', auth()->id())->where('status', 'confirmed')->count() }}
                    </p>
                </div>
                <div class="w-14 h-14 rounded-full flex items-center justify-center" style="background-color: #D1FAE5;">
                    <i class="ki-filled ki-check-circle text-3xl" style="color: #059669;"></i>
                </div>
            </div>
        </a>

        <!-- En attente -->
        <a href="{{ route('appointments.index', ['status' => 'scheduled']) }}"
           class="flex-1 min-w-[220px] p-5 bg-white rounded-xl shadow-md {{ request('status') === 'scheduled' ? 'ring-2 ring-border bg-orange-200' : 'hover:shadow-lg' }} transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">En attente</p>
                    <p class="text-3xl font-bold text-gray-900">
                        {{ \App\Models\Appointment::where('patient_id', auth()->id())->where('status', 'scheduled')->count() }}
                    </p>
                </div>
                <div class="w-14 h-14 rounded-full flex items-center justify-center" style="background-color: #FED7AA;">
                    <i class="ki-filled ki-time text-3xl" style="color: #EA580C;"></i>
                </div>
            </div>
        </a>

        <!-- Terminés - CORRIGÉ: purple → violet -->
        <a href="{{ route('appointments.index', ['status' => 'completed']) }}"
           class="flex-1 min-w-[220px] p-5 bg-white rounded-xl shadow-md {{ request('status') === 'completed' ? 'ring-2 ring-violet-200 bg-violet-50' : 'hover:shadow-lg' }} transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Terminés</p>
                    <p class="text-3xl font-bold text-gray-900">
                        {{ \App\Models\Appointment::where('patient_id', auth()->id())->where('status', 'completed')->count() }}
                    </p>
                </div>
                <div class="w-14 h-14 rounded-full flex items-center justify-center" style="background-color: #EDE9FE;">
                    <i class="ki-filled ki-verify text-3xl" style="color: #7C3AED;"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- Liste des rendez-vous -->
    <div class="space-y-4">
        @forelse($appointments as $appointment)
        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            <div class="p-7">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                    <!-- Informations principales -->
                    <div class="flex-1">
                        <div class="flex items-start gap-4">
                            <!-- Date -->
                            <div class="flex-shrink-0 text-center">
                                <div class="w-16 h-16 bg-blue-50 rounded-lg flex flex-col items-center justify-center">
                                    <span class="text-xs text-blue-600 font-medium uppercase">
                                        {{ $appointment->appointment_date->format('M') }}
                                    </span>
                                    <span class="text-2xl font-bold text-blue-600">
                                        {{ $appointment->appointment_date->format('d') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Détails -->
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $appointment->type_label }}
                                    </h3>
                                    @php
                                    $statusColors = [
                                        'scheduled' => 'bg-blue-50 text-blue-700 border-blue-300',
                                        'confirmed' => 'bg-green-50 text-green-700 border-green-300',
                                        'in_progress' => 'bg-yellow-50 text-yellow-700 border-yellow-300',
                                        'completed' => 'bg-gray-50 text-gray-700 border-gray-300',
                                        'cancelled' => 'bg-red-50 text-red-700 border-red-300',
                                        'no_show' => 'bg-orange-50 text-orange-700 border-orange-300',
                                    ];
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium border {{ $statusColors[$appointment->status] ?? $statusColors['scheduled'] }}">
                                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                        {{ $appointment->status_label }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                    <div class="flex items-center gap-2 text-gray-600">
                                        <i class="ki-filled ki-time text-gray-400"></i>
                                        <span>{{ $appointment->formatted_time }} ({{ $appointment->duration }}min)</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-gray-600">
                                        <i class="ki-filled ki-geolocation text-gray-400"></i>
                                        <span>{{ $appointment->location_label }}</span>
                                    </div>
                                </div>

                                @if($appointment->doctor)
                                <div class="flex items-center gap-2 mt-3">
                                    <img src="{{ $appointment->doctor->avatar_url }}"
                                         alt="Dr. {{ $appointment->doctor->full_name }}"
                                         class="w-8 h-8 rounded-full object-cover">
                                    <span class="text-sm text-gray-700">
                                        Dr. {{ $appointment->doctor->full_name }}
                                        @if($appointment->doctor->speciality)
                                            <span class="text-gray-500">• {{ $appointment->doctor->speciality }}</span>
                                        @endif
                                    </span>
                                </div>
                                @endif

                                @if($appointment->is_emergency)
                                <div class="flex items-center gap-2 mt-3 px-3 py-1.5 bg-red-50 border border-red-300 rounded-lg w-fit">
                                    <i class="ki-filled ki-information text-red-600 text-sm"></i>
                                    <span class="text-xs font-medium text-red-800">Rendez-vous urgent</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('appointments.show', $appointment) }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <i class="ki-filled ki-eye text-sm"></i>
                            Voir
                        </a>

                        @if($appointment->canBeCancelled() && $appointment->patient_id === auth()->id())
                        <button type="button"
                                class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition-colors btn-cancel-appointment"
                                data-appointment-id="{{ $appointment->id }}">
                            <i class="ki-filled ki-cross text-sm"></i>
                            Annuler
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Motif si présent -->
                @if($appointment->reason)
                <div class="mt-4 pt-4 border-t border-gray-300">
                    <p class="text-sm text-gray-600">
                        <span class="font-medium text-gray-700">Motif :</span> {{ $appointment->reason }}
                    </p>
                </div>
                @endif

                <!-- Remarques pour le patient -->
                @if($appointment->patient_notes)
                <div class="mt-3 px-4 py-3 bg-blue-50 border border-blue-300 rounded-lg">
                    <div class="flex items-start gap-2">
                        <i class="ki-filled ki-information text-blue-600 mt-0.5"></i>
                        <div>
                            <p class="text-xs font-semibold text-blue-800 uppercase mb-1">Remarques importantes</p>
                            <p class="text-sm text-blue-900">{{ $appointment->patient_notes }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-md p-1">
            <div class="flex flex-col items-center text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="ki-filled ki-calendar text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucun rendez-vous trouvé</h3>
                <p class="text-gray-600 mb-6">Vous n'avez pas encore de rendez-vous planifié.</p>
                <a href="{{ route('appointments.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="ki-filled ki-plus"></i>
                    Demander un rendez-vous
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($appointments->hasPages())
    <div class="mt-6 flex items-center justify-between">
        <div class="text-sm text-gray-600 font-medium">
            Affichage de <span class="font-bold text-gray-900">{{ $appointments->firstItem() }}</span>
            à <span class="font-bold text-gray-900">{{ $appointments->lastItem() }}</span>
            sur <span class="font-bold text-gray-900">{{ $appointments->total() }}</span> rendez-vous
        </div>
        <div>
            {{ $appointments->links() }}
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/appointments.js') }}"></script>
@endpush
