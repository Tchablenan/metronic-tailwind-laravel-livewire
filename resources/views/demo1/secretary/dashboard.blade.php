@extends('layouts.demo1.base')

@section('content')
@php
    use App\Models\ServiceRequest;
    use App\Models\Appointment;
    use App\Models\User;
    use Carbon\Carbon;

    // Statistiques principales
    $stats = [
        'pending_requests' => ServiceRequest::where('status', 'pending')->count(),
        'today_appointments' => Appointment::whereDate('appointment_date', today())->count(),
        'to_confirm' => Appointment::where('status', 'scheduled')->count(),
        'today_patients' => Appointment::whereDate('appointment_date', today())->distinct('patient_id')->count(),
    ];

    // Demandes urgentes à traiter
    $urgentRequests = ServiceRequest::where('status', 'pending')
        ->orderByRaw("CASE urgency WHEN 'high' THEN 1 WHEN 'medium' THEN 2 ELSE 3 END")
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();

    // Rendez-vous du jour
    $todayAppointments = Appointment::with(['patient', 'doctor', 'nurse'])
        ->whereDate('appointment_date', today())
        ->orderBy('appointment_time', 'asc')
        ->take(8)
        ->get();

    // Rendez-vous à confirmer
    $toConfirm = Appointment::with(['patient'])
        ->where('status', 'scheduled')
        ->orderBy('appointment_date', 'asc')
        ->orderBy('appointment_time', 'asc')
        ->take(5)
        ->get();

    // Activité récente (aujourd'hui)
    $todayActivity = [
        'new_requests' => ServiceRequest::whereDate('created_at', today())->count(),
        'converted_today' => ServiceRequest::where('status', 'converted')->whereDate('updated_at', today())->count(),
        'confirmed_today' => Appointment::where('status', 'confirmed')->whereDate('updated_at', today())->count(),
        'completed_today' => Appointment::where('status', 'completed')->whereDate('updated_at', today())->count(),
    ];
@endphp

