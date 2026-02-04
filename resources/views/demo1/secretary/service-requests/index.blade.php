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

    <!-- ========================================
         STATISTIQUES (PHASE 3)
         ======================================== -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <!-- Stat: En Attente -->
        <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">En Attente</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $statistics['pending'] }}</p>
                </div>
                <div class="text-4xl text-yellow-400 opacity-20">
                    <i class="ki-filled ki-clock"></i>
                </div>
            </div>
        </div>

        <!-- Stat: En Traitement -->
        <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">En Traitement</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $statistics['in_progress'] }}</p>
                </div>
                <div class="text-4xl text-blue-400 opacity-20">
                    <i class="ki-filled ki-gear"></i>
                </div>
            </div>
        </div>

        <!-- Stat: Complétées -->
        <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">Complétées</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $statistics['completed'] }}</p>
                </div>
                <div class="text-4xl text-green-400 opacity-20">
                    <i class="ki-filled ki-check-circle"></i>
                </div>
            </div>
        </div>

        <!-- Stat: Montant Total -->
        <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">Montant Total</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($statistics['total_amount'], 0, ',', ' ') }} FCFA</p>
                </div>
                <div class="text-4xl text-purple-400 opacity-20">
                    <i class="ki-filled ki-money"></i>
                </div>
            </div>
        </div>

        <!-- Stat: % Données Médicales -->
        <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">Données Médicales</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $statistics['medical_data_percentage'] }}%</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $statistics['total_with_medical_data'] }}/{{ $statistics['total'] }}</p>
                </div>
                <div class="text-4xl text-red-400 opacity-20">
                    <i class="ki-filled ki-pulse"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================
         FILTRES (PHASE 2)
         ======================================== -->
    <div class="bg-white rounded-xl shadow-md mb-6">
        <div class="border-b border-gray-200">
            <button type="button" onclick="toggleFilters()"
                    class="w-full flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition-colors">
                <div class="flex items-center gap-3">
                    <i class="ki-filled ki-filter text-lg text-gray-600"></i>
                    <span class="font-semibold text-gray-900">Filtres avancés</span>
                </div>
                <i class="ki-filled ki-down text-gray-600" id="filterToggleIcon"></i>
            </button>
        </div>

        <form method="GET" action="{{ route('secretary.service-requests.index') }}" id="filterForm" class="hidden p-6 bg-gray-50">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <!-- Recherche -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                    <input type="text" name="search" value="{{ $search }}"
                           placeholder="Nom, Email, Téléphone, ID..."
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                </div>

                <!-- Statut -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <select name="status"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <option value="">-- Tous les statuts --</option>
                        @foreach($statusOptions as $key => $label)
                            <option value="{{ $key }}" {{ $status === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Type de Service -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type de Service</label>
                    <select name="service_type"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <option value="">-- Tous les types --</option>
                        @foreach($serviceTypeOptions as $key => $label)
                            <option value="{{ $key }}" {{ $serviceType === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Urgence -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Urgence</label>
                    <select name="urgency"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <option value="">-- Tous les niveaux --</option>
                        @foreach($urgencyOptions as $key => $label)
                            <option value="{{ $key }}" {{ $urgency === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Statut de Paiement -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Statut de Paiement</label>
                    <select name="payment_status"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <option value="">-- Tous les statuts --</option>
                        @foreach($paymentStatusOptions as $key => $label)
                            <option value="{{ $key }}" {{ $paymentStatus === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Début -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date Début</label>
                    <input type="date" name="date_from" value="{{ $dateFrom }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                </div>

                <!-- Date Fin -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date Fin</label>
                    <input type="date" name="date_to" value="{{ $dateTo }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                </div>

                <!-- Checkbox: Assurée -->
                <div class="flex items-end">
                    <label class="flex items-center gap-2.5 p-2.5 bg-white border border-gray-300 rounded-lg hover:border-blue-500 cursor-pointer transition-all">
                        <input type="checkbox" name="has_insurance" value="1" {{ $hasInsurance ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Patients assurés</span>
                    </label>
                </div>

                <!-- Checkbox: Données Médicales -->
                <div class="flex items-end">
                    <label class="flex items-center gap-2.5 p-2.5 bg-white border border-gray-300 rounded-lg hover:border-blue-500 cursor-pointer transition-all">
                        <input type="checkbox" name="has_medical_data" value="1" {{ $hasMedicalData ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Avec données médicales</span>
                    </label>
                </div>
            </div>

            <!-- Boutons Action -->
            <div class="flex flex-wrap items-center gap-3">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 active:scale-95 transition-all">
                    <i class="ki-filled ki-filter"></i>
                    Appliquer les filtres
                </button>
                <a href="{{ route('secretary.service-requests.index') }}"
                   class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-300 text-gray-800 font-medium rounded-lg hover:bg-gray-400 active:scale-95 transition-all">
                    <i class="ki-filled ki-cross"></i>
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Card principal du tableau -->
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
                        <th class="px-4 py-3 text-center font-semibold text-gray-700">Indicateurs</th>
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
                            @elseif($request->status === 'in_progress')
                                <span class="inline-block px-2.5 py-1.5 bg-blue-100 text-blue-800 rounded-lg text-xs font-medium">En traitement</span>
                            @elseif($request->status === 'completed')
                                <span class="inline-block px-2.5 py-1.5 bg-green-100 text-green-800 rounded-lg text-xs font-medium">Complétée</span>
                            @else
                                <span class="inline-block px-2.5 py-1.5 bg-gray-100 text-gray-800 rounded-lg text-xs font-medium">{{ ucfirst($request->status) }}</span>
                            @endif
                        </td>
                        <!-- Indicateurs: Données Médicales, Assurance, Fichier Examen -->
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-3">
                                <!-- Indicateur: Données Médicales -->
                                @if($request->temperature || $request->blood_pressure_systolic || $request->weight || $request->height || $request->known_allergies || $request->current_medications)
                                    <span title="Données médicales présentes" class="inline-flex items-center justify-center w-7 h-7 bg-red-100 text-red-600 rounded-full">
                                        <i class="ki-filled ki-pulse text-sm"></i>
                                    </span>
                                @endif

                                <!-- Indicateur: Assurance -->
                                @if($request->has_insurance)
                                    <span title="Patient assuré" class="inline-flex items-center justify-center w-7 h-7 bg-green-100 text-green-600 rounded-full">
                                        <i class="ki-filled ki-shield-check text-sm"></i>
                                    </span>
                                @endif

                                <!-- Indicateur: Fichier Examen -->
                                @if($request->exam_results_file)
                                    <span title="Fichier d'examen présent" class="inline-flex items-center justify-center w-7 h-7 bg-purple-100 text-purple-600 rounded-full">
                                        <i class="ki-filled ki-file text-sm"></i>
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @if($request->payment_status === 'paid')
                                <span class="inline-block px-2.5 py-1.5 bg-green-100 text-green-800 rounded-lg text-xs font-medium">✅ Payé</span>
                            @elseif($request->payment_status === 'partial')
                                <span class="inline-block px-2.5 py-1.5 bg-yellow-100 text-yellow-800 rounded-lg text-xs font-medium">⚠️ Partiel</span>
                            @else
                                <span class="inline-block px-2.5 py-1.5 bg-red-100 text-red-800 rounded-lg text-xs font-medium">❌ Non payé</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $request->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-2">
                                <!-- Bouton Voir -->
                                <a href="{{ route('secretary.service-requests.show', $request) }}"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-blue-600 hover:bg-blue-50 transition-colors"
                                   title="Voir détails">
                                    <i class="ki-filled ki-eye text-lg"></i>
                                </a>

                                <!-- Bouton Modifier (si autorisé et statut permet) -->
                                @can('update', $request)
                                    @if($request->canBeEdited())
                                        <a href="{{ route('secretary.service-requests.edit', $request) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-green-600 hover:bg-green-50 transition-colors"
                                           title="Modifier">
                                            <i class="ki-filled ki-pencil text-lg"></i>
                                        </a>
                                    @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination avec options -->
        <div class="p-4 border-t border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div class="text-sm text-gray-600">
                    Affichage de <span class="font-semibold">{{ $serviceRequests->firstItem() ?? 0 }}</span> à
                    <span class="font-semibold">{{ $serviceRequests->lastItem() ?? 0 }}</span> sur
                    <span class="font-semibold">{{ $serviceRequests->total() }}</span> demandes
                </div>
            </div>
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

<!-- ========================================
     JAVASCRIPT - Gestion des filtres
     ======================================== -->
<script>
function toggleFilters() {
    const filterForm = document.getElementById('filterForm');
    const filterIcon = document.getElementById('filterToggleIcon');

    if (filterForm.classList.contains('hidden')) {
        filterForm.classList.remove('hidden');
        filterIcon.classList.add('ki-up');
        filterIcon.classList.remove('ki-down');
    } else {
        filterForm.classList.add('hidden');
        filterIcon.classList.remove('ki-up');
        filterIcon.classList.add('ki-down');
    }
}

// Auto-ouvrir les filtres si des filtres sont appliqués
document.addEventListener('DOMContentLoaded', function() {
    const search = '{{ $search }}';
    const status = '{{ $status }}';
    const serviceType = '{{ $serviceType }}';
    const urgency = '{{ $urgency }}';
    const paymentStatus = '{{ $paymentStatus }}';
    const dateFrom = '{{ $dateFrom }}';
    const dateTo = '{{ $dateTo }}';
    const hasInsurance = '{{ $hasInsurance }}';
    const hasMedicalData = '{{ $hasMedicalData }}';

    // Si au moins un filtre est appliqué, ouvrir le formulaire
    if (search || status || serviceType || urgency || paymentStatus || dateFrom || dateTo || hasInsurance || hasMedicalData) {
        const filterForm = document.getElementById('filterForm');
        const filterIcon = document.getElementById('filterToggleIcon');
        filterForm.classList.remove('hidden');
        filterIcon.classList.add('ki-up');
        filterIcon.classList.remove('ki-down');
    }
});
</script>

@endsection
