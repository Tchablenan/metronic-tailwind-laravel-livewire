@extends('layouts.demo1.base')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header -->
    <div class="mb-8">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Demandes de Service
                </h1>
                <p class="text-sm text-gray-600 mt-1">
                    Gérez les demandes reçues depuis le site vitrine
                </p>
            </div>

            {{-- Bouton créer demande pour secrétaire --}}
            @can('create', App\Models\ServiceRequest::class)
            <a href="{{ route('secretary.service-requests.create') }}"
               style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; font-size: 14px; font-weight: 500; color: white; background-color: rgb(34, 197, 94); border-radius: 8px;">
                <i class="ki-filled ki-plus"></i>
                Nouvelle demande
            </a>
            @endcan
        </div>
    </div>

    <!-- Cartes statistiques -->
    @if(Auth::user()->role === 'secretary')
    <div class="grid grid-cols-4 md:grid-cols-4 lg:grid-cols-4 gap-6 mb-8">

        <!-- Total -->
        <a href="{{ route('service-requests.index') }}"
           class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition-shadow {{ !request()->hasAny(['status', 'payment_status', 'paid_not_sent']) ? 'ring-2 ring-blue-300' : '' }}">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="ki-filled ki-message-text text-blue-600 text-xl"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            <p class="text-xs text-gray-600 mt-1">Total demandes</p>
        </a>

        <!-- Non payées -->
        <a href="{{ route('service-requests.index', ['payment_status' => 'unpaid']) }}"
           class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition-shadow border-l-4 border-red-500 {{ request('payment_status') === 'unpaid' ? 'ring-2 ring-destructive' : '' }}">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="ki-filled ki-dollar text-red-600 text-xl"></i>
                </div>
                @if($stats['unpaid'] > 0)
                <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                @endif
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['unpaid'] }}</p>
            <p class="text-xs text-gray-600 mt-1">Non payées</p>
        </a>

        <!-- Payées non envoyées -->
        <a href="{{ route('service-requests.index') }}?paid_not_sent=1"
           class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition-shadow border-l-4 border-yellow-500 {{ request('paid_not_sent') ? 'ring-2 ring-yellow-200' : '' }}">
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

        <!-- Converties -->
        <a href="{{ route('service-requests.index', ['status' => 'converted']) }}"
           class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition-shadow border-l-4 border-green-500 {{ request('status') === 'converted' ? 'ring-2 ring-green-200' : '' }}">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="ki-filled ki-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['converted'] }}</p>
            <p class="text-xs text-gray-600 mt-1">Converties en RDV</p>
        </a>

    </div>
    @else
    <!-- Stats pour le médecin -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="ki-filled ki-message-text text-blue-600 text-xl"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            <p class="text-xs text-gray-600 mt-1">Total reçues</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="ki-filled ki-notification-on text-yellow-600 text-xl"></i>
                </div>
                @if($stats['to_validate'] > 0)
                <span class="w-2 h-2 bg-yellow-500 rounded-full animate-pulse"></span>
                @endif
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['to_validate'] }}</p>
            <p class="text-xs text-gray-600 mt-1">À valider</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="ki-filled ki-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['converted'] }}</p>
            <p class="text-xs text-gray-600 mt-1">Converties</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="ki-filled ki-information text-red-600 text-xl"></i>
                </div>
                @if($stats['urgent'] > 0)
                <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                @endif
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['urgent'] }}</p>
            <p class="text-xs text-gray-600 mt-1">Cas urgents</p>
        </div>

    </div>
    @endif

    <!-- Card principal -->
    <div class="bg-white rounded-lg shadow">

        <!-- Messages flash -->
        @if (session('success'))
        <div class="m-6 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-lg">
            <div class="flex items-center justify-between">
                <span class="flex items-center gap-2 text-sm font-medium">
                    <i class="ki-filled ki-check-circle text-green-600"></i>
                    {{ session('success') }}
                </span>
                <button onclick="this.parentElement.parentElement.remove()"
                        class="text-green-600 hover:text-green-800 p-1">
                    <i class="ki-filled ki-cross text-xs"></i>
                </button>
            </div>
        </div>
        @endif

        @if (session('error'))
        <div class="m-6 px-4 py-3 bg-red-50 border border-red-200 text-red-800 rounded-lg">
            <div class="flex items-center justify-between">
                <span class="flex items-center gap-2 text-sm font-medium">
                    <i class="ki-filled ki-cross-circle text-red-600"></i>
                    {{ session('error') }}
                </span>
                <button onclick="this.parentElement.parentElement.remove()"
                        class="text-red-600 hover:text-red-800 p-1">
                    <i class="ki-filled ki-cross text-xs"></i>
                </button>
            </div>
        </div>
        @endif

        <!-- Barre de filtres -->
        <div class="p-4 bg-gray-50 border-b border-gray-200">
            <form method="GET" action="{{ route('service-requests.index') }}" class="space-y-4">

                <!-- Ligne 1: Recherche -->
                <div class="flex flex-col lg:flex-row gap-3">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 px-3 py-2 border border-gray-300 rounded-lg bg-white focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500">
                            <i class="ki-filled ki-magnifier text-gray-400 text-sm"></i>
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Rechercher par nom, email, téléphone..."
                                   class="flex-1 text-sm outline-none bg-transparent" />
                        </div>
                    </div>
                </div>

                <!-- Ligne 2: Filtres -->
                <div class="grid grid-cols-4 sm:grid-cols-4 lg:grid-cols-4 gap-3">

                    <!-- Statut -->
                    <select name="status" class="px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tous les statuts</option>
                        @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>

                    <!-- Service -->
                    <select name="service_type" class="px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tous les services</option>
                        @foreach($serviceTypes as $value => $label)
                        <option value="{{ $value }}" {{ request('service_type') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>

                    <!-- Urgence -->
                    <select name="urgency" class="px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Toutes urgences</option>
                        @foreach($urgencies as $value => $label)
                        <option value="{{ $value }}" {{ request('urgency') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>

                    <!-- Paiement (SECRÉTAIRE SEULEMENT) -->
                    @if(Auth::user()->role === 'secretary')
                    <select name="payment_status" class="px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tous paiements</option>
                        @foreach($paymentStatuses as $value => $label)
                        <option value="{{ $value }}" {{ request('payment_status') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                    @endif

                </div>

                <!-- Boutons -->
                <div class="flex items-center gap-3">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="ki-filled ki-magnifier text-sm"></i>
                        Filtrer
                    </button>

                    @if(request()->hasAny(['search', 'status', 'service_type', 'urgency', 'payment_status', 'paid_not_sent']))
                    <a href="{{ route('service-requests.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                        <i class="ki-filled ki-setting-4 text-sm"></i>
                        Réinitialiser
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tableau -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-300 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                            Demandeur
                        </th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                            Service
                        </th>
                        @if(Auth::user()->role === 'secretary')
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                            Paiement
                        </th>
                        @endif
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                            Urgence
                        </th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                            Reçue le
                        </th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white border-y border-gray-300">
                    @forelse($serviceRequests as $request)
                    <tr class="hover:bg-gray-50 transition-colors">

                        <!-- Demandeur -->
                        <td class="px-4 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold text-gray-900">
                                    {{ $request->full_name }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ $request->email }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    📱 {{ $request->phone_number }}
                                </span>
                            </div>
                        </td>

                        <!-- Service -->
                        <td class="px-4 py-4">
                            @php
                            $serviceStyles = [
                                'appointment' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'home_visit' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                'emergency' => 'bg-red-50 text-red-700 border-red-200',
                                'transport' => 'bg-purple-50 text-purple-700 border-purple-200',
                                'consultation' => 'bg-green-50 text-green-700 border-green-200',
                                'other' => 'bg-gray-50 text-gray-700 border-gray-200',
                            ];
                            @endphp
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium border {{ $serviceStyles[$request->service_type] ?? 'bg-gray-50 text-gray-700' }}">
                                {{ $serviceTypes[$request->service_type] ?? $request->service_type }}
                            </span>
                        </td>

                        <!-- Paiement (SECRÉTAIRE SEULEMENT) -->
                        @if(Auth::user()->role === 'secretary')
                        <td class="px-4 py-4">
                            <div class="flex flex-col gap-1">
                                @php
                                $paymentStyles = [
                                    'unpaid' => 'bg-red-50 text-red-700 border-red-200',
                                    'paid' => 'bg-green-50 text-green-700 border-green-200',
                                    'refunded' => 'bg-gray-50 text-gray-700 border-gray-200',
                                ];
                                @endphp
                                <span class="inline-flex items-center w-fit px-2 py-1 rounded-full text-xs font-medium border {{ $paymentStyles[$request->payment_status] ?? 'bg-gray-50 text-gray-700' }}">
                                    @if($request->payment_status === 'paid')
                                        <i class="ki-filled ki-check-circle text-xs mr-1"></i>
                                        Payé {{ number_format($request->payment_amount, 0, ',', ' ') }} F
                                    @elseif($request->payment_status === 'unpaid')
                                        <i class="ki-filled ki-cross-circle text-xs mr-1"></i>
                                        Non payé
                                    @else
                                        Remboursé
                                    @endif
                                </span>

                                @if($request->payment_status === 'paid')
                                    @if($request->sent_to_doctor)
                                    <span class="inline-flex items-center w-fit px-2 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                        <i class="ki-filled ki-send text-xs mr-1"></i>
                                        Envoyé au médecin
                                    </span>
                                    @else
                                    <span class="inline-flex items-center w-fit px-2 py-1 rounded-full text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200 animate-pulse">
                                        <i class="ki-filled ki-time text-xs mr-1"></i>
                                        À envoyer
                                    </span>
                                    @endif
                                @endif
                            </div>
                        </td>
                        @endif

                        <!-- Urgence -->
                        <td class="px-4 py-4">
                            @php
                            $urgencyStyles = [
                                'low' => 'bg-green-50 text-green-700 border-green-200',
                                'medium' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                'high' => 'bg-red-50 text-red-700 border-red-200',
                            ];
                            @endphp
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium border {{ $urgencyStyles[$request->urgency] ?? 'bg-gray-50 text-gray-700' }}">
                                @if($request->urgency === 'high') 🔴 @elseif($request->urgency === 'medium') 🟡 @else 🟢 @endif
                                {{ $urgencies[$request->urgency] ?? $request->urgency }}
                            </span>
                        </td>

                        <!-- Statut -->
                        <td class="px-4 py-4">
                            @php
                            $statusStyles = [
                                'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                'contacted' => 'bg-purple-50 text-purple-700 border-purple-200',
                                'converted' => 'bg-green-50 text-green-700 border-green-200',
                                'rejected' => 'bg-red-50 text-red-700 border-red-200',
                                'cancelled' => 'bg-gray-50 text-gray-700 border-gray-200',
                            ];
                            @endphp
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium border {{ $statusStyles[$request->status] ?? 'bg-gray-50 text-gray-700' }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                {{ $statuses[$request->status] ?? $request->status }}
                            </span>
                        </td>

                        <!-- Date -->
                        <td class="px-4 py-4">
                            <span class="text-xs text-gray-600">
                                {{ $request->created_at->format('d/m/Y H:i') }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="px-4 py-4 text-center">
                            <a href="{{ route('service-requests.show', $request) }}"
                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg hover:bg-blue-50 transition-colors"
                               title="Voir détails">
                                <i class="ki-filled ki-eye text-lg text-blue-600"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ Auth::user()->role === 'secretary' ? '7' : '6' }}" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                    <i class="ki-filled ki-message-text text-3xl text-gray-400"></i>
                                </div>
                                <p class="text-sm font-medium text-gray-900">Aucune demande trouvée</p>
                                @if(request()->hasAny(['search', 'status', 'service_type', 'urgency', 'payment_status']))
                                <p class="text-xs text-gray-600 mt-1">Essayez de modifier vos filtres</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($serviceRequests->hasPages())
        <div class="px-4 py-4 bg-gray-50 border-t border-gray-200 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-sm text-gray-600">
                Affichage de <span class="font-semibold text-gray-900">{{ $serviceRequests->firstItem() }}</span>
                à <span class="font-semibold text-gray-900">{{ $serviceRequests->lastItem() }}</span>
                sur <span class="font-semibold text-gray-900">{{ $serviceRequests->total() }}</span> demandes
            </div>
            <div>
                {{ $serviceRequests->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
