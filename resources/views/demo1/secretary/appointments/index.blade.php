@extends('layouts.demo1.base')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-5 pb-8">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                Planning des Rendez-vous
            </h1>
            <p class="text-sm text-gray-600 font-medium">
                Gérez les rendez-vous et planifiez les consultations
            </p>
        </div>

        @can('create', App\Models\Appointment::class)
        <a href="{{ route('appointments.create') }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 hover:shadow-md active:scale-95 transition-all">
            <i class="ki-filled ki-plus"></i>
            Nouveau Rendez-vous
        </a>
        @endcan
    </div>

    <!-- Card principal -->
    <div class="bg-white rounded-xl shadow-md">

        <!-- Messages flash -->
        @if (session('success'))
            <div class="m-4 px-4 py-3.5 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-800 rounded-lg shadow-sm">
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
            <div class="m-4 px-4 py-3.5 bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 text-red-800 rounded-lg shadow-sm">
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

        <!-- Barre de filtres -->
        <div class="p-5 bg-gray-50/50 border-b border-gray-200">
            <form method="GET" action="{{ route('appointments.index') }}" class="flex flex-col lg:flex-row gap-3 w-full">

                <!-- Recherche -->
                <div class="w-80">
                    <div class="flex items-center gap-2 px-3 py-2 border border-gray-300 rounded-lg bg-white focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 focus-within:shadow-sm transition-all">
                        <i class="ki-filled ki-magnifier text-gray-400 text-sm flex-shrink-0"></i>
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Rechercher un patient..."
                               class="flex-1 text-sm outline-none bg-transparent placeholder:text-gray-400" />
                    </div>
                </div>

                <!-- Date -->
                <input type="date"
                       name="date"
                       value="{{ request('date') }}"
                       class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white hover:border-gray-400 transition-all cursor-pointer">

                <!-- Filtre statut -->
                <select name="status"
                        class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white hover:border-gray-400 transition-all cursor-pointer">
                    <option value="">Tous les statuts</option>
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                <!-- Filtre médecin -->
                <select name="doctor_id"
                        class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white hover:border-gray-400 transition-all cursor-pointer">
                    <option value="">Tous les médecins</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                            Dr. {{ $doctor->full_name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit"
                        class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 hover:shadow-md active:scale-95 transition-all">
                    <i class="ki-filled ki-magnifier text-sm"></i>
                    Filtrer
                </button>

                @if(request('search') || request('date') || request('status') || request('doctor_id'))
                <a href="{{ route('appointments.index') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 hover:shadow-sm active:scale-95 transition-all">
                    <i class="ki-filled ki-setting-4 text-sm"></i>
                    Réinitialiser
                </a>
                @endif
            </form>
        </div>

        <!-- Tableau -->
        <div class="overflow-x-auto overflow-y-visible">
            <table class="w-full">
                <thead>
                    <tr class="border-b" style="border-color: #f0f0f0;">
                        <th class="px-4 py-4 text-left font-medium text-gray-600 text-xs uppercase tracking-wide">
                            Date & Heure
                        </th>
                        <th class="px-4 py-4 text-left font-medium text-gray-600 text-xs uppercase tracking-wide">
                            Patient
                        </th>
                        <th class="px-4 py-4 text-left font-medium text-gray-600 text-xs uppercase tracking-wide">
                            Médecin
                        </th>
                        <th class="px-4 py-4 text-left font-medium text-gray-600 text-xs uppercase tracking-wide">
                            Type
                        </th>
                        <th class="px-4 py-4 text-left font-medium text-gray-600 text-xs uppercase tracking-wide">
                            Statut
                        </th>
                        <th class="w-20 px-4 py-4 text-center font-medium text-gray-600 text-xs uppercase tracking-wide">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($appointments as $appointment)
                    <tr class="border-b hover:bg-gray-50/50 transition-colors" style="border-color: #f5f5f5;">
                        <td class="px-4 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-900">
                                    {{ $appointment->formatted_date }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ $appointment->formatted_time }} ({{ $appointment->duration }}min)
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $appointment->patient->avatar_url }}"
                                     alt="{{ $appointment->patient->full_name }}"
                                     class="w-9 h-9 rounded-full object-cover">
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ $appointment->patient->full_name }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ $appointment->patient->phone_number }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            @if($appointment->doctor)
                            <span class="text-sm text-gray-700">
                                Dr. {{ $appointment->doctor->full_name }}
                            </span>
                            @else
                            <span class="text-sm text-gray-400 italic">Non assigné</span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            @php
                            $typeColors = [
                                'consultation' => 'background-color: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe;',
                                'followup' => 'background-color: #e9d5ff; color: #6b21a8; border: 1px solid #d8b4fe;',
                                'exam' => 'background-color: #cffafe; color: #0e7490; border: 1px solid #a5f3fc;',
                                'emergency' => 'background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca;',
                                'vaccination' => 'background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0;',
                                'home_visit' => 'background-color: #fed7aa; color: #9a3412; border: 1px solid #fdba74;',
                                'telehealth' => 'background-color: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe;',
                            ];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium"
                                  style="{{ $typeColors[$appointment->type] ?? 'background-color: #f3f4f6; color: #374151; border: 1px solid #e5e7eb;' }}">
                                {{ $appointment->type_label }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            @php
                            $statusColors = [
                                'scheduled' => 'background-color: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe;',
                                'confirmed' => 'background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0;',
                                'in_progress' => 'background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a;',
                                'completed' => 'background-color: #f3f4f6; color: #374151; border: 1px solid #e5e7eb;',
                                'cancelled' => 'background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca;',
                                'no_show' => 'background-color: #fed7aa; color: #9a3412; border: 1px solid #fdba74;',
                            ];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium"
                                  style="{{ $statusColors[$appointment->status] ?? 'background-color: #f3f4f6; color: #374151; border: 1px solid #e5e7eb;' }}">
                                <span class="w-1.5 h-1.5 rounded-full"
                                      style="background-color: {{ $appointment->status === 'confirmed' ? '#059669' : ($appointment->status === 'cancelled' ? '#dc2626' : '#6b7280') }};"></span>
                                {{ $appointment->status_label }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <div class="relative inline-block" x-data="{ open: false }">
                                <button @click="open = !open" type="button"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg hover:bg-gray-100 transition-colors">
                                    <i class="ki-filled ki-dots-vertical text-lg text-gray-500"></i>
                                </button>

                                <div x-show="open" @click.away="open = false"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     style="display: none;"
                                     class="absolute right-0 top-full mt-1 w-48 bg-white rounded-lg shadow-xl border border-gray-200 py-1 z-[100]">

                                    <!-- Voir -->
                                    @can('view', $appointment)
                                        <a href="{{ route('appointments.show', $appointment) }}"
                                           class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                            <i class="ki-filled ki-eye text-gray-400 text-base"></i>
                                            Voir
                                        </a>
                                    @endcan

                                    @can('update', $appointment)
                                        <!-- Modifier (uniquement si prévu ou confirmé) -->
                                        @if($appointment->canBeModified())
                                            <a href="{{ route('appointments.edit', $appointment) }}"
                                               class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                                <i class="ki-filled ki-pencil text-gray-400 text-base"></i>
                                                Modifier
                                            </a>
                                        @endif

                                        <!-- Confirmer -->
                                        @if($appointment->canBeConfirmed())
                                            <button type="button"
                                                    class="flex items-center gap-3 px-4 py-2 text-sm text-green-600 hover:bg-green-50 w-full text-left transition-colors btn-confirm-appointment"
                                                    data-appointment-id="{{ $appointment->id }}">
                                                <i class="ki-filled ki-check text-green-500 text-base"></i>
                                                Confirmer
                                            </button>
                                        @endif

                                        <!-- Annuler -->
                                        @if($appointment->canBeCancelled())
                                            <button type="button"
                                                    class="flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 w-full text-left transition-colors btn-cancel-appointment"
                                                    data-appointment-id="{{ $appointment->id }}">
                                                <i class="ki-filled ki-cross text-red-500 text-base"></i>
                                                Annuler
                                            </button>
                                        @endif
                                    @endcan
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                    <i class="ki-filled ki-calendar text-3xl text-gray-400"></i>
                                </div>
                                <p class="text-gray-700 font-medium">Aucun rendez-vous trouvé</p>
                                @if(request()->hasAny(['search', 'date', 'status', 'doctor_id']))
                                <p class="text-gray-500 text-sm mt-1">Essayez de modifier vos filtres</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($appointments->hasPages())
        <div class="px-5 py-4 bg-gray-50/50 border-t border-gray-200 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-sm text-gray-600 font-medium">
                Affichage de <span class="font-bold text-gray-900">{{ $appointments->firstItem() }}</span> à <span class="font-bold text-gray-900">{{ $appointments->lastItem() }}</span> sur <span class="font-bold text-gray-900">{{ $appointments->total() }}</span> rendez-vous
            </div>
            <div>
                {{ $appointments->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/appointments.js') }}"></script>
@endpush
