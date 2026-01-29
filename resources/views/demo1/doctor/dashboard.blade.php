@extends('layouts.demo1.base')

@section('content')
@php
    use App\Models\ServiceRequest;
    use App\Models\Appointment;
    use App\Models\User;
    use Carbon\Carbon;

    // Statistiques globales - SEULEMENT demandes payées ET envoyées
    $stats = [
        'pending_requests' => ServiceRequest::where('payment_status', 'paid')
                                            ->where('sent_to_doctor', true)
                                            ->whereNotIn('status', ['converted', 'rejected'])
                                            ->count(),
        'today_appointments' => Appointment::whereDate('appointment_date', today())->count(),
        'total_patients' => User::where('role', 'patient')->where('is_active', true)->count(),
        'urgent_cases' => ServiceRequest::where('payment_status', 'paid')
                                        ->where('sent_to_doctor', true)
                                        ->where('urgency', 'high')
                                        ->whereNotIn('status', ['converted', 'rejected'])
                                        ->count(),
    ];

    // Rendez-vous du jour
    $todayAppointments = Appointment::with(['patient', 'doctor', 'nurse'])
        ->whereDate('appointment_date', today())
        ->orderBy('appointment_time', 'asc')
        ->take(8)
        ->get();

    // Demandes urgentes À VALIDER - SEULEMENT payées ET envoyées
    $urgentRequests = ServiceRequest::with(['handler', 'sender'])
        ->where('payment_status', 'paid')              // ✅ Payé
        ->where('sent_to_doctor', true)                // ✅ Envoyé par secrétaire
        ->whereNotIn('status', ['converted', 'rejected']) // ✅ Pas encore traité
        ->orderByRaw("CASE urgency WHEN 'high' THEN 1 WHEN 'medium' THEN 2 ELSE 3 END")
        ->orderBy('sent_to_doctor_at', 'desc')
        ->take(5)
        ->get();

    // Activité récente (7 derniers jours)
    $recentActivity = [
        'new_requests' => ServiceRequest::where('payment_status', 'paid')
                                        ->where('sent_to_doctor', true)
                                        ->where('sent_to_doctor_at', '>=', now()->subDays(7))
                                        ->count(),
        'converted_requests' => ServiceRequest::where('status', 'converted')
                                              ->where('updated_at', '>=', now()->subDays(7))
                                              ->count(),
        'completed_appointments' => Appointment::where('status', 'completed')
                                              ->where('updated_at', '>=', now()->subDays(7))
                                              ->count(),
        'new_patients' => User::where('role', 'patient')
                             ->where('created_at', '>=', now()->subDays(7))
                             ->count(),
    ];
@endphp

