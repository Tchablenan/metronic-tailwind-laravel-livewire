@extends('layouts.demo1.base')

@section('content')
<div class="kt-container-fixed">
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 pb-7.5">
        <a href="{{ route('users.index') }}" class="text-sm text-secondary-foreground hover:text-primary">
            Utilisateurs
        </a>
        <i class="ki-filled ki-right text-xs text-secondary-foreground"></i>
        <span class="text-sm font-medium">Créer un utilisateur</span>
    </div>

    {{-- Titre --}}
    <div class="flex flex-wrap items-center justify-between gap-5 pb-7.5">
        <div class="flex flex-col justify-center gap-2">
            <h1 class="text-xl font-medium leading-none text-mono">
                Créer un nouvel utilisateur
            </h1>
            <div class="flex items-center flex-wrap gap-1.5 font-medium">
                <span class="text-base text-secondary-foreground">
                    Remplissez les informations ci-dessous
                </span>
            </div>
        </div>
    </div>

    {{-- Formulaire --}}
    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="kt-card">
            <div class="kt-card-content">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                    {{-- Colonne gauche - Photo de profil --}}
                    <div class="lg:col-span-1">
                        <div class="kt-card">
                            <div class="kt-card-content text-center">
                                <label class="form-label font-medium mb-4 block">Photo de profil</label>
                                <div class="flex justify-center mb-4">
                                    <div id="avatar-preview" class="relative">
                                        <div class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center">
                                            <i class="ki-filled ki-picture text-4xl text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>
                                <input type="file" name="avatar" id="avatar" accept="image/*" class="hidden" />
                                <button type="button" onclick="document.getElementById('avatar').click()" class="kt-btn kt-btn-light kt-btn-sm">
                                    <i class="ki-filled ki-picture"></i>
                                    Choisir une photo
                                </button>
                                @error('avatar')
                                    <span class="text-danger text-xs mt-2 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Colonne droite - Informations --}}
                    <div class="lg:col-span-2">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            {{-- Prénom --}}
                            <div>
                                <label class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" value="{{ old('first_name') }}" class="kt-input @error('first_name') border-danger @enderror" placeholder="Ex: Jean" required>
                                @error('first_name')
                                    <span class="text-danger text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Nom --}}
                            <div>
                                <label class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" class="kt-input @error('last_name') border-danger @enderror" placeholder="Ex: Dupont" required>
                                @error('last_name')
                                    <span class="text-danger text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" class="kt-input @error('email') border-danger @enderror" placeholder="jean.dupont@example.com" required>
                                @error('email')
                                    <span class="text-danger text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Téléphone --}}
                            <div>
                                <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                                <input type="text" name="phone_number" value="{{ old('phone_number') }}" class="kt-input @error('phone_number') border-danger @enderror" placeholder="+225 01 02 03 04 05" required>
                                @error('phone_number')
                                    <span class="text-danger text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Rôle --}}
                            <div>
                                <label class="form-label">Rôle <span class="text-danger">*</span></label>
                                <select name="role" id="role" class="kt-select @error('role') border-danger @enderror" required>
                                    <option value="patient" {{ old('role') == 'patient' ? 'selected' : '' }}>Patient</option>
                                    <option value="doctor" {{ old('role') == 'doctor' ? 'selected' : '' }}>Médecin Chef</option>
                                    <option value="nurse" {{ old('role') == 'nurse' ? 'selected' : '' }}>Infirmier</option>
                                    <option value="secretary" {{ old('role') == 'secretary' ? 'selected' : '' }}>Secrétaire</option>
                                    <option value="partner" {{ old('role') == 'partner' ? 'selected' : '' }}>Partenaire</option>
                                    <option value="home_care_member" {{ old('role') == 'home_care_member' ? 'selected' : '' }}>Équipe Terrain</option>
                                </select>
                                @error('role')
                                    <span class="text-danger text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Statut --}}
                            <div>
                                <label class="form-label">Statut</label>
                                <label class="kt-switch flex items-center">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} />
                                    <span class="kt-switch-slider"></span>
                                    <span class="kt-switch-label">Actif</span>
                                </label>
                            </div>

                            {{-- Spécialité (conditionnelle) --}}
                            <div id="speciality-field" style="display: {{ in_array(old('role'), ['doctor', 'nurse']) ? 'block' : 'none' }};">
                                <label class="form-label">Spécialité <span class="text-danger">*</span></label>
                                <input type="text" name="speciality" value="{{ old('speciality') }}" class="kt-input @error('speciality') border-danger @enderror" placeholder="Ex: Cardiologie">
                                @error('speciality')
                                    <span class="text-danger text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Numéro de licence (conditionnelle) --}}
                            <div id="license-field" style="display: {{ in_array(old('role'), ['doctor', 'nurse']) ? 'block' : 'none' }};">
                                <label class="form-label">Numéro de licence <span class="text-danger">*</span></label>
                                <input type="text" name="license_number" value="{{ old('license_number') }}" class="kt-input @error('license_number') border-danger @enderror" placeholder="Ex: MED-2024-001">
                                @error('license_number')
                                    <span class="text-danger text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Mot de passe --}}
                            <div>
                                <label class="form-label">Mot de passe <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="kt-input @error('password') border-danger @enderror" placeholder="Min. 8 caractères" required>
                                @error('password')
                                    <span class="text-danger text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Confirmation mot de passe --}}
                            <div>
                                <label class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" class="kt-input" placeholder="Répétez le mot de passe" required>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer avec boutons --}}
            <div class="kt-card-footer justify-end">
                <a href="{{ route('users.index') }}" class="kt-btn kt-btn-light">
                    <i class="ki-filled ki-left"></i>
                    Annuler
                </a>
                <button type="submit" class="kt-btn kt-btn-primary">
                    <i class="ki-filled ki-check"></i>
                    Créer l'utilisateur
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Preview avatar
document.getElementById('avatar').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatar-preview').innerHTML = `
                <img src="${e.target.result}" class="w-32 h-32 rounded-full object-cover" />
            `;
        };
        reader.readAsDataURL(file);
    }
});

// Toggle champs conditionnels
document.getElementById('role').addEventListener('change', function() {
    const showFields = ['doctor', 'nurse'].includes(this.value);
    document.getElementById('speciality-field').style.display = showFields ? 'block' : 'none';
    document.getElementById('license-field').style.display = showFields ? 'block' : 'none';
});
</script>
@endpush
@endsection
