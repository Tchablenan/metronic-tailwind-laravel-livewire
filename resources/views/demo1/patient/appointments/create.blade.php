@extends('layouts.demo1.base')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <!-- Header -->
    <div class="flex items-center gap-4 pb-8">
        <a href="{{ route('appointments.index') }}"
           class="inline-flex items-center justify-center w-10 h-10 rounded-lg hover:bg-gray-100 transition-colors">
            <i class="ki-filled ki-left text-xl text-gray-600"></i>
        </a>
        <div class="flex flex-col gap-1">
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                Demander un Rendez-vous
            </h1>
            <p class="text-sm text-gray-600">
                Remplissez le formulaire pour demander un rendez-vous médical
            </p>
        </div>
    </div>

    <!-- Erreurs de validation -->
    @if ($errors->any())
        <div class="mb-6 px-4 py-3.5 bg-red-50 border border-red-200 text-red-800 rounded-lg">
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

    <!-- Formulaire -->
    <div class="bg-white rounded-xl shadow-md">
        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100">
            <h2 class="text-lg font-semibold text-gray-900">Informations du rendez-vous</h2>
            <p class="text-sm text-gray-600 mt-1">Les champs marqués d'un * sont obligatoires</p>
        </div>

        <form method="POST" action="{{ route('appointments.store') }}" class="p-6">
            @csrf

            <!-- Patient ID caché -->
            <input type="hidden" name="patient_id" value="{{ auth()->id() }}">

            <div class="space-y-6">

                <!-- Type de consultation -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                        Type de consultation <span class="text-red-500">*</span>
                    </label>
                    <select name="type" id="type" required
                            class="w-full px-4 py-3 text-sm border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white @error('type') border-red-500 @enderror">
                        @foreach($types as $value => $label)
                            <option value="{{ $value }}" {{ old('type', 'consultation') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('type')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date et Heure -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="appointment_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Date souhaitée <span class="text-red-500">*</span>
                        </label>
                        <input type="date"
                               name="appointment_date"
                               id="appointment_date"
                               value="{{ old('appointment_date', now()->addDay()->format('Y-m-d')) }}"
                               min="{{ now()->format('Y-m-d') }}"
                               required
                               class="w-full px-4 py-3 text-sm border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('appointment_date') border-red-500 @enderror">
                        @error('appointment_date')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="appointment_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Heure souhaitée <span class="text-red-500">*</span>
                        </label>
                        <input type="time"
                               name="appointment_time"
                               id="appointment_time"
                               value="{{ old('appointment_time', '09:00') }}"
                               required
                               class="w-full px-4 py-3 text-sm border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('appointment_time') border-red-500 @enderror">
                        @error('appointment_time')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Médecin préféré (optionnel) -->
                <div>
                    <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Médecin préféré (optionnel)
                    </label>
                    <select name="doctor_id" id="doctor_id"
                            class="w-full px-4 py-3 text-sm border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white @error('doctor_id') border-red-500 @enderror">
                        <option value="">Aucune préférence</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                Dr. {{ $doctor->full_name }}
                                @if($doctor->speciality)
                                    - {{ $doctor->speciality }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Si vous ne choisissez pas, un médecin vous sera assigné automatiquement.</p>
                    @error('doctor_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Lieu -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                        Lieu de consultation <span class="text-red-500">*</span>
                    </label>
                    <select name="location" id="location" required
                            class="w-full px-4 py-3 text-sm border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white @error('location') border-red-500 @enderror">
                        @foreach($locations as $value => $label)
                            <option value="{{ $value }}" {{ old('location', 'cabinet') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('location')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- S'agit-il d'une urgence ? -->
                <div class="p-4 bg-yellow-50 border-2 border-yellow-200 rounded-lg">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox"
                               name="is_emergency"
                               id="is_emergency"
                               value="1"
                               {{ old('is_emergency') ? 'checked' : '' }}
                               class="w-5 h-5 mt-0.5 rounded border-gray-300 text-red-600 focus:ring-red-500">
                        <div>
                            <span class="text-sm font-semibold text-gray-900">
                                S'agit-il d'une urgence médicale ?
                            </span>
                            <p class="text-xs text-gray-600 mt-1">
                                Cochez cette case si vous avez besoin d'une consultation urgente (douleur aiguë, fièvre élevée, etc.)
                            </p>
                        </div>
                    </label>
                </div>

                <!-- Motif de consultation -->
                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Motif de consultation <span class="text-red-500">*</span>
                    </label>
                    <textarea name="reason"
                              id="reason"
                              rows="4"
                              required
                              placeholder="Décrivez brièvement la raison de votre consultation (symptômes, durée, etc.)..."
                              class="w-full px-4 py-3 text-sm border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none @error('reason') border-red-500 @enderror">{{ old('reason') }}</textarea>
                    @error('reason')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Informations importantes -->
                <div class="p-4 bg-blue-50 border-2 border-blue-200 rounded-lg">
                    <div class="flex items-start gap-3">
                        <i class="ki-filled ki-information text-blue-600 text-xl mt-0.5"></i>
                        <div>
                            <h3 class="font-semibold text-blue-900 text-sm mb-2">À savoir avant votre rendez-vous</h3>
                            <ul class="text-xs text-blue-800 space-y-1">
                                <li>• Votre demande sera examinée par notre équipe médicale</li>
                                <li>• Vous recevrez une confirmation par email et SMS</li>
                                <li>• En cas d'urgence grave, appelez directement le cabinet ou le 185</li>
                                <li>• Prévoyez d'arriver 10 minutes avant l'heure du rendez-vous</li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Boutons -->
            <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('appointments.index') }}"
                   class="inline-flex items-center gap-2 px-5 py-3 text-sm font-medium text-gray-700 bg-white border-2 border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="ki-filled ki-cross text-sm"></i>
                    Annuler
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 hover:shadow-lg active:scale-95 transition-all">
                    <i class="ki-filled ki-check text-sm"></i>
                    Envoyer ma demande
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/appointments.js') }}"></script>
@endpush
```