<div class="container mx-auto px-4 py-6">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
            Bonjour Dr. {{ Auth::user()->last_name }} 👋
        </h1>
        <p class="text-gray-600">
            {{ now()->translatedFormat('l j F Y') }} • Voici un aperçu de votre activité
        </p>
    </div>

    <!-- Cartes statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Demandes à valider -->
        <a href="{{ route('service-requests.index') }}"
           class="group border-2 border-orange-200 rounded-xl p-4 text-white shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="ki-filled ki-notification-on text-orange-400 text-3xl"></i>
                </div>
                @if($stats['pending_requests'] > 0)
                <span class="animate-pulse w-3 h-3 text-orange-400 rounded-full"></span>
                @endif
            </div>
            <p class="text-xl text-orange-400 font-bold mb-2">{{ $stats['pending_requests'] }}</p>
            <p class="text-orange-50 text-sm  font-medium">Demandes à valider</p>
        </a>

        <!-- RDV du jour -->
        <a href="{{ route('appointments.index', ['date' => today()->format('Y-m-d')]) }}"
           class="group border-2 border-blue-500 rounded-xl p-4 text-white shadow-lg hover:shadow-2xl transition-all duration-300 hover:scale-105">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="ki-filled  ki-calendar-tick text-blue-600 text-3xl"></i>
                </div>
            </div>
            <p class="text-4xl text-blue-600 font-bold mb-2">{{ $stats['today_appointments'] }}</p>
            <p class="text-blue-600 text-sm font-medium">Rendez-vous aujourd'hui</p>
        </a>

        <!-- Total patients -->
        <a href="{{ route('users.index', ['role' => 'patient']) }}"
           class="group border-2 border-green-500 rounded-xl p-4 text-white shadow-lg hover:shadow-2xl transition-all duration-300 hover:scale-105">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class=" text-green-600 ki-filled  ki-profile-user text-3xl"></i>
                </div>
            </div>
            <p class="text-4xl text-green-600  font-bold mb-2">{{ $stats['total_patients'] }}</p>
            <p class="text-green-600 text-sm font-medium">Patients actifs</p>
        </a>

        <!-- Cas urgents -->
        <a href="{{ route('service-requests.index', ['urgency' => 'high']) }}"
           class="group border-2 border-red-300 rounded-xl p-4 shadow-lg hover:shadow-2xl transition-all duration-300 hover:scale-105">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="ki-filled ki-cross-circle text-3xl"></i>
                </div>
                @if($stats['urgent_cases'] > 0)
                <span class="animate-pulse w-3 h-3 bg-white rounded-full"></span>
                @endif
            </div>
            <p class="text-4xl text-red-100 font-bold mb-2">{{ $stats['urgent_cases'] }}</p>
            <p class="text-red-600 text-sm font-medium">Cas urgents</p>
        </a>
    </div>



    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Rendez-vous du jour -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-4 py-4  border-b border-blue-500 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i class="ki-filled ki-calendar-2 text-blue-600"></i>
                    Rendez-vous du jour
                </h3>
                <a href="{{ route('appointments.index') }}"
                   class="text-sm font-medium text-blue-600 hover:text-blue-700 hover:underline">
                    Voir tout
                </a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($todayAppointments as $appointment)
                <a href="{{ route('appointments.show', $appointment) }}"
                   class="block px-4 py-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-sm font-bold text-blue-600">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                                </span>
                                @php
                                $statusColors = [
                                    'scheduled' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'confirmed' => 'bg-green-50 text-green-300 border-green-200',
                                    'in_progress' => 'bg-violet-50 text-violet-700 border-violet-200',
                                    'completed' => 'bg-gray-50 text-gray-700 border-gray-200',
                                    'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                                ];
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border {{ $statusColors[$appointment->status] ?? 'bg-gray-50 text-gray-700 border-gray-200' }}">
                                    {{ $appointment->status_label }}
                                </span>
                            </div>
                            <p class="font-semibold text-gray-900">{{ $appointment->patient->full_name }}</p>
                            <p class="text-sm text-gray-600">{{ $appointment->type_label }}</p>
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
                <div class="px-4 py-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="ki-filled ki-calendar-2 text-3xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-600 font-medium">Aucun rendez-vous aujourd'hui</p>
                    <p class="text-gray-500 text-sm mt-1">Profitez de cette journée calme !</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Demandes à valider (PAYÉES + ENVOYÉES PAR LA SECRÉTAIRE) -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-4 py-4  border-b border-orange-200 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i class="ki-filled ki-notification-on text-orange-400"></i>
                    Demandes à valider
                </h3>
                <a href="{{ route('service-requests.index') }}"
                   class="text-sm font-medium text-orange-600 hover:text-orange-700 hover:underline">
                    Voir tout
                </a>
            </div>
            <div class="border-y border-gray-200">
                @forelse($urgentRequests as $request)
                <a href="{{ route('service-requests.show', $request) }}"
                   class="block px-6 py-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                @php
                                $urgencyColors = [
                                    'low' => 'bg-green-50 text-green-700 border-green-200',
                                    'medium' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                    'high' => 'bg-red-50 text-red-700 border-red-200',
                                ];
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border {{ $urgencyColors[$request->urgency] ?? 'bg-gray-50 text-gray-700 border-gray-200' }}">
                                    @if($request->urgency === 'high') 🔴 @elseif($request->urgency === 'medium') 🟡 @else 🟢 @endif
                                    @switch($request->urgency)
                                        @case('low') Faible @break
                                        @case('medium') Moyenne @break
                                        @case('high') URGENT @break
                                    @endswitch
                                </span>
                                <span class="text-xs text-gray-500">
                                    Envoyé {{ $request->sent_to_doctor_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="font-semibold text-gray-900">{{ $request->full_name }}</p>
                            <p class="text-sm text-gray-600">
                                @switch($request->service_type)
                                    @case('appointment') 📅 Rendez-vous @break
                                    @case('home_visit') 🏠 Visite domicile @break
                                    @case('emergency') 🚨 Urgence @break
                                    @case('transport') 🚑 Transport @break
                                    @case('consultation') 🩺 Consultation @break
                                    @default {{ $request->service_type }}
                                @endswitch
                            </p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                    <i class="ki-filled ki-check-circle text-xs"></i>
                                    Payé {{ number_format($request->payment_amount, 0, ',', ' ') }} FCFA
                                </span>
                            </div>
                            @if($request->sender)
                            <p class="text-xs text-gray-500 mt-1">
                                Par {{ $request->sender->full_name }}
                            </p>
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
                    <p class="text-gray-600 font-medium">Toutes les demandes sont traitées</p>
                    <p class="text-gray-500 text-sm mt-1">Excellent travail !</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
        <a href="{{ route('service-requests.index') }}"
           class="group p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition-all hover:scale-105 border-2 border-transparent hover:border-orange-200">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-orange-100 flex items-center justify-center group-hover:bg-orange-500 transition-colors">
                    <i class="ki-filled ki-message-text text-2xl text-orange-600 group-hover:text-white"></i>
                </div>
                <div>
                    <p class="font-bold text-gray-900 group-hover:text-orange-600">Valider les demandes</p>
                    <p class="text-sm text-gray-600">Consulter et convertir</p>
                </div>
            </div>
        </a>

        <a href="{{ route('appointments.create') }}"
           class="group p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition-all hover:scale-105 border-2 border-transparent hover:border-green-200">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-green-100 flex items-center justify-center group-hover:bg-green-500 transition-colors">
                    <i class="ki-filled ki-calendar-add text-2xl text-green-600 group-hover:text-white"></i>
                </div>
                <div>
                    <p class="font-bold text-gray-900 group-hover:text-green-600">Nouveau rendez-vous</p>
                    <p class="text-sm text-gray-600">Planifier un RDV</p>
                </div>
            </div>
        </a>

        <a href="{{ route('users.index') }}"
           class="group p-4 bg-white rounded-xl shadow-md hover:shadow-lg transition-all hover:scale-105 border-2 border-transparent hover:border-purple-200">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-violet-500 flex items-center justify-center group-hover:bg-violet-500 transition-colors">
                    <i class="ki-filled ki-profile-user text-2xl text-violet-600 group-hover:text-white"></i>
                </div>
                <div>
                    <p class="font-bold text-gray-900 group-hover:text-violet-600">Gérer les utilisateurs</p>
                    <p class="text-sm text-gray-600">Équipe et patients</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
