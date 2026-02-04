@extends('layouts.demo1.base')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center gap-4 pb-8">
        <a href="{{ route('secretary.service-requests.show', $serviceRequest->id) }}"
           class="inline-flex items-center justify-center w-10 h-10 rounded-lg hover:bg-gray-100 transition-colors">
            <i class="ki-filled ki-left text-xl text-gray-600"></i>
        </a>
        <div class="flex flex-col gap-1">
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                Modifier la Demande
            </h1>
            <p class="text-sm text-gray-600">
                Demande #{{ $serviceRequest->id }} - {{ $serviceRequest->full_name }}
            </p>
        </div>
    </div>

    <!-- Info box -->
    <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4">
        <div class="flex items-start gap-3">
            <i class="ki-filled ki-information text-blue-600 text-xl flex-shrink-0"></i>
            <div class="text-sm text-blue-900">
                <p class="font-semibold mb-1">Vous pouvez modifier :</p>
                <ul class="list-disc list-inside space-y-1 text-xs">
                    <li>Les informations du patient (nom, email, téléphone)</li>
                    <li>Tous les détails de la demande</li>
                    <li>Les données médicales (triage, assurance, examens)</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Erreurs -->
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
        <form method="POST" action="{{ route('secretary.service-requests.update', $serviceRequest->id) }}" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Informations Patient -->
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ki-filled ki-user text-blue-600"></i>
                    Informations du Patient
                </h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Prénom -->
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Prénom <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="first_name" id="first_name"
                               value="{{ old('first_name', $serviceRequest->first_name) }}" required
                               placeholder="Ex: Jean"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('first_name') border-red-500 @enderror">
                        @error('first_name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nom -->
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="last_name" id="last_name"
                               value="{{ old('last_name', $serviceRequest->last_name) }}" required
                               placeholder="Ex: Koné"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('last_name') border-red-500 @enderror">
                        @error('last_name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email"
                               value="{{ old('email', $serviceRequest->email) }}" required
                               placeholder="patient@example.com"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Téléphone -->
                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Téléphone <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="phone_number" id="phone_number"
                               value="{{ old('phone_number', $serviceRequest->phone_number) }}" required
                               placeholder="+225 07 XX XX XX XX"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone_number') border-red-500 @enderror">
                        @error('phone_number')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- 🏥 SECTION 2 : TRIAGE INITIAL -->
            <div class="mb-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ki-filled ki-pulse text-red-600"></i>
                    Triage Initial (Signes Vitaux)
                </h3>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label for="temperature" class="block text-sm font-medium text-gray-700 mb-2">
                            Température (°C)
                        </label>
                        <input type="number" name="temperature" id="temperature"
                               value="{{ old('temperature', $serviceRequest->temperature) }}"
                               min="30" max="45" step="0.1"
                               placeholder="37.0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('temperature') border-red-500 @enderror">
                        @error('temperature')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="blood_pressure_systolic" class="block text-sm font-medium text-gray-700 mb-2">
                            Tension Systolique (mmHg)
                        </label>
                        <input type="number" name="blood_pressure_systolic" id="blood_pressure_systolic"
                               value="{{ old('blood_pressure_systolic', $serviceRequest->blood_pressure_systolic) }}"
                               min="50" max="250"
                               placeholder="120"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('blood_pressure_systolic') border-red-500 @enderror">
                        @error('blood_pressure_systolic')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="blood_pressure_diastolic" class="block text-sm font-medium text-gray-700 mb-2">
                            Tension Diastolique (mmHg)
                        </label>
                        <input type="number" name="blood_pressure_diastolic" id="blood_pressure_diastolic"
                               value="{{ old('blood_pressure_diastolic', $serviceRequest->blood_pressure_diastolic) }}"
                               min="30" max="150"
                               placeholder="80"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('blood_pressure_diastolic') border-red-500 @enderror">
                        @error('blood_pressure_diastolic')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">
                            Poids (kg)
                        </label>
                        <input type="number" name="weight" id="weight"
                               value="{{ old('weight', $serviceRequest->weight) }}"
                               min="0" max="500" step="0.1"
                               placeholder="70.5"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('weight') border-red-500 @enderror">
                        @error('weight')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="height" class="block text-sm font-medium text-gray-700 mb-2">
                            Taille (cm)
                        </label>
                        <input type="number" name="height" id="height"
                               value="{{ old('height', $serviceRequest->height) }}"
                               min="0" max="300" step="0.1"
                               placeholder="175"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('height') border-red-500 @enderror">
                        @error('height')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="bmi_display" class="block text-sm font-medium text-gray-700 mb-2">
                            IMC (calculé automatiquement)
                        </label>
                        <input type="text" id="bmi_display"
                               readonly
                               placeholder="--"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-center font-semibold">
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="known_allergies" class="block text-sm font-medium text-gray-700 mb-2">
                            Allergies connues
                        </label>
                        <textarea name="known_allergies" id="known_allergies"
                                  rows="2"
                                  placeholder="Ex: Pénicilline, Arachides..."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('known_allergies') border-red-500 @enderror">{{ old('known_allergies', $serviceRequest->known_allergies) }}</textarea>
                        @error('known_allergies')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="current_medications" class="block text-sm font-medium text-gray-700 mb-2">
                            Médicaments actuels
                        </label>
                        <textarea name="current_medications" id="current_medications"
                                  rows="2"
                                  placeholder="Ex: Paracétamol 500mg, Aspirine..."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('current_medications') border-red-500 @enderror">{{ old('current_medications', $serviceRequest->current_medications) }}</textarea>
                        @error('current_medications')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- 🛡️ SECTION 3 : ASSURANCE -->
            <div class="mb-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ki-filled ki-shield-tick text-green-600"></i>
                    Informations Assurance
                </h3>

                <div class="mb-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="has_insurance" id="has_insurance"
                               value="1" {{ old('has_insurance', $serviceRequest->has_insurance) ? 'checked' : '' }}
                               onchange="toggleInsuranceFields()"
                               class="w-4 h-4 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Patient assuré</span>
                    </label>
                </div>

                <div id="insurance_fields" style="display: {{ old('has_insurance', $serviceRequest->has_insurance) ? 'grid' : 'none' }}" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="insurance_company" class="block text-sm font-medium text-gray-700 mb-2">
                            Compagnie d'Assurance
                        </label>
                        <select name="insurance_company" id="insurance_company"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('insurance_company') border-red-500 @enderror">
                            <option value="">-- Sélectionner --</option>
                            <option value="NSIA" {{ old('insurance_company', $serviceRequest->insurance_company) === 'NSIA' ? 'selected' : '' }}>NSIA</option>
                            <option value="Allianz" {{ old('insurance_company', $serviceRequest->insurance_company) === 'Allianz' ? 'selected' : '' }}>Allianz</option>
                            <option value="SAHAM" {{ old('insurance_company', $serviceRequest->insurance_company) === 'SAHAM' ? 'selected' : '' }}>SAHAM</option>
                            <option value="SUNU" {{ old('insurance_company', $serviceRequest->insurance_company) === 'SUNU' ? 'selected' : '' }}>SUNU</option>
                            <option value="Atlantique" {{ old('insurance_company', $serviceRequest->insurance_company) === 'Atlantique' ? 'selected' : '' }}>Atlantique</option>
                            <option value="SONAR" {{ old('insurance_company', $serviceRequest->insurance_company) === 'SONAR' ? 'selected' : '' }}>SONAR</option>
                            <option value="AXA" {{ old('insurance_company', $serviceRequest->insurance_company) === 'AXA' ? 'selected' : '' }}>AXA</option>
                            <option value="Autre" {{ old('insurance_company', $serviceRequest->insurance_company) === 'Autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('insurance_company')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="insurance_policy_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Numéro de Police
                        </label>
                        <input type="text" name="insurance_policy_number" id="insurance_policy_number"
                               value="{{ old('insurance_policy_number', $serviceRequest->insurance_policy_number) }}"
                               placeholder="Ex: POL-2026-123456"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('insurance_policy_number') border-red-500 @enderror">
                        @error('insurance_policy_number')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="insurance_coverage_rate" class="block text-sm font-medium text-gray-700 mb-2">
                            Taux de Couverture (%)
                        </label>
                        <input type="number" name="insurance_coverage_rate" id="insurance_coverage_rate"
                               value="{{ old('insurance_coverage_rate', $serviceRequest->insurance_coverage_rate) }}"
                               min="0" max="100"
                               placeholder="80"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('insurance_coverage_rate') border-red-500 @enderror">
                        @error('insurance_coverage_rate')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="insurance_ceiling" class="block text-sm font-medium text-gray-700 mb-2">
                            Plafond Annuel (FCFA)
                        </label>
                        <input type="number" name="insurance_ceiling" id="insurance_ceiling"
                               value="{{ old('insurance_ceiling', $serviceRequest->insurance_ceiling) }}"
                               min="0"
                               placeholder="500000"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('insurance_ceiling') border-red-500 @enderror">
                        @error('insurance_ceiling')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="insurance_expiry_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Date d'Expiration
                        </label>
                        <input type="date" name="insurance_expiry_date" id="insurance_expiry_date"
                               value="{{ old('insurance_expiry_date', $serviceRequest->insurance_expiry_date?->format('Y-m-d')) }}"
                               min="{{ now()->format('Y-m-d') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('insurance_expiry_date') border-red-500 @enderror">
                        @error('insurance_expiry_date')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- 📋 SECTION 4 : EXAMENS ANTÉRIEURS -->
            <div class="mb-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ki-filled ki-file-down text-purple-600"></i>
                    Examens Déjà Effectués
                </h3>

                <div class="mb-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-sm text-blue-900">
                        <span class="font-semibold">💡 Info:</span> Sélectionnez cette option si le patient a déjà effectué des examens pour ce problème de santé spécifique.
                    </p>
                </div>

                <div class="mb-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="has_previous_exams" id="has_previous_exams"
                               value="1" {{ old('has_previous_exams', $serviceRequest->has_previous_exams) ? 'checked' : '' }}
                               onchange="toggleExamFields()"
                               class="w-4 h-4 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Oui, le patient a déjà fait des examens</span>
                    </label>
                </div>

                <div id="exam_fields" style="display: {{ old('has_previous_exams', $serviceRequest->has_previous_exams) ? 'grid' : 'none' }}" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="previous_exam_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Type d'Examen
                        </label>
                        <select name="previous_exam_type" id="previous_exam_type"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('previous_exam_type') border-red-500 @enderror">
                            <option value="">-- Sélectionner --</option>
                            <option value="laboratory" {{ old('previous_exam_type', $serviceRequest->previous_exam_type) === 'laboratory' ? 'selected' : '' }}>🧪 Analyses de laboratoire</option>
                            <option value="imaging" {{ old('previous_exam_type', $serviceRequest->previous_exam_type) === 'imaging' ? 'selected' : '' }}>📸 Imagerie médicale</option>
                            <option value="ecg" {{ old('previous_exam_type', $serviceRequest->previous_exam_type) === 'ecg' ? 'selected' : '' }}>💓 Électrocardiogramme</option>
                            <option value="covid" {{ old('previous_exam_type', $serviceRequest->previous_exam_type) === 'covid' ? 'selected' : '' }}>🦠 Test COVID-19</option>
                            <option value="checkup" {{ old('previous_exam_type', $serviceRequest->previous_exam_type) === 'checkup' ? 'selected' : '' }}>✅ Bilan de santé</option>
                            <option value="other" {{ old('previous_exam_type', $serviceRequest->previous_exam_type) === 'other' ? 'selected' : '' }}>📋 Autre examen</option>
                        </select>
                        @error('previous_exam_type')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="previous_exam_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom de l'Examen
                        </label>
                        <input type="text" name="previous_exam_name" id="previous_exam_name"
                               value="{{ old('previous_exam_name', $serviceRequest->previous_exam_name) }}"
                               placeholder="Ex: Bilan sanguin complet"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('previous_exam_name') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500">Requis si examen coché</p>
                        @error('previous_exam_name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="lg:col-span-2">
                        <label for="previous_exam_facility" class="block text-sm font-medium text-gray-700 mb-2">
                            Établissement
                        </label>
                        <input type="text" name="previous_exam_facility" id="previous_exam_facility"
                               value="{{ old('previous_exam_facility', $serviceRequest->previous_exam_facility) }}"
                               placeholder="Ex: Laboratoire Central - Cocody"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('previous_exam_facility') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500">Où a été effectué l'examen - Requis si examen coché</p>
                        @error('previous_exam_facility')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="previous_exam_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Date de l'Examen
                        </label>
                        <input type="date" name="previous_exam_date" id="previous_exam_date"
                               value="{{ old('previous_exam_date', $serviceRequest->previous_exam_date?->format('Y-m-d')) }}"
                               max="{{ now()->format('Y-m-d') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('previous_exam_date') border-red-500 @enderror">
                        @error('previous_exam_date')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="previous_exam_file" class="block text-sm font-medium text-gray-700 mb-2">
                            Fichier d'Examen (PDF, JPG, PNG)
                        </label>
                        <input type="file" name="previous_exam_file" id="previous_exam_file"
                               accept=".pdf,.jpg,.jpeg,.png"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('previous_exam_file') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500">Max 5 Mo - Formats: PDF, JPG, PNG</p>
                        @if($serviceRequest->previous_exam_file_path)
                            <div class="mt-2 p-2 bg-green-50 rounded border border-green-200">
                                <p class="text-xs text-green-700">✅ Fichier actuel: {{ basename($serviceRequest->previous_exam_file_path) }}</p>
                                <a href="{{ Storage::url($serviceRequest->previous_exam_file_path) }}" target="_blank" class="text-xs text-green-600 hover:underline">Voir le fichier</a>
                            </div>
                        @endif
                        @error('previous_exam_file')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 5: Détails de la Demande -->
            <div class="mb-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ki-filled ki-notepad text-indigo-600"></i>
                    Détails de la Demande
                </h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Type de Service -->
                    <div>
                        <label for="service_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Type de Service <span class="text-red-500">*</span>
                        </label>
                        <select name="service_type" id="service_type" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('service_type') border-red-500 @enderror">
                            <option value="">-- Sélectionner --</option>
                            <option value="appointment" {{ old('service_type', $serviceRequest->service_type) === 'appointment' ? 'selected' : '' }}>📅 Rendez-vous</option>
                            <option value="consultation" {{ old('service_type', $serviceRequest->service_type) === 'consultation' ? 'selected' : '' }}>🏥 Consultation</option>
                            <option value="home_visit" {{ old('service_type', $serviceRequest->service_type) === 'home_visit' ? 'selected' : '' }}>🏠 Visite à domicile</option>
                            <option value="emergency" {{ old('service_type', $serviceRequest->service_type) === 'emergency' ? 'selected' : '' }}>🚨 Urgence</option>
                            <option value="transport" {{ old('service_type', $serviceRequest->service_type) === 'transport' ? 'selected' : '' }}>🚑 Transport</option>
                        </select>
                        @error('service_type')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Urgence -->
                    <div>
                        <label for="urgency" class="block text-sm font-medium text-gray-700 mb-2">
                            Urgence <span class="text-red-500">*</span>
                        </label>
                        <select name="urgency" id="urgency" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('urgency') border-red-500 @enderror">
                            <option value="">-- Sélectionner --</option>
                            <option value="low" {{ old('urgency', $serviceRequest->urgency) === 'low' ? 'selected' : '' }}>🟢 Faible</option>
                            <option value="medium" {{ old('urgency', $serviceRequest->urgency) === 'medium' ? 'selected' : '' }}>🟡 Moyenne</option>
                            <option value="high" {{ old('urgency', $serviceRequest->urgency) === 'high' ? 'selected' : '' }}>🔴 Élevée</option>
                        </select>
                        @error('urgency')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Message -->
                    <div class="lg:col-span-2">
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                            Message / Détails du Problème
                        </label>
                        <textarea name="message" id="message"
                                  rows="3"
                                  placeholder="Décrivez le problème ou les détails de la demande..."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('message') border-red-500 @enderror">{{ old('message', $serviceRequest->message) }}</textarea>
                        @error('message')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date Préférée -->
                    <div>
                        <label for="preferred_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Date Préférée
                        </label>
                        <input type="date" name="preferred_date" id="preferred_date"
                               value="{{ old('preferred_date', $serviceRequest->preferred_date?->format('Y-m-d')) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('preferred_date') border-red-500 @enderror">
                        @error('preferred_date')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Heure Préférée -->
                    <div>
                        <label for="preferred_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Heure Préférée
                        </label>
                        <input type="time" name="preferred_time" id="preferred_time"
                               value="{{ old('preferred_time', $serviceRequest->preferred_time) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('preferred_time') border-red-500 @enderror">
                        @error('preferred_time')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 6: Paiement -->
            <div class="mb-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="ki-filled ki-money text-green-600"></i>
                    Information de Paiement
                </h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Montant -->
                    <div>
                        <label for="payment_amount" class="block text-sm font-medium text-gray-700 mb-2">
                            Montant (FCFA) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="payment_amount" id="payment_amount"
                               value="{{ old('payment_amount', $serviceRequest->payment_amount) }}" required
                               min="0" step="100"
                               placeholder="25000"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('payment_amount') border-red-500 @enderror">
                        @error('payment_amount')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Méthode Paiement -->
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                            Méthode de Paiement <span class="text-red-500">*</span>
                        </label>
                        <select name="payment_method" id="payment_method" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('payment_method') border-red-500 @enderror">
                            <option value="">-- Sélectionner --</option>
                            <option value="cash" {{ old('payment_method', $serviceRequest->payment_method) === 'cash' ? 'selected' : '' }}>💵 Espèces</option>
                            <option value="card" {{ old('payment_method', $serviceRequest->payment_method) === 'card' ? 'selected' : '' }}>💳 Carte Bancaire</option>
                            <option value="mobile_money" {{ old('payment_method', $serviceRequest->payment_method) === 'mobile_money' ? 'selected' : '' }}>📱 Mobile Money</option>
                            <option value="bank_transfer" {{ old('payment_method', $serviceRequest->payment_method) === 'bank_transfer' ? 'selected' : '' }}>🏦 Virement Bancaire</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="pt-6 border-t border-gray-200 flex gap-3 justify-end">
                <a href="{{ route('secretary.service-requests.show', $serviceRequest->id) }}"
                   class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Annuler
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 hover:shadow-md active:scale-95 transition-all">
                    <i class="ki-filled ki-check mr-2"></i>
                    Enregistrer les Modifications
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function calculateBMI() {
        const weight = parseFloat(document.getElementById('weight').value);
        const height = parseFloat(document.getElementById('height').value);
        const bmiDisplay = document.getElementById('bmi_display');

        if (!weight || !height || height <= 0) {
            bmiDisplay.value = '--';
            return;
        }

        const heightInMeters = height / 100;
        const bmi = (weight / (heightInMeters ** 2)).toFixed(2);
        bmiDisplay.value = bmi;

        // Code couleur basé sur IMC
        if (bmi < 18.5) {
            bmiDisplay.style.color = '#2563eb'; // Bleu
        } else if (bmi < 25) {
            bmiDisplay.style.color = '#16a34a'; // Vert
        } else if (bmi < 30) {
            bmiDisplay.style.color = '#ca8a04'; // Jaune
        } else {
            bmiDisplay.style.color = '#dc2626'; // Rouge
        }
    }

    function toggleInsuranceFields() {
        const checkbox = document.getElementById('has_insurance');
        const insuranceFields = document.getElementById('insurance_fields');
        insuranceFields.style.display = checkbox.checked ? 'grid' : 'none';
    }

    function toggleExamFields() {
        const checkbox = document.getElementById('has_previous_exams');
        const examFields = document.getElementById('exam_fields');
        examFields.style.display = checkbox.checked ? 'grid' : 'none';
    }

    // Initialisation
    document.addEventListener('DOMContentLoaded', function() {
        calculateBMI();
    });

    // Événements
    document.getElementById('weight')?.addEventListener('input', calculateBMI);
    document.getElementById('height')?.addEventListener('input', calculateBMI);
</script>
@endpush
@endsection
