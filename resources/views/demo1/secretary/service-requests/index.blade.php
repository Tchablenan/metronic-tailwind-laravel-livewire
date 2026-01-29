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
                Gérer les demandes en attente de traitement
            </p>
        </div>

        {{-- 🆕 BOUTON NOUVELLE DEMANDE --}}
        @can('create', App\Models\ServiceRequest::class)
        <a href="{{ route('secretary.service-requests.create') }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium bg-green-600 text-white rounded-lg hover:bg-green-700 hover:shadow-md active:scale-95 transition-all">
            <i class="ki-filled ki-plus"></i>
            Nouvelle demande
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

        <!-- Table -->
        @if($serviceRequests->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr class="hover:bg-gray-100 transition-colors">
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Patient</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Service</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Urgence</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Statut</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Paiement</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Date</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($serviceRequests as $request)
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex flex-col">
                                <span class="font-medium text-gray-900">{{ $request->full_name }}</span>
                                <span class="text-xs text-gray-600">{{ $request->email }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-block px-2.5 py-1.5 bg-blue-100 text-blue-800 rounded-lg text-xs font-medium">
                                {{ ucfirst(str_replace('_', ' ', $request->service_type)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if($request->urgency === 'high')
                                <span class="inline-block px-2.5 py-1.5 bg-red-100 text-red-800 rounded-lg text-xs font-medium">🔴 Élevée</span>
                            @elseif($request->urgency === 'medium')
                                <span class="inline-block px-2.5 py-1.5 bg-yellow-100 text-yellow-800 rounded-lg text-xs font-medium">🟡 Moyenne</span>
                            @else
                                <span class="inline-block px-2.5 py-1.5 bg-green-100 text-green-800 rounded-lg text-xs font-medium">🟢 Faible</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($request->status === 'pending')
                                <span class="inline-block px-2.5 py-1.5 bg-gray-100 text-gray-800 rounded-lg text-xs font-medium">En attente</span>
                            @elseif($request->status === 'contacted')
                                <span class="inline-block px-2.5 py-1.5 bg-blue-100 text-blue-800 rounded-lg text-xs font-medium">Contacté</span>
                            @elseif($request->status === 'converted')
                                <span class="inline-block px-2.5 py-1.5 bg-green-100 text-green-800 rounded-lg text-xs font-medium">Converti</span>
                            @else
                                <span class="inline-block px-2.5 py-1.5 bg-gray-100 text-gray-800 rounded-lg text-xs font-medium">{{ ucfirst($request->status) }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($request->payment_status === 'paid')
                                <span class="inline-block px-2.5 py-1.5 bg-green-100 text-green-800 rounded-lg text-xs font-medium">✅ Payé</span>
                            @else
                                <span class="inline-block px-2.5 py-1.5 bg-red-100 text-red-800 rounded-lg text-xs font-medium">❌ Non payé</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $request->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('secretary.service-requests.show', $request) }}"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-blue-600 hover:bg-blue-50 transition-colors"
                                   title="Voir détails">
                                    <i class="ki-filled ki-eye text-lg"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-gray-200">
            {{ $serviceRequests->links() }}
        </div>

        @else
        <div class="p-12 text-center">
            <i class="ki-filled ki-questionnaire text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-600 font-medium">Aucune demande de service</p>
            <p class="text-sm text-gray-500 mt-1">Les demandes apparaîtront ici</p>
        </div>
        @endif
    </div>
</div>
@endsection
