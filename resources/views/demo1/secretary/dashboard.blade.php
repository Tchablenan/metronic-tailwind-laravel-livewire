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
        'unpaid_requests' => ServiceRequest::where('payment_status', 'unpaid')
                                          ->whereIn('status', ['pending', 'contacted'])
                                          ->count(),
        'paid_not_sent' => ServiceRequest::where('payment_status', 'paid')
                                        ->where('sent_to_doctor', false)
                                        ->whereNotIn('status', ['converted', 'rejected'])
                                        ->count(),
        'rejected_requests' => ServiceRequest::where('status', 'rejected')->count(),
        'today_appointments' => Appointment::whereDate('appointment_date', today())->count(),
    ];

    // Demandes NON PAYÉES à traiter (PRIORITÉ 1)
    $unpaidRequests = ServiceRequest::where('payment_status', 'unpaid')
        ->whereIn('status', ['pending', 'contacted'])
        ->orderByRaw("CASE urgency WHEN 'high' THEN 1 WHEN 'medium' THEN 2 ELSE 3 END")
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();

    // Demandes PAYÉES mais NON ENVOYÉES au médecin (PRIORITÉ 2)
    $paidNotSent = ServiceRequest::where('payment_status', 'paid')
        ->where('sent_to_doctor', false)
        ->whereNotIn('status', ['converted', 'rejected'])
        ->orderByRaw("CASE urgency WHEN 'high' THEN 1 WHEN 'medium' THEN 2 ELSE 3 END")
        ->orderBy('paid_at', 'asc')
        ->take(5)
        ->get();

    // Demandes REJETÉES (PRIORITÉ 3 - À RELANCER)
    $rejectedRequests = ServiceRequest::where('status', 'rejected')
        ->orderBy('updated_at', 'desc')
        ->take(5)
        ->get();

    // Rendez-vous du jour
    $todayAppointments = Appointment::with(['patient', 'doctor', 'nurse'])
        ->whereDate('appointment_date', today())
        ->orderBy('appointment_time', 'asc')
        ->take(6)
        ->get();

    // Rendez-vous à confirmer
    $toConfirm = Appointment::with(['patient'])
        ->where('status', 'scheduled')
        ->orderBy('appointment_date', 'asc')
        ->orderBy('appointment_time', 'asc')
        ->take(5)
        ->get();

    // Statistiques de paiement du jour
    $todayPayments = [
        'total_paid_today' => ServiceRequest::where('payment_status', 'paid')
                                            ->whereDate('paid_at', today())
                                            ->sum('payment_amount'),
        'count_paid_today' => ServiceRequest::where('payment_status', 'paid')
                                            ->whereDate('paid_at', today())
                                            ->count(),
        'sent_to_doctor_today' => ServiceRequest::where('sent_to_doctor', true)
                                                ->whereDate('sent_to_doctor_at', today())
                                                ->count(),
        'converted_today' => ServiceRequest::where('status', 'converted')
                                          ->whereDate('updated_at', today())
                                          ->count(),
    ];
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">
            Bonjour {{ auth()->user()->first_name }} 👋
        </h1>
        <p class="text-sm text-gray-600 mt-1">
            {{ now()->translatedFormat('l j F Y') }} • Tableau de bord secrétariat
        </p>
    </div>

    <!-- Cartes statistiques principales -->
    <div class="grid grid-cols-4 md:grid-cols-1 lg:grid-cols-4 gap-6 mb-8">

        <!-- Demandes non payées -->
        <a href="{{ route('service-requests.index', ['payment_status' => 'unpaid']) }}"
           class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition-shadow border-b-2 border-red-700">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="ki-filled ki-dollar text-red-300 text-xl"></i>
                </div>
                @if($stats['unpaid_requests'] > 0)
                <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                @endif
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['unpaid_requests'] }}</p>
            <p class="text-xs text-gray-600 mt-1">En attente de paiement</p>
        </a>

        <!-- Payées non envoyées -->
        <a href="{{ route('service-requests.index') }}?paid_not_sent=1"
           class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition-shadow border-l-4 border-yellow-500">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="ki-filled ki-send text-yellow-600 text-xl"></i>
                </div>
                @if($stats['paid_not_sent'] > 0)
                <span class="w-2 h-2 bg-yellow-500 rounded-full animate-pulse"></span>
                @endif
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['paid_not_sent'] }}</p>
            <p class="text-xs text-gray-600 mt-1">À envoyer au médecin</p>
        </a>

        <!-- RDV du jour -->
        <a href="{{ route('appointments.index', ['date' => today()->format('Y-m-d')]) }}"
           class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition-shadow border-l-4 border-blue-500">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="ki-filled ki-calendar-2 text-blue-600 text-xl"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['today_appointments'] }}</p>
            <p class="text-xs text-gray-600 mt-1">Rendez-vous aujourd'hui</p>
        </a>

        <!-- Demandes en attente -->
        <a href="{{ route('service-requests.index', ['status' => 'pending']) }}"
           class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition-shadow border-l-4 border-purple-500">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="ki-filled ki-notification-on text-purple-600 text-xl"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['pending_requests'] }}</p>
            <p class="text-xs text-gray-600 mt-1">Demandes à traiter</p>
        </a>

        <!-- Demandes rejetées -->
        <a href="{{ route('service-requests.index', ['status' => 'rejected']) }}"
           class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition-shadow border-l-4 border-orange-500">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="ki-filled ki-close-circle text-orange-600 text-xl"></i>
                </div>
                @if($stats['rejected_requests'] > 0)
                <span class="w-2 h-2 bg-orange-500 rounded-full animate-pulse"></span>
                @endif
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['rejected_requests'] }}</p>
            <p class="text-xs text-gray-600 mt-1">À relancer</p>
        </a>

    </div>

    <!-- Statistiques de paiement du jour -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
            <i class="ki-filled ki-chart-line-up-2 text-green-600"></i>
            Activité paiements aujourd'hui
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <div class="flex items-center gap-4 p-4 bg-green-50 rounded-lg border border-green-200">
                <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                    <i class="ki-filled ki-dollar text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-xl font-bold text-gray-900">{{ number_format($todayPayments['total_paid_today'], 0, ',', ' ') }} F</p>
                    <p class="text-xs text-gray-600">Montant encaissé</p>
                </div>
            </div>

            <div class="flex items-center gap-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                    <i class="ki-filled ki-check-circle text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-xl font-bold text-gray-900">{{ $todayPayments['count_paid_today'] }}</p>
                    <p class="text-xs text-gray-600">Paiements reçus</p>
                </div>
            </div>

            <div class="flex items-center gap-4 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                    <i class="ki-filled ki-send text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-xl font-bold text-gray-900">{{ $todayPayments['sent_to_doctor_today'] }}</p>
                    <p class="text-xs text-gray-600">Envoyées au médecin</p>
                </div>
            </div>

            <div class="flex items-center gap-4 p-4 bg-purple-50 rounded-lg border border-purple-200">
                <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
                    <i class="ki-filled ki-calendar-tick text-white text-xl"></i>
                </div>
                <div>
                    <p class="text-xl font-bold text-gray-900">{{ $todayPayments['converted_today'] }}</p>
                    <p class="text-xs text-gray-600">Converties en RDV</p>
                </div>
            </div>

        </div>
    </div>

    <!-- Section demandes -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">

        <!-- Demandes NON PAYÉES (PRIORITÉ 1) -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 bg-red-50 border-b border-red-200 flex items-center justify-between">
                <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <i class="ki-filled ki-dollar text-red-600"></i>
                    En attente de paiement
                </h3>
                <a href="{{ route('service-requests.index') }}" class="text-sm text-red-600 hover:text-red-700 font-medium">
                    Voir tout →
                </a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($unpaidRequests as $request)
                <a href="{{ route('service-requests.show', $request) }}"
                   class="block px-6 py-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                @php
                                $urgencyStyles = [
                                    'low' => 'bg-green-100 text-green-700 border-green-300',
                                    'medium' => 'bg-yellow-100 text-yellow-700 border-yellow-300',
                                    'high' => 'bg-red-100 text-red-700 border-red-300',
                                ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-medium rounded-full border {{ $urgencyStyles[$request->urgency] ?? 'bg-gray-100 text-gray-700' }}">
                                    @switch($request->urgency)
                                        @case('low') 🟢 Faible @break
                                        @case('medium') 🟡 Moyenne @break
                                        @case('high') 🔴 URGENT @break
                                    @endswitch
                                </span>
                                <span class="px-2 py-1 text-xs font-medium rounded-full border bg-red-100 text-red-700 border-red-300">
                                    Non payé
                                </span>
                            </div>
                            <p class="text-sm font-semibold text-gray-900">{{ $request->full_name }}</p>
                            <div class="flex items-center gap-3 text-xs text-gray-600 mt-1">
                                <span>📱 {{ $request->phone_number }}</span>
                                <span>{{ $request->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-gray-600 mt-1">
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
                        <i class="ki-filled ki-check-circle text-green-600 text-3xl"></i>
                    </div>
                    <p class="text-sm font-semibold text-gray-900">Tous les paiements sont à jour</p>
                    <p class="text-xs text-gray-600 mt-1">Excellent travail !</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Demandes PAYÉES non envoyées (PRIORITÉ 2) -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 bg-yellow-50 border-b border-yellow-200 flex items-center justify-between">
                <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <i class="ki-filled ki-send text-yellow-600"></i>
                    Payées - À envoyer
                </h3>
                <a href="{{ route('service-requests.index') }}" class="text-sm text-yellow-600 hover:text-yellow-700 font-medium">
                    Voir tout →
                </a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($paidNotSent as $request)
                <a href="{{ route('service-requests.show', $request) }}"
                   class="block px-6 py-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                @php
                                $urgencyStyles = [
                                    'low' => 'bg-green-100 text-green-700 border-green-300',
                                    'medium' => 'bg-yellow-100 text-yellow-700 border-yellow-300',
                                    'high' => 'bg-red-100 text-red-700 border-red-300',
                                ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-medium rounded-full border {{ $urgencyStyles[$request->urgency] ?? 'bg-gray-100 text-gray-700' }}">
                                    @switch($request->urgency)
                                        @case('low') 🟢 Faible @break
                                        @case('medium') 🟡 Moyenne @break
                                        @case('high') 🔴 URGENT @break
                                    @endswitch
                                </span>
                                <span class="px-2 py-1 text-xs font-medium rounded-full border bg-green-100 text-green-700 border-green-300">
                                    Payé {{ number_format($request->payment_amount, 0, ',', ' ') }} F
                                </span>
                            </div>
                            <p class="text-sm font-semibold text-gray-900">{{ $request->full_name }}</p>
                            <div class="flex items-center gap-3 text-xs text-gray-600 mt-1">
                                <span>📱 {{ $request->phone_number }}</span>
                                <span>Payé {{ $request->paid_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-gray-600 mt-1">
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
                        <i class="ki-filled ki-check-circle text-green-600 text-3xl"></i>
                    </div>
                    <p class="text-sm font-semibold text-gray-900">Tout est envoyé au médecin</p>
                    <p class="text-xs text-gray-600 mt-1">Parfait !</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Demandes rejetées à relancer -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 bg-orange-50 border-b border-orange-200 flex items-center justify-between">
                <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <i class="ki-filled ki-close-circle text-orange-600"></i>
                    Demandes rejetées à relancer
                </h3>
                <a href="{{ route('service-requests.index', ['status' => 'rejected']) }}" class="text-sm text-orange-600 hover:text-orange-700 font-medium">
                    Voir tout →
                </a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($rejectedRequests as $request)
                <a href="{{ route('service-requests.show', $request) }}"
                   class="block px-6 py-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-orange-100 text-orange-700 border border-orange-300">
                                    🔴 Rejeté
                                </span>
                                <span class="text-xs text-gray-500">{{ $request->updated_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm font-semibold text-gray-900">{{ $request->full_name }}</p>
                            <div class="flex items-center gap-3 text-xs text-gray-600 mt-1">
                                <span>📱 {{ $request->phone_number }}</span>
                                <span>Raison: {{ $request->rejection_reason ?? 'Non spécifiée' }}</span>
                            </div>
                        </div>
                        <i class="ki-filled ki-right text-gray-400 text-sm"></i>
                    </div>
                </a>
                @empty
                <div class="px-6 py-12 text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="ki-filled ki-check-circle text-green-600 text-3xl"></i>
                    </div>
                    <p class="text-sm font-semibold text-gray-900">Aucune demande rejetée</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Section Planning et RDV à confirmer -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">

        <!-- Planning du jour -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 bg-blue-50 border-b border-blue-200 flex items-center justify-between">
                <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <i class="ki-filled ki-calendar-2 text-blue-600"></i>
                    Planning du jour
                </h3>
                <a href="{{ route('appointments.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                    Voir tout →
                </a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($todayAppointments as $appointment)
                <a href="{{ route('appointments.show', $appointment) }}"
                   class="block px-6 py-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="text-center min-w-16">
                            <span class="text-xl font-bold text-blue-600">
                                {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                            </span>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900">{{ $appointment->patient->full_name }}</p>
                            <p class="text-xs text-gray-600">{{ $appointment->type_label }}</p>
                        </div>
                        @php
                        $statusStyles = [
                            'scheduled' => 'bg-blue-100 text-blue-700 border-blue-300',
                            'confirmed' => 'bg-green-100 text-green-700 border-green-300',
                            'in_progress' => 'bg-yellow-100 text-yellow-700 border-yellow-300',
                            'completed' => 'bg-gray-100 text-gray-700 border-gray-300',
                        ];
                        @endphp
                        <span class="px-2 py-1 text-xs font-medium rounded-full border {{ $statusStyles[$appointment->status] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ $appointment->status_label }}
                        </span>
                    </div>
                </a>
                @empty
                <div class="px-6 py-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="ki-filled ki-calendar-2 text-gray-400 text-3xl"></i>
                    </div>
                    <p class="text-sm font-semibold text-gray-700">Aucun rendez-vous aujourd'hui</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- RDV à confirmer -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 bg-purple-50 border-b border-purple-200 flex items-center justify-between">
                <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <i class="ki-filled ki-time text-purple-600"></i>
                    À confirmer
                </h3>
                <a href="{{ route('appointments.index', ['status' => 'scheduled']) }}" class="text-sm text-purple-600 hover:text-purple-700 font-medium">
                    Voir tout →
                </a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($toConfirm as $appointment)
                <a href="{{ route('appointments.show', $appointment) }}"
                   class="block px-6 py-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-sm font-semibold text-purple-600">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}
                                </span>
                                <span class="text-xs text-gray-600">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                                </span>
                            </div>
                            <p class="text-sm font-semibold text-gray-900">{{ $appointment->patient->full_name }}</p>
                            <p class="text-xs text-gray-600 mt-1">{{ $appointment->type_label }}</p>
                        </div>
                        <i class="ki-filled ki-right text-gray-400 text-sm"></i>
                    </div>
                </a>
                @empty
                <div class="px-6 py-12 text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="ki-filled ki-check-circle text-green-600 text-3xl"></i>
                    </div>
                    <p class="text-sm font-semibold text-gray-700">Tous les RDV sont confirmés</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Actions rapides -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
            <i class="ki-filled ki-rocket text-blue-600"></i>
            Actions rapides
        </h2>
        <div class="grid grid-cols-4 md:grid-cols-3 gap-6">

            <a href="{{ route('service-requests.index') }}"
               class="flex items-center gap-4 p-4 border-2 border-red-200 rounded-lg hover:bg-red-50 hover:border-red-300 transition-all">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="ki-filled ki-message-text text-red-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">Demandes site web</p>
                    <p class="text-xs text-gray-600">Traiter les demandes</p>
                </div>
            </a>

            <a href="{{ route('appointments.create') }}"
               class="flex items-center gap-4 p-4 border-2 border-blue-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-all">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="ki-filled ki-plus-circle text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">Nouveau rendez-vous</p>
                    <p class="text-xs text-gray-600">Planifier un RDV</p>
                </div>
            </a>

            <a href="{{ route('appointments.index') }}"
               class="flex items-center gap-4 p-4 border-2 border-purple-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-all">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="ki-filled ki-calendar-2 text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">Planning complet</p>
                    <p class="text-xs text-gray-600">Tous les rendez-vous</p>
                </div>
            </a>

        </div>
    </div>

</div>
@endsection
