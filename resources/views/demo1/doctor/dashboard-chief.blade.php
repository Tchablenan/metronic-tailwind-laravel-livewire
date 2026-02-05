@extends('layouts.demo1.base')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- En-tête -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <h1 class="text-3xl font-bold text-gray-900">Tableau de Bord Directeur</h1>
                <span class="inline-block bg-purple-100 text-purple-800 text-sm font-medium px-4 py-2 rounded-full">
                    <i class="ki-filled ki-crown text-purple-600 mr-1"></i> Médecin Chef
                </span>
            </div>
            <p class="text-gray-600">Bonjour Dr. {{ Auth::user()->full_name }}</p>
            <p class="text-sm text-gray-500">{{ now()->format('d F Y') }}</p>
        </div>
    </div>

    <!-- Section 1 : Statistiques Globales (Grid 3x2 = 6 cartes) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Carte 1 : RDV aujourd'hui (TOUS) -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">RDV aujourd'hui</p>
                    <h3 class="text-4xl font-bold text-gray-900 mt-2">{{ $allAppointmentsToday }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Tous médecins</p>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg">
                    <i class="ki-filled ki-calendar-tick text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Carte 2 : Consultations ce mois -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Consultations ce mois</p>
                    <h3 class="text-4xl font-bold text-gray-900 mt-2">{{ $allConsultationsThisMonth }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Toutes consultations</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <i class="ki-filled ki-hospital text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Carte 3 : Demandes en attente -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Demandes en attente</p>
                    <h3 class="text-4xl font-bold text-gray-900 mt-2">{{ $pendingRequests }}</h3>
                    <p class="text-xs text-gray-500 mt-1">À traiter</p>
                </div>
                <div class="bg-orange-50 p-4 rounded-lg">
                    <i class="ki-filled ki-notepad text-orange-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Carte 4 : Patients total -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Patients enregistrés</p>
                    <h3 class="text-4xl font-bold text-gray-900 mt-2">{{ $totalPatients }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Base patients</p>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg">
                    <i class="ki-filled ki-profile-user text-purple-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Carte 5 : Médecins actifs -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-cyan-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Médecins actifs</p>
                    <h3 class="text-4xl font-bold text-gray-900 mt-2">{{ $activeDoctors }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Équipe médicale</p>
                </div>
                <div class="bg-cyan-50 p-4 rounded-lg">
                    <i class="ki-filled ki-user-tick text-cyan-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Carte 6 : Taux de complétion -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 {{ $completionRate >= 80 ? 'border-green-500' : ($completionRate >= 60 ? 'border-orange-500' : 'border-red-500') }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Taux de complétion</p>
                    <h3 class="text-4xl font-bold text-gray-900 mt-2">{{ number_format($completionRate, 1) }}%</h3>
                    <p class="text-xs {{ $completionRate >= 80 ? 'text-green-600' : ($completionRate >= 60 ? 'text-orange-600' : 'text-red-600') }} mt-1">
                        {{ $completionRate >= 80 ? '✅ Excellent' : ($completionRate >= 60 ? '⚠️ Bon' : '❌ À améliorer') }}
                    </p>
                </div>
                <div class="{{ $completionRate >= 80 ? 'bg-green-50' : ($completionRate >= 60 ? 'bg-orange-50' : 'bg-red-50') }} p-4 rounded-lg">
                    <i class="ki-filled ki-chart-line {{ $completionRate >= 80 ? 'text-green-600' : ($completionRate >= 60 ? 'text-orange-600' : 'text-red-600') }} text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 2 : RDV du Jour (Tous médecins) -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900">
                Rendez-vous d'Aujourd'hui (Tous médecins)
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
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Médecin</th>
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
                                <td class="px-6 py-4 text-sm text-gray-700 font-medium">
                                    Dr. {{ $appointment->doctor->last_name }}
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
                        → Voir tous les rendez-vous ({{ $totalTodayAppointments }})
                    </a>
                </div>
            @endif
        @endif
    </div>

    <!-- Section 3 : Performance par Médecin -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Performance des Médecins</h2>

        @if($doctorPerformance->isEmpty())
            <div class="text-center py-12">
                <p class="text-gray-600">Aucun médecin actif pour afficher les performances</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Médecin</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">RDV ce mois</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Consultations</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Taux complétion</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Patients vus</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($doctorPerformance as $doctor)
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    Dr. {{ $doctor['name'] }}
                                    <p class="text-xs text-gray-500 mt-1">{{ $doctor['speciality'] ?? 'Généraliste' }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <span class="font-bold">{{ $doctor['appointments_count'] }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <span class="font-bold">{{ $doctor['consultations_count'] }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @php
                                        $rate = $doctor['completion_rate'];
                                        $rateColor = $rate >= 80 ? 'bg-green-100 text-green-800' : ($rate >= 60 ? 'bg-orange-100 text-orange-800' : 'bg-red-100 text-red-800');
                                    @endphp
                                    <span class="inline-block {{ $rateColor }} text-xs font-medium px-3 py-1 rounded-full">
                                        {{ number_format($rate, 1) }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <span class="font-bold">{{ $doctor['patients_count'] }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">Détails</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Section 4 : Demandes Récentes -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900">
                Demandes de Service Récentes
                <span class="ml-2 inline-block bg-orange-100 text-orange-800 text-sm font-medium px-3 py-1 rounded-full">
                    {{ $totalRequests }}
                </span>
            </h2>
        </div>

        @if($recentRequests->isEmpty())
            <div class="text-center py-12">
                <i class="ki-filled ki-check text-green-500 text-4xl mb-4 block"></i>
                <p class="text-gray-600">Aucune demande de service</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Patient</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Service</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Statut</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Date</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentRequests as $request)
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ $request->patient?->full_name ?? 'Patient inconnu' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ ucfirst(str_replace('_', ' ', $request->service_type)) }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'converted' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                        ];
                                        $statusColor = $statusColors[$request->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-block {{ $statusColor }} text-xs font-medium px-3 py-1 rounded-full">
                                        {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $request->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="{{ route('service-requests.show', $request) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                        Voir
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($totalRequests > 5)
                <div class="mt-6 text-center">
                    <a href="{{ route('service-requests.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                        → Voir toutes les demandes ({{ $totalRequests }})
                    </a>
                </div>
            @endif
        @endif
    </div>

    <!-- Section 5 : Demandes Renvoyées par la Secrétaire -->
    @php
        $resubmittedRequests = \App\Models\ServiceRequest::where('status', 'contacted')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();
    @endphp

    @if($resubmittedRequests->count() > 0)
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                <i class="ki-filled ki-send text-blue-600"></i>
                Demandes Renvoyées par la Secrétaire
            </h2>
            <a href="{{ route('service-requests.index', ['status' => 'contacted']) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                Voir tout →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-blue-50 border-b border-blue-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Patient</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Type de Service</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Renvoyée le</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Note Secrétaire</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($resubmittedRequests as $request)
                    <tr class="hover:bg-blue-50 transition">
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                            {{ $request->full_name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <span class="inline-block bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full">
                                {{ ucfirst(str_replace('_', ' ', $request->service_type)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $request->updated_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                            @php
                                // Extraire la dernière note des notes internes
                                $notes = explode('[', $request->internal_notes ?? '');
                                $lastNote = end($notes);
                                $noteText = substr($lastNote, strpos($lastNote, '] ') + 2);
                            @endphp
                            <span title="{{ $noteText }}">{{ Str::limit($noteText, 30) }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('service-requests.show', $request) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                Voir
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Section 5 : Accès Rapides (Grid 3x2 = 6 boutons) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Bouton 1 : Tous les Rendez-vous -->
        <a href="{{ route('appointments.index') }}" class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition border border-blue-200">
            <div class="flex items-start justify-between mb-4">
                <div class="bg-blue-50 p-3 rounded-lg">
                    <i class="ki-filled ki-calendar text-blue-600 text-2xl"></i>
                </div>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">Tous les Rendez-vous</h3>
            <p class="text-sm text-gray-600">Gérer le planning global</p>
        </a>

        <!-- Bouton 2 : Demandes de Service -->
        <a href="{{ route('service-requests.index') }}" class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition border border-orange-200">
            <div class="flex items-start justify-between mb-4">
                <div class="bg-orange-50 p-3 rounded-lg">
                    <i class="ki-filled ki-notepad text-orange-600 text-2xl"></i>
                </div>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">Demandes de Service</h3>
            <p class="text-sm text-gray-600">Suivre les demandes</p>
        </a>

        <!-- Bouton 3 : Gestion Personnel -->
        <a href="{{ route('users.index') }}" class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition border border-green-200">
            <div class="flex items-start justify-between mb-4">
                <div class="bg-green-50 p-3 rounded-lg">
                    <i class="ki-filled ki-people text-green-600 text-2xl"></i>
                </div>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">Gestion Personnel</h3>
            <p class="text-sm text-gray-600">Médecins et patients</p>
        </a>

        <!-- Bouton 4 : Mes Consultations -->
        <a href="#" class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition border border-purple-200">
            <div class="flex items-start justify-between mb-4">
                <div class="bg-purple-50 p-3 rounded-lg">
                    <i class="ki-filled ki-stethoscope text-purple-600 text-2xl"></i>
                </div>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">Mes Consultations</h3>
            <p class="text-sm text-gray-600">En tant que médecin</p>
        </a>

        <!-- Bouton 5 : Statistiques -->
        <a href="#" class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition border border-cyan-200">
            <div class="flex items-start justify-between mb-4">
                <div class="bg-cyan-50 p-3 rounded-lg">
                    <i class="ki-filled ki-chart-line text-cyan-600 text-2xl"></i>
                </div>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">Statistiques</h3>
            <p class="text-sm text-gray-600">Rapports détaillés</p>
        </a>

        <!-- Bouton 6 : Paramètres -->
        <a href="#" class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition border border-gray-200">
            <div class="flex items-start justify-between mb-4">
                <div class="bg-gray-50 p-3 rounded-lg">
                    <i class="ki-filled ki-setting-3 text-gray-600 text-2xl"></i>
                </div>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">Paramètres</h3>
            <p class="text-sm text-gray-600">Configuration système</p>
        </a>
    </div>
</div>
@endsection
