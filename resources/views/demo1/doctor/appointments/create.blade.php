@extends('layouts.demo1.base')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <div class="flex items-center gap-4 pb-8">
            <a href="{{ isset($serviceRequest) ? route('service-requests.show', $serviceRequest) : route('appointments.index') }}"
                class="inline-flex items-center justify-center w-10 h-10 rounded-lg hover:bg-gray-100 transition-colors">
                <i class="ki-filled ki-left text-xl text-gray-600"></i>
            </a>
            <div class="flex flex-col gap-1">
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    {{ isset($serviceRequest) ? 'Convertir en Rendez-vous' : 'Nouveau Rendez-vous' }}
                </h1>
                <p class="text-sm text-gray-600">
                    {{ isset($serviceRequest) ? 'Créer un RDV depuis la demande #' . $serviceRequest->id : 'Planifier un rendez-vous pour un patient' }}
                </p>
            </div>
        </div>

        <!-- Bloc d'information ServiceRequest -->
        @if (isset($serviceRequest))
            <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="ki-filled ki-message-text text-white text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-bold text-blue-900 mb-2">
                            Informations de la demande
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 text-xs">
                            <div>
                                <span class="text-blue-700 font-medium">Demandeur :</span>
                                <span class="text-blue-900">{{ $serviceRequest->full_name }}</span>
                            </div>
                            <div>
                                <span class="text-blue-700 font-medium">Email :</span>
                                <span class="text-blue-900">{{ $serviceRequest->email }}</span>
                            </div>
                            <div>
                                <span class="text-blue-700 font-medium">Téléphone :</span>
                                <span class="text-blue-900">{{ $serviceRequest->phone_number }}</span>
                            </div>
                            <div>
                                <span class="text-blue-700 font-medium">Service :</span>
                                <span class="text-blue-900">
                                    @switch($serviceRequest->service_type)
                                        @case('appointment')
                                            Rendez-vous
                                        @break

                                        @case('home_visit')
                                            Visite à domicile
                                        @break

                                        @case('emergency')
                                            Urgence
                                        @break

                                        @case('transport')
                                            Transport
                                        @break

                                        @case('consultation')
                                            Consultation
                                        @break

                                        @default
                                            {{ $serviceRequest->service_type }}
                                    @endswitch
                                </span>
                            </div>
                            <div>
                                <span class="text-blue-700 font-medium">Urgence :</span>
                                <span class="text-blue-900">
                                    @if ($serviceRequest->urgency === 'high')
                                        🔴 Élevée
                                    @elseif($serviceRequest->urgency === 'medium')
                                        🟡 Moyenne
                                    @else
                                        🟢 Faible
                                    @endif
                                </span>
                            </div>
                            <div>
                                <span class="text-blue-700 font-medium">Montant payé :</span>
                                <span
                                    class="text-blue-900 font-semibold">{{ number_format($serviceRequest->payment_amount, 0, ',', ' ') }}
                                    FCFA</span>
                            </div>
                        </div>
                        @if ($serviceRequest->message)
                            <div class="mt-3 pt-3 border-t border-blue-200">
                                <span class="text-blue-700 font-medium text-xs">Message :</span>
                                <p class="text-blue-900 text-xs mt-1">{{ $serviceRequest->message }}</p>
                            </div>
                        @endif
                        @if ($patient)
                            <div class="mt-3 p-2 bg-green-100 border border-green-300 rounded">
                                <i class="ki-filled ki-check-circle text-green-600"></i>
                                <span class="text-xs text-green-800 font-medium">
                                    Patient existant trouvé : {{ $patient->full_name }}
                                </span>
                            </div>
                        @else
                            <div class="mt-3 p-2 bg-yellow-100 border border-yellow-300 rounded">
                                <i class="ki-filled ki-information text-yellow-600"></i>
                                <span class="text-xs text-yellow-800 font-medium">
                                    Un nouveau compte patient sera créé automatiquement pour {{ $serviceRequest->email }}
                                </span>
                            </div>
                        @endif

                        {{-- ✅ AJOUTE CE BLOC POUR AFFICHER LES WARNINGS --}}
                        @if (isset($patientWarning))
                            <div class="mt-3 p-3 bg-red-100 border-l-4 border-red-500 rounded">
                                <div class="flex items-start gap-2">
                                    <i class="ki-filled ki-information text-red-600 text-lg flex-shrink-0 mt-0.5"></i>
                                    <div>
                                        <p class="text-sm font-bold text-red-900">Attention - Incohérence détectée</p>
                                        <p class="text-xs text-red-800 mt-1">{{ $patientWarning }}</p>
                                        <p class="text-xs text-red-700 mt-2 font-medium">
                                            → Vérifiez les informations et sélectionnez manuellement le bon patient
                                            ci-dessous, ou laissez vide pour créer un nouveau compte.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Erreurs de validation -->
        @if ($errors->any())
            <div class="mb-6 px-4 py-3.5 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                <div class="flex items-center gap-2 font-semibold mb-2">
                    <i class="ki-filled ki-information-2 text-red-600"></i>
                    Erreurs de validation
                </div>
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulaire -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <form method="POST" action="{{ route('appointments.store') }}" class="p-6">
                @csrf

                <!-- Champ caché pour service_request_id -->
                @if (isset($serviceRequest))
                    <input type="hidden" name="service_request_id" value="{{ $serviceRequest->id }}">
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <!-- Patient -->
                    <div class="{{ isset($patient) ? 'lg:col-span-2' : '' }}">
                        <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Patient @if (!isset($serviceRequest))
                                <span class="text-red-500">*</span>
                            @endif
                        </label>

                        @if (isset($patient))
                            <!-- Patient trouvé : affichage lecture seule -->
                            <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                            <div class="flex items-center gap-3 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                <img src="{{ $patient->avatar_url }}" alt="" class="w-12 h-12 rounded-full">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $patient->full_name }}</p>
                                    <p class="text-xs text-gray-600">{{ $patient->email }} • {{ $patient->phone_number }}
                                    </p>
                                </div>
                            </div>
                        @else
                            <!-- Sélection patient -->
                            <select name="patient_id" id="patient_id" @if (!isset($serviceRequest)) required @endif
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white @error('patient_id') border-red-500 @enderror">
                                <option value="">
                                    {{ isset($serviceRequest) ? 'Créer un nouveau patient' : 'Sélectionner un patient' }}
                                </option>
                                @foreach ($patients as $p)
                                    <option value="{{ $p->id }}"
                                        {{ old('patient_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->full_name }} - {{ $p->phone_number }}
                                    </option>
                                @endforeach
                            </select>
                            @if (isset($serviceRequest))
                                <p class="text-xs text-gray-500 mt-1">
                                    Laissez vide pour créer automatiquement un compte pour {{ $serviceRequest->email }}
                                </p>
                            @endif
                        @endif
                        @error('patient_id')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Médecin -->
                
                    <div>
                        <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Médecin
                        </label>
                        <select name="doctor_id" id="doctor_id"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white @error('doctor_id') border-red-500 @enderror">
                            <option value="">Non assigné</option>
                            @foreach ($doctors as $doctor)
                                <option value="{{ $doctor->id }}"
                                    {{ old('doctor_id') == $doctor->id || (!old('doctor_id') && $doctor->id === Auth::id()) ? 'selected' : '' }}>
                                    Dr. {{ $doctor->full_name }}
                                </option>
                            @endforeach
                        </select>

                        {{-- ✅ Message si aucun autre médecin --}}
                        @if ($doctors->isEmpty() && Auth::user()->isChief())
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="ki-filled ki-information-2 text-gray-400"></i>
                                Vous serez automatiquement assigné comme médecin responsable
                            </p>
                        @endif

                        @error('doctor_id')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Infirmier -->
                    <div>
                        <label for="nurse_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Infirmier(ère)
                        </label>
                        <select name="nurse_id" id="nurse_id"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white @error('nurse_id') border-red-500 @enderror">
                            <option value="">Non assigné</option>
                            @foreach ($nurses as $nurse)
                                <option value="{{ $nurse->id }}" {{ old('nurse_id') == $nurse->id ? 'selected' : '' }}>
                                    {{ $nurse->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('nurse_id')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                            Type de rendez-vous <span class="text-red-500">*</span>
                        </label>
                        <select name="type" id="type" required
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white @error('type') border-red-500 @enderror">
                            @foreach ($types as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('type') == $value ||
                                    (isset($serviceRequest) &&
                                        (($serviceRequest->service_type === 'appointment' && $value === 'consultation') ||
                                            ($serviceRequest->service_type === 'consultation' && $value === 'consultation') ||
                                            ($serviceRequest->service_type === 'emergency' && $value === 'emergency') ||
                                            ($serviceRequest->service_type === 'home_visit' && $value === 'home_visit')))
                                        ? 'selected'
                                        : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Lieu -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                            Lieu <span class="text-red-500">*</span>
                        </label>
                        <select name="location" id="location" required
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white @error('location') border-red-500 @enderror">
                            @foreach ($locations as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('location') == $value || (isset($serviceRequest) && $serviceRequest->service_type === 'home_visit' && $value === 'home') || (!old('location') && !isset($serviceRequest) && $value === 'cabinet') ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('location')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date -->
                    <div>
                        <label for="appointment_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="appointment_date" id="appointment_date"
                            value="{{ old('appointment_date', isset($serviceRequest) && $serviceRequest->preferred_date ? $serviceRequest->preferred_date->format('Y-m-d') : now()->addDay()->format('Y-m-d')) }}"
                            min="{{ now()->format('Y-m-d') }}" required
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('appointment_date') border-red-500 @enderror">
                        @error('appointment_date')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Heure -->
                    <div>
                        <label for="appointment_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Heure <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="appointment_time" id="appointment_time"
                            value="{{ old('appointment_time', isset($serviceRequest) && $serviceRequest->preferred_time ? \Carbon\Carbon::parse($serviceRequest->preferred_time)->format('H:i') : '10:00') }}"
                            required
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('appointment_time') border-red-500 @enderror">
                        @error('appointment_time')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Durée -->
                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">
                            Durée (minutes) <span class="text-red-500">*</span>
                        </label>
                        <select name="duration" id="duration" required
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white @error('duration') border-red-500 @enderror">
                            <option value="15" {{ old('duration') == 15 ? 'selected' : '' }}>15 minutes</option>
                            <option value="30" {{ old('duration', 30) == 30 ? 'selected' : '' }}>30 minutes</option>
                            <option value="45" {{ old('duration') == 45 ? 'selected' : '' }}>45 minutes</option>
                            <option value="60" {{ old('duration') == 60 ? 'selected' : '' }}>1 heure</option>
                            <option value="90" {{ old('duration') == 90 ? 'selected' : '' }}>1h30</option>
                            <option value="120" {{ old('duration') == 120 ? 'selected' : '' }}>2 heures</option>
                        </select>
                        @error('duration')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prix -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                            Prix (FCFA)
                        </label>
                        <input type="number" name="price" id="price"
                            value="{{ old('price', isset($serviceRequest) ? $serviceRequest->payment_amount : '') }}"
                            min="0" step="100" placeholder="Ex: 25000"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-500 @enderror">
                        @error('price')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Urgence -->
                    <div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_emergency" id="is_emergency" value="1"
                                {{ old('is_emergency') || (isset($serviceRequest) && $serviceRequest->urgency === 'high') ? 'checked' : '' }}
                                class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                            <span class="text-sm font-medium text-gray-700">
                                Marquer comme urgence
                            </span>
                        </label>
                    </div>

                    <!-- Motif (sur toute la largeur) -->
                    <div class="lg:col-span-2">
                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Motif de consultation
                        </label>
                        <textarea name="reason" id="reason" rows="3" placeholder="Décrivez brièvement le motif du rendez-vous..."
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none @error('reason') border-red-500 @enderror">{{ old('reason', isset($serviceRequest) ? $serviceRequest->message : '') }}</textarea>
                        @error('reason')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes internes (sur toute la largeur) -->
                    <div class="lg:col-span-2">
                        <label for="patient_notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Notes pour le patient
                        </label>
                        <textarea name="patient_notes" id="patient_notes" rows="2" placeholder="Message visible par le patient..."
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none @error('patient_notes') border-red-500 @enderror">{{ old('patient_notes', isset($serviceRequest) ? 'Rendez-vous créé suite à votre demande en ligne.' : '') }}</textarea>
                        @error('patient_notes')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ isset($serviceRequest) ? route('service-requests.show', $serviceRequest) : route('appointments.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="ki-filled ki-cross text-sm"></i>
                        Annuler
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 hover:shadow-md active:scale-95 transition-all">
                        <i class="ki-filled ki-check text-sm"></i>
                        {{ isset($serviceRequest) ? 'Créer le rendez-vous et convertir' : 'Créer le rendez-vous' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/appointments.js') }}"></script>
@endpush