<div class="container mx-auto px-4 py-6">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
            Bonjour {{ auth()->user()->first_name }} 👋
        </h1>
        <p class="text-gray-600">
            {{ now()->translatedFormat('l j F Y') }} • Gérez l'accueil et le planning de la clinique
        </p>
    </div>

    <!-- Cartes statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Demandes en attente - CORRIGÉ: orange → yellow -->
        <a href="{{ route('service-requests.index', ['status' => 'pending']) }}"
           class="group border-3 border-orange-200  rounded-xl p-4 text-white shadow-lg hover:shadow-2xl transition-all duration-300 hover:scale-105">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="ki-filled  ki-notification-on text-3xl text-yellow-500"></i>
                </div>
                @if($stats['pending_requests'] > 0)
                <span class="animate-pulse w-3 h-3 bg-white rounded-full"></span>
                @endif
            </div>
            <p class="text-4xl text-yellow-500  mb-2">{{ $stats['pending_requests'] }}</p>
            <p class="text-yellow-500 text-sm font-medium">Demandes à traiter</p>
        </a>

        <!-- RDV du jour -->
        <a href="{{ route('appointments.index', ['date' => today()->format('Y-m-d')]) }}"
           class="group border-3 border-blue-500  rounded-lg p-4 text-white shadow-lg hover:shadow-2xl transition-all duration-300 hover:scale-105">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="ki-filled text-blue-600 ki-calendar-2 text-3xl"></i>
                </div>
            </div>
            <p class="text-4xl text-blue-600 font-bold mb-2">{{ $stats['today_appointments'] }}</p>
            <p class="text-blue-600 text-sm font-medium">Rendez-vous aujourd'hui</p>
        </a>

        <!-- À confirmer - CORRIGÉ: purple → violet -->
        <a href="{{ route('appointments.index', ['status' => 'scheduled']) }}"
           class="group border-3 border-violet-200 rounded-lg p-4 text-white shadow-lg hover:shadow-lg transition-all duration-300 hover:scale-105">
            <div class="flex items-center justify-between mb-4 ">
                <div class="w-14 h-14 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="ki-filled text-violet-500 ki-time text-3xl"></i>
                </div>
                @if($stats['to_confirm'] > 0)
                <span class="animate-pulse w-3 h-3 bg-white rounded-full"></span>
                @endif
            </div>
            <p class="text-4xl text-violet-500 font-bold mb-2">{{ $stats['to_confirm'] }}</p>
            <p class="text-violet-500 text-sm font-medium">À confirmer</p>
        </a>

        <!-- Patients attendus -->
        <div class="border-3 border-green-200 rounded-lg p-4 text-white shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                    <i class="ki-filled ki-chart-line-up-2 text-3xl text-green-500"></i>
                </div>
            </div>
            <p class="text-4xl text-green-500 font-bold mb-2">{{ $stats['today_patients'] }}</p>
            <p class="text-green-100 text-green-500 text-sm font-medium">Patients attendus</p>
        </div>
    </div>


    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Demandes urgentes à traiter - CORRIGÉ: orange → yellow -->
        <div class="bg-white rounded-xl shadow-md ">
            <div class="px-4 py-4  border-b border-yellow-500 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i class="ki-filled ki-notification-on text-yellow-600"></i>
                    Demandes urgentes
                </h3>
                <a href="{{ route('service-requests.index', ['status' => 'pending']) }}"
                   class="text-sm font-medium text-yellow-600 hover:text-yellow-700 hover:underline flex items-center gap-1">
                    Voir tout
                    <i class="ki-filled ki-right text-xs"></i>
                </a>
            </div>
            <div class="">
                @forelse($urgentRequests as $request)
                <a href="{{ route('service-requests.show', $request) }}"
                   class="block px-7 py-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                @php
                                $urgencyConfig = [
                                    'low' => ['bg' => 'bg-green-50', 'text' => 'text-green-700', 'border' => 'border-green-200', 'emoji' => '🟢'],
                                    'medium' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-700', 'border' => 'border-yellow-500', 'emoji' => '🟡'],
                                    'high' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-300', 'emoji' => '🔴'],
                                ];
                                $urg = $urgencyConfig[$request->urgency] ?? $urgencyConfig['medium'];
                                @endphp
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium border {{ $urg['bg'] }} {{ $urg['text'] }} {{ $urg['border'] }}">
                                    {{ $urg['emoji'] }}
                                    @switch($request->urgency)
                                        @case('low') Faible @break
                                        @case('medium') Moyenne @break
                                        @case('high') URGENT @break
                                    @endswitch
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ $request->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="font-semibold text-gray-900">{{ $request->full_name }}</p>
                            <div class="flex items-center gap-3 text-sm text-gray-600 mt-1">
                                <span class="flex items-center gap-1">
                                    <i class="ki-filled ki-phone text-xs"></i>
                                    {{ $request->phone_number }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">
                                @switch($request->service_type)
                                    @case('appointment') 📅 Rendez-vous @break
                                    @case('home_visit') 🏠 Visite domicile @break
                                    @case('emergency') 🚨 Urgence @break
                                    @case('transport') 🚑 Transport @break
                                    @case('consultation') 🩺 Consultation @break
                                    @default {{ $request->service_type }}
                                @endswitch
                            </p>
                        </div>
                        <i class="ki-filled ki-right text-gray-400 text-sm"></i>
                    </div>
                </a>
                @empty
                <div class="px-6 py-12 text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="ki-filled ki-check-circle text-3xl text-green-600"></i>
                    </div>
                    <p class="text-gray-600 font-medium">Toutes les demandes sont traitées</p>
                    <p class="text-gray-500 text-sm mt-1">Excellent travail !</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Rendez-vous à confirmer - CORRIGÉ: purple → violet, pink n'existe pas → violet-100 -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-7 py-4 bg-gradient-to-r from-violet-50 to-violet-100 border-b border-violet-200 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-4">
                    <i class="ki-filled ki-time text-violet-600"></i>
                    À confirmer
                </h3>
                <a href="{{ route('appointments.index', ['status' => 'scheduled']) }}"
                   class="text-sm font-medium text-violet-600 hover:text-violet-700 hover:underline flex items-center gap-1">
                    Voir tout
                    <i class="ki-filled ki-right text-xs"></i>
                </a>
            </div>
            <div class="">
                @forelse($toConfirm as $appointment)
                <a href="{{ route('appointments.show', $appointment) }}"
                   class="block px-6 py-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-sm font-bold text-violet-600">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                                </span>
                            </div>
                            <p class="font-semibold text-gray-900">{{ $appointment->patient->full_name }}</p>
                            <div class="flex items-center gap-3 text-sm text-gray-600 mt-1">
                                <span class="flex items-center gap-1">
                                    <i class="ki-filled ki-phone text-xs"></i>
                                    {{ $appointment->patient->phone_number }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">{{ $appointment->type_label }}</p>
                            @if($appointment->is_emergency)
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-red-600 mt-1">
                                <i class="ki-filled ki-information text-sm"></i>
                                URGENCE
                            </span>
                            @endif
                        </div>
                        <i class="ki-filled ki-right text-gray-400 text-sm"></i>
                    </div>
                </a>
                @empty
                <div class="px-6 py-12 text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="ki-filled ki-check-circle text-3xl text-green-600"></i>
                    </div>
                    <p class="text-gray-600 font-medium">Tous les RDV sont confirmés</p>
                    <p class="text-gray-500 text-sm mt-1">Parfait !</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Planning du jour - CORRIGÉ: cyan n'existe pas → blue-100 -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
        <div class="px-4 py-4  border-b border-blue-500 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <i class="ki-filled ki-calendar-2 text-blue-600"></i>
                Planning du jour
            </h3>
            <a href="{{ route('appointments.index') }}"
               class="text-sm font-medium text-blue-600 hover:text-blue-700 hover:underline flex items-center gap-1">
                Planning complet
                <i class="ki-filled ki-right text-xs"></i>
            </a>
        </div>
        <div class="">
            @forelse($todayAppointments as $appointment)
            <a href="{{ route('appointments.show', $appointment) }}"
               class="block px-7 py-4 hover:bg-gray-50 transition-colors">
                <div class="flex items-center gap-6">
                    <div class="text-center min-w-[60px]">
                        <span class="block text-2xl font-bold text-blue-600">
                            {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                        </span>
                        <span class="text-xs text-gray-500">{{ $appointment->duration }} min</span>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <p class="font-semibold text-gray-900">{{ $appointment->patient->full_name }}</p>
                            @php
                            $statusColors = [
                                'scheduled' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                'confirmed' => 'bg-green-50 text-green-700 border-green-200',
                                'in_progress' => 'bg-violet-50 text-violet-700 border-violet-200',
                                'completed' => 'bg-gray-50 text-gray-700 border-gray-200',
                                'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                            ];
                            @endphp
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium border {{ $statusColors[$appointment->status] ?? 'bg-gray-50 text-gray-700 border-gray-200' }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                {{ $appointment->status_label }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600">{{ $appointment->type_label }}</p>
                        @if($appointment->doctor)
                        <p class="text-sm text-gray-500 mt-1">
                            <i class="ki-filled ki-user text-xs"></i>
                            Dr. {{ $appointment->doctor->full_name }}
                        </p>
                        @endif
                        @if($appointment->is_emergency)
                        <span class="inline-flex items-center gap-1 text-xs font-medium text-red-600 mt-1">
                            <i class="ki-filled ki-information text-sm"></i>
                            URGENCE
                        </span>
                        @endif
                    </div>
                    <i class="ki-filled ki-right text-gray-400"></i>
                </div>
            </a>
            @empty
            <div class="px-6 py-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="ki-filled ki-calendar-2 text-3xl text-gray-400"></i>
                </div>
                <p class="text-gray-600 font-medium">Aucun rendez-vous aujourd'hui</p>
                <p class="text-gray-500 text-sm mt-1">Journée calme !</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
            <i class="ki-filled ki-rocket text-blue-600"></i>
            Actions rapides
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- CORRIGÉ: orange → yellow -->
            <a href="{{ route('service-requests.index') }}"
               class="group p-5 border-2 border-yellow-500 rounded-xl hover:bg-yellow-50 hover:border-yellow-300 transition-all hover:scale-105">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center group-hover:bg-yellow-500 transition-colors">
                        <i class="ki-filled ki-message-text text-2xl text-yellow-600 group-hover:text-white"></i>
                    </div>
                    <div>
                        <p class="font-bold text-gray-900 group-hover:text-yellow-600">Demandes site web</p>
                        <p class="text-sm text-gray-600">Traiter les demandes</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('appointments.create') }}"
               class="group p-5 border-2 border-blue-500 rounded-xl hover:bg-blue-50 hover:border-blue-300 transition-all hover:scale-105">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center group-hover:bg-blue-500 transition-colors">
                        <i class="ki-filled ki-plus-circle text-2xl text-blue-600 group-hover:text-white"></i>
                    </div>
                    <div>
                        <p class="font-bold text-gray-900 group-hover:text-blue-600">Nouveau rendez-vous</p>
                        <p class="text-sm text-gray-600">Planifier un RDV</p>
                    </div>
                </div>
            </a>

            <!-- CORRIGÉ: purple → violet, border-purple-400 n'existe pas → border-violet-400 n'existe pas → utiliser 300 ou 500 -->
            <a href="{{ route('appointments.index') }}"
               class="group p-5 border-2 border-violet-200 rounded-xl hover:bg-violet-50 hover:border-violet-300 transition-all hover:scale-105">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-violet-100 flex items-center justify-center group-hover:bg-violet-500 transition-colors">
                        <i class="ki-filled ki-calendar-2 text-2xl text-violet-600 group-hover:text-white"></i>
                    </div>
                    <div>
                        <p class="font-bold text-gray-900 group-hover:text-violet-600">Planning complet</p>
                        <p class="text-sm text-gray-600">Tous les rendez-vous</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('users.index', ['role' => 'patient']) }}"
               class="group p-5 border-2 border-green-200 rounded-xl hover:bg-green-50 hover:border-green-300 transition-all hover:scale-105">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center group-hover:bg-green-500 transition-colors">
                        <i class="ki-filled ki-profile-user text-2xl text-green-600 group-hover:text-white"></i>
                    </div>
                    <div>
                        <p class="font-bold text-gray-900 group-hover:text-green-600">Patients</p>
                        <p class="text-sm text-gray-600">Gérer les patients</p>
                    </div>
                </div>
            </a>

            <!-- CORRIGÉ: cyan n'existe pas → blue -->
            <a href="tel:+2250700000000"
               class="group p-5 border-2 border-blue-500 rounded-xl hover:bg-blue-50 hover:border-blue-300 transition-all hover:scale-105">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center group-hover:bg-blue-500 transition-colors">
                        <i class="ki-filled ki-phone text-2xl text-blue-600 group-hover:text-white"></i>
                    </div>
                    <div>
                        <p class="font-bold text-gray-900 group-hover:text-blue-600">Appeler un patient</p>
                        <p class="text-sm text-gray-600">Contact rapide</p>
                    </div>
                </div>
            </a>

            <!-- CORRIGÉ: indigo n'existe pas → violet -->
            <a href="{{ route('users.index') }}"
               class="group p-5 border-2 border-violet-200 rounded-xl hover:bg-violet-50 hover:border-violet-300 transition-all hover:scale-105">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-violet-100 flex items-center justify-center group-hover:bg-violet-500 transition-colors">
                        <i class="ki-filled ki-people text-2xl text-violet-600 group-hover:text-white"></i>
                    </div>
                    <div>
                        <p class="font-bold text-gray-900 group-hover:text-violet-600">Équipe médicale</p>
                        <p class="text-sm text-gray-600">Gérer l'équipe</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
