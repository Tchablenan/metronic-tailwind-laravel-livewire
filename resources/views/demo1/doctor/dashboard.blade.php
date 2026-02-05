@extends('layouts.demo1.base')

@section('content')

<div class="container mx-auto px-4 py-8">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Mon Tableau de Bord</h1>
        <p class="text-gray-600 mt-2">Bonjour Dr. {{ Auth::user()->full_name }}</p>
        <p class="text-sm text-gray-500">{{ now()->format('d F Y') }}</p>
    </div>

    <!-- Section 1 : Cartes de Statistiques (Grid 4 colonnes) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Carte 1 : Mes RDV du jour -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">RDV aujourd'hui</p>
                    <h3 class="text-4xl font-bold text-gray-900 mt-2">{{ $myAppointmentsToday }}</h3>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg">
                    <i class="ki-filled ki-calendar text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Carte 2 : Mes consultations ce mois -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Consultations ce mois</p>
                    <h3 class="text-4xl font-bold text-gray-900 mt-2">{{ $myConsultationsThisMonth }}</h3>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <i class="ki-filled ki-stethoscope text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Carte 3 : Mes patients vus -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Patients suivis</p>
                    <h3 class="text-4xl font-bold text-gray-900 mt-2">{{ $myPatientsSeen }}</h3>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg">
                    <i class="ki-filled ki-user text-purple-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Carte 4 : Mes RDV à venir (7 jours) -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">RDV prochains 7j</p>
                    <h3 class="text-4xl font-bold text-gray-900 mt-2">{{ $myUpcomingAppointments }}</h3>
                </div>
                <div class="bg-orange-50 p-4 rounded-lg">
                    <i class="ki-filled ki-calendar-add text-orange-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 2 : Mes RDV du Jour (Tableau) -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900">
                Mes Rendez-vous d'Aujourd'hui
                <span class="ml-2 inline-block bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                    {{ $totalTodayAppointments }}
                </span>
            </h2>
        </div>

        @if($todayAppointments->isEmpty())
            <div class="text-center py-12">
                <i class="ki-filled ki-check text-green-500 text-4xl mb-4 block"></i>
                <p class="text-gray-600">✅ Aucun rendez-vous aujourd'hui</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Heure</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Patient</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Type</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Statut</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($todayAppointments as $appointment)
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $appointment->patient->full_name }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full">
                                        {{ ucfirst($appointment->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @php
                                        $statusColors = [
                                            'scheduled' => 'bg-blue-100 text-blue-800',
                                            'confirmed' => 'bg-green-100 text-green-800',
                                            'completed' => 'bg-gray-100 text-gray-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                        ];
                                        $statusColor = $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-block {{ $statusColor }} text-xs font-medium px-3 py-1 rounded-full">
                                        {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="{{ route('appointments.show', $appointment) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                        Voir
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($totalTodayAppointments > 10)
                <div class="mt-6 text-center">
                    <a href="{{ route('appointments.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                        → Voir tous mes rendez-vous ({{ $totalTodayAppointments }})
                    </a>
                </div>
            @endif
        @endif
    </div>

    <!-- Section 3 : Accès Rapides (Grid 2x2) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Bouton 1 : Mes Rendez-vous (LECTEUR SEUL pour médecin régulier) -->
        <a href="{{ route('appointments.index') }}" class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition border border-blue-200">
            <div class="flex items-start justify-between mb-4">
                <div class="bg-blue-50 p-3 rounded-lg">
                    <i class="ki-filled ki-calendar text-blue-600 text-2xl"></i>
                </div>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">Mes Rendez-vous</h3>
            <p class="text-sm text-gray-600">Consulter mon planning</p>
        </a>

        <!-- Bouton 2 : Mes Consultations -->
        <a href="#" class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition border border-green-200">
            <div class="flex items-start justify-between mb-4">
                <div class="bg-green-50 p-3 rounded-lg">
                    <i class="ki-filled ki-stethoscope text-green-600 text-2xl"></i>
                </div>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">Mes Consultations</h3>
            <p class="text-sm text-gray-600">Historique consultations</p>
        </a>

        <!-- Bouton 3 : Mon Planning (Désactivé) -->
        <div class="bg-gray-50 rounded-xl shadow-sm p-6 border border-gray-300 opacity-60 cursor-not-allowed">
            <div class="flex items-start justify-between mb-4">
                <div class="bg-gray-200 p-3 rounded-lg">
                    <i class="ki-filled ki-calendar-8 text-gray-400 text-2xl"></i>
                </div>
                <span class="inline-block bg-gray-300 text-gray-700 text-xs font-medium px-2 py-1 rounded">Bientôt</span>
            </div>
            <h3 class="text-lg font-bold text-gray-600 mb-1">Mon Planning</h3>
            <p class="text-sm text-gray-500">Vue calendrier</p>
        </div>

        <!-- Bouton 4 : Mes Patients (Désactivé pour médecin régulier) -->
        <div class="bg-gray-50 rounded-xl shadow-sm p-6 border border-gray-300 opacity-60 cursor-not-allowed">
            <div class="flex items-start justify-between mb-4">
                <div class="bg-gray-200 p-3 rounded-lg">
                    <i class="ki-filled ki-people text-gray-400 text-2xl"></i>
                </div>
                <span class="inline-block bg-gray-300 text-gray-700 text-xs font-medium px-2 py-1 rounded">Bientôt</span>
            </div>
            <h3 class="text-lg font-bold text-gray-600 mb-1">Mes Patients</h3>
            <p class="text-sm text-gray-500">Dossiers patients</p>
        </div>
    </div>
</div>
@endsection
