@extends('layouts.demo1.base')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-5 pb-8">
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                Demandes de Service
            </h1>
            <p class="text-sm text-gray-600 font-medium">
                Gérez les demandes reçues depuis le site vitrine
            </p>
        </div>
    </div>

    <!-- Filtres rapides - 4 par ligne -->
    <div class="flex flex-wrap gap-4 mb-6">
        <!-- Toutes -->
        <a href="{{ route('service-requests.index') }}"
           class="flex-1 min-w-[220px] p-5 bg-white rounded-xl shadow-md {{ !request('status') ? 'ring-2 ring-blue-500 bg-blue-50' : 'hover:shadow-lg' }} transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Toutes</p>
                    <p class="text-3xl font-bold text-gray-900">{{ \App\Models\ServiceRequest::count() }}</p>
                </div>
                <div class="w-14 h-14 rounded-full flex items-center justify-center" style="background-color: #DBEAFE;">
                    <i class="ki-filled ki-message-text text-3xl" style="color: #2563EB;"></i>
                </div>
            </div>
        </a>

        <!-- En attente -->
        <a href="{{ route('service-requests.index', ['status' => 'pending']) }}"
           class="flex-1 min-w-[220px] p-5 bg-white rounded-xl shadow-md {{ request('status') === 'pending' ? 'ring-2 ring-orange-500 bg-orange-50' : 'hover:shadow-lg' }} transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">En attente</p>
                    <p class="text-3xl font-bold text-gray-900">
                        {{ \App\Models\ServiceRequest::where('status', 'pending')->count() }}
                    </p>
                </div>
                <div class="w-14 h-14 rounded-full flex items-center justify-center" style="background-color: #FED7AA;">
                    <i class="ki-filled ki-time text-3xl" style="color: #EA580C;"></i>
                </div>
            </div>
        </a>

        <!-- Contactées -->
        <a href="{{ route('service-requests.index', ['status' => 'contacted']) }}"
           class="flex-1 min-w-[220px] p-5 bg-white rounded-xl shadow-md {{ request('status') === 'contacted' ? 'ring-2 ring-purple-500 bg-purple-50' : 'hover:shadow-lg' }} transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Contactées</p>
                    <p class="text-3xl font-bold text-gray-900">
                        {{ \App\Models\ServiceRequest::where('status', 'contacted')->count() }}
                    </p>
                </div>
                <div class="w-14 h-14 rounded-full flex items-center justify-center" style="background-color: #E9D5FF;">
                    <i class="ki-filled ki-phone text-3xl" style="color: #9333EA;"></i>
                </div>
            </div>
        </a>

        <!-- Converties -->
        <a href="{{ route('service-requests.index', ['status' => 'converted']) }}"
           class="flex-1 min-w-[220px] p-5 bg-white rounded-xl shadow-md {{ request('status') === 'converted' ? 'ring-2 ring-green-500 bg-green-50' : 'hover:shadow-lg' }} transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Converties</p>
                    <p class="text-3xl font-bold text-gray-900">
                        {{ \App\Models\ServiceRequest::where('status', 'converted')->count() }}
                    </p>
                </div>
                <div class="w-14 h-14 rounded-full flex items-center justify-center" style="background-color: #D1FAE5;">
                    <i class="ki-filled ki-check-circle text-3xl" style="color: #059669;"></i>
                </div>
            </div>
        </a>
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
            <form method="GET" action="{{ route('service-requests.index') }}" class="flex flex-col lg:flex-row gap-3 w-full">

                <!-- Recherche -->
                <div class="flex-1">
                    <div class="flex items-center gap-2 px-3 py-2 border border-gray-300 rounded-lg bg-white focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 focus-within:shadow-sm transition-all">
                        <i class="ki-filled ki-magnifier text-gray-400 text-sm flex-shrink-0"></i>
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Rechercher par nom, email, téléphone..."
                               class="flex-1 text-sm outline-none bg-transparent placeholder:text-gray-400" />
                    </div>
                </div>

                <!-- Filtre type de service -->
                <select name="service_type"
                        class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white hover:border-gray-400 transition-all cursor-pointer">
                    <option value="">Tous les services</option>
                    @foreach($serviceTypes as $value => $label)
                        <option value="{{ $value }}" {{ request('service_type') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                <!-- Filtre urgence -->
                <select name="urgency"
                        class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white hover:border-gray-400 transition-all cursor-pointer">
                    <option value="">Toutes urgences</option>
                    @foreach($urgencies as $value => $label)
                        <option value="{{ $value }}" {{ request('urgency') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                <button type="submit"
                        class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 hover:shadow-md active:scale-95 transition-all">
                    <i class="ki-filled ki-magnifier text-sm"></i>
                    Filtrer
                </button>

                @if(request()->hasAny(['search', 'service_type', 'urgency']))
                <a href="{{ route('service-requests.index') }}"
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
                            Demandeur
                        </th>
                        <th class="px-4 py-4 text-left font-medium text-gray-600 text-xs uppercase tracking-wide">
                            Service
                        </th>
                        <th class="px-4 py-4 text-left font-medium text-gray-600 text-xs uppercase tracking-wide">
                            Date demandée
                        </th>
                        <th class="px-4 py-4 text-left font-medium text-gray-600 text-xs uppercase tracking-wide">
                            Urgence
                        </th>
                        <th class="px-4 py-4 text-left font-medium text-gray-600 text-xs uppercase tracking-wide">
                            Statut
                        </th>
                        <th class="px-4 py-4 text-left font-medium text-gray-600 text-xs uppercase tracking-wide">
                            Reçue le
                        </th>
                        <th class="w-20 px-4 py-4 text-center font-medium text-gray-600 text-xs uppercase tracking-wide">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($serviceRequests as $request)
                    <tr class="border-b hover:bg-gray-50/50 transition-colors" style="border-color: #f5f5f5;">
                        <td class="px-4 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold text-gray-900">
                                    {{ $request->full_name }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ $request->email }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ $request->phone_number }}
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            @php
                            $serviceColors = [
                                'appointment' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'home_visit' => 'bg-orange-50 text-orange-700 border-orange-200',
                                'emergency' => 'bg-red-50 text-red-700 border-red-200',
                                'transport' => 'bg-purple-50 text-purple-700 border-purple-200',
                                'consultation' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                                'other' => 'bg-gray-50 text-gray-700 border-gray-200',
                            ];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium border {{ $serviceColors[$request->service_type] ?? 'bg-gray-50 text-gray-700 border-gray-200' }}">
                                {{ $serviceTypes[$request->service_type] ?? $request->service_type }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            @if($request->preferred_date)
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($request->preferred_date)->format('d/m/Y') }}
                                    </span>
                                    @if($request->preferred_time)
                                        <span class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($request->preferred_time)->format('H:i') }}
                                        </span>
                                    @endif
                                </div>
                            @else
                                <span class="text-sm text-gray-400 italic">Non précisée</span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            @php
                            $urgencyColors = [
                                'low' => 'bg-green-50 text-green-700 border-green-200',
                                'medium' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                'high' => 'bg-red-50 text-red-700 border-red-200',
                            ];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium border {{ $urgencyColors[$request->urgency] ?? 'bg-gray-50 text-gray-700 border-gray-200' }}">
                                {{ $urgencies[$request->urgency] ?? $request->urgency }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            @php
                            $statusColors = [
                                'pending' => 'bg-orange-50 text-orange-700 border-orange-200',
                                'contacted' => 'bg-purple-50 text-purple-700 border-purple-200',
                                'converted' => 'bg-green-50 text-green-700 border-green-200',
                                'rejected' => 'bg-red-50 text-red-700 border-red-200',
                                'cancelled' => 'bg-gray-50 text-gray-700 border-gray-200',
                            ];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium border {{ $statusColors[$request->status] ?? 'bg-gray-50 text-gray-700 border-gray-200' }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                {{ $statuses[$request->status] ?? $request->status }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <span class="text-xs text-gray-500">
                                {{ $request->created_at->format('d/m/Y H:i') }}
                            </span>
                        </td>
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
                        <td colspan="7" class="px-4 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                    <i class="ki-filled ki-message-text text-3xl text-gray-400"></i>
                                </div>
                                <p class="text-gray-700 font-medium">Aucune demande trouvée</p>
                                @if(request()->hasAny(['search', 'service_type', 'urgency']))
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
        @if($serviceRequests->hasPages())
        <div class="px-5 py-4 bg-gray-50/50 border-t border-gray-200 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-sm text-gray-600 font-medium">
                Affichage de <span class="font-bold text-gray-900">{{ $serviceRequests->firstItem() }}</span>
                à <span class="font-bold text-gray-900">{{ $serviceRequests->lastItem() }}</span>
                sur <span class="font-bold text-gray-900">{{ $serviceRequests->total() }}</span> demandes
            </div>
            <div>
                {{ $serviceRequests->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
