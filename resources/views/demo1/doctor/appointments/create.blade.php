@extends('layouts.demo1.base')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <div class="flex items-center gap-4 pb-8">
            <a href="{{ route('appointments.index') }}"
                class="inline-flex items-center justify-center w-10 h-10 rounded-lg hover:bg-gray-100 transition-colors">
                <i class="ki-filled ki-left text-xl text-gray-600"></i>
            </a>
            <div class="flex flex-col gap-1">
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Nouveau Rendez-vous
                </h1>
                <p class="text-sm text-gray-600">
                    Planifier un rendez-vous pour un patient
                </p>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <form method="POST" action="{{ route('appointments.store') }}" class="p-6">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <!-- Patient -->
                    <div>
                        <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Patient <span class="text-red-500">*</span>
                        </label>
                        <select name="patient_id" id="patient_id" required
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white @error('patient_id') border-red-500 @enderror">
                            <option value="">Sélectionner un patient</option>
                            @foreach ($patients as $patient)
                                <option value="{{ $patient->id }}"
                                    {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->full_name }} - {{ $patient->phone_number }}
                                </option>
                            @endforeach
                        </select>
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
                                <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                    Dr. {{ $doctor->full_name }}
                                    @if ($doctor->speciality)
                                        - {{ $doctor->speciality }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
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
                                <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date -->
                    <div>
                        <label for="appointment_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="appointment_date" id="appointment_date"
                            value="{{ old('appointment_date', now()->format('Y-m-d')) }}"
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
                            value="{{ old('appointment_time', '09:00') }}" required
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('appointment_time') border-red-500 @enderror">
                        @error('appointment_time')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                        <div id="availability-message" class="mt-2 text-xs hidden"></div>
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

                    <!-- Lieu -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                            Lieu <span class="text-red-500">*</span>
                        </label>
                        <select name="location" id="location" required
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white @error('location') border-red-500 @enderror">
                            @foreach ($locations as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('location', 'cabinet') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('location')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prix -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                            Prix (FCFA)
                        </label>
                        <input type="number" name="price" id="price" value="{{ old('price') }}" min="0"
                            step="100" placeholder="Ex: 25000"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-500 @enderror">
                        @error('price')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Urgence -->
                    <div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_emergency" id="is_emergency" value="1"
                                {{ old('is_emergency') ? 'checked' : '' }}
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
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none @error('reason') border-red-500 @enderror">{{ old('reason') }}</textarea>
                        @error('reason')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes internes (sur toute la largeur) -->
                    <div class="lg:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Notes internes
                        </label>
                        <textarea name="notes" id="notes" rows="3" placeholder="Notes privées pour le personnel médical..."
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('appointments.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="ki-filled ki-cross text-sm"></i>
                        Annuler
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 hover:shadow-md active:scale-95 transition-all">
                        <i class="ki-filled ki-check text-sm"></i>
                        Créer le rendez-vous
                    </button>
                </div>
                <!-- Erreurs de validation -->
                @if ($errors->any())
                    <div class="m-4 px-4 py-3.5 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <span class="flex items-center gap-2 font-semibold">
                                <i class="ki-filled ki-information-2 text-red-600"></i>
                                Erreurs de validation
                            </span>
                        </div>
                        <ul class="list-disc list-inside text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/appointments.js') }}"></script>
@endpush
