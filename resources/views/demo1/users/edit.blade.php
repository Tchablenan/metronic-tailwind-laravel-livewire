@extends('layouts.demo1.base')

@section('content')
<div class="kt-container-fixed">
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 pb-7.5">
        <a href="{{ route('users.index') }}" class="text-sm text-secondary-foreground hover:text-primary">
            Utilisateurs
        </a>
        <i class="ki-filled ki-right text-xs text-secondary-foreground"></i>
        <span class="text-sm font-medium">Modifier {{ $user->full_name }}</span>
    </div>

    {{-- Titre --}}
    <div class="flex flex-wrap items-center justify-between gap-5 pb-7.5">
        <div class="flex flex-col justify-center gap-2">
            <h1 class="text-xl font-medium leading-none text-mono">
                Modifier l'utilisateur
            </h1>
            <div class="flex items-center flex-wrap gap-1.5 font-medium">
                <span class="text-base text-secondary-foreground">
                    {{ $user->email }}
                </span>
            </div>
        </div>
    </div>

    {{-- Messages flash --}}
    @if(session('error'))
    <div class="px-6 py-3 bg-red-100 border border-red-400 text-red-800 rounded-lg mb-5">
        <div class="flex items-center justify-between">
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="text-red-600 hover:text-red-800">
                <i class="ki-filled ki-cross"></i>
            </button>
        </div>
    </div>
    @endif

    {{-- Formulaire --}}
    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

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
                                        <img src="{{ $user->avatar_url }}" class="w-32 h-32 rounded-full object-cover" />
                                    </div>
                                </div>
                                <input type="file" name="avatar" id="avatar" accept="image/*" class="hidden" />
                                <button type="button" onclick="document.getElementById('avatar').click()" class="kt-btn kt-btn-light kt-btn-sm">
                                    <i class="ki-filled ki-picture"></i>
                                    Changer la photo
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
                                <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" class="kt-input @error('first_name') border-danger @enderror" required>
                                @error('first_name')
                                    <span class="text-danger text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Nom --}}
                            <div>
                                <label class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" class="kt-input @error('last_name') border-danger @enderror" required>
                                @error('last_name')
                                    <span class="text-danger text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="kt-input @error('email') border-danger @enderror" required>
                                @error('email')
                                    <span class="text-danger text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Téléphone --}}
                            <div>
                                <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                                <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="kt-input @error('phone_number') border-danger @enderror" required>
                                @error('phone_number')
                                    <span class="text-danger text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Rôle --}}
                            <div>
                                <label class="form-label">Rôle <span class="text-danger">*</span></label>
                                <select name="role" id="role" class="kt-select @error('role') border-danger @enderror" required>
                                    @foreach($roles as $value => $label)
                                        <option value="{{ $value }}" {{ old('role', $user->role) == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <span class="text-danger text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Statut --}}
                            <div>
                                <label class="form-label">Statut</label>
                                <label class="kt-switch flex items-center">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} />
                                    <span class="kt-switch-slider"></span>
                                    <span class="kt-switch-label">Actif</span>
                                </label>
                            </div>

                            {{-- Spécialité (conditionnelle) --}}
                            <div id="speciality-field" style="display: {{ in_array(old('role', $user->role), ['doctor', 'nurse']) ? 'block' : 'none' }};">
                                <label class="form-label">Spécialité</label>
                                <input type="text" name="speciality" value="{{ old('speciality', $user->speciality) }}" class="kt-input @error('speciality') border-danger @enderror">
                                @error('speciality')
                                    <span class="text-danger text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Numéro de licence (conditionnelle) --}}
                            <div id="license-field" style="display: {{ in_array(old('role', $user->role), ['doctor', 'nurse']) ? 'block' : 'none' }};">
                                <label class="form-label">Numéro de licence</label>
                                <input type="text" name="license_number" value="{{ old('license_number', $user->license_number) }}" class="kt-input @error('license_number') border-danger @enderror">
                                @error('license_number')
                                    <span class="text-danger text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer avec boutons --}}
            <div class="kt-card-footer justify-between">
                <a href="{{ route('users.index') }}" class="kt-btn kt-btn-light">
                    <i class="ki-filled ki-left"></i>
                    Retour
                </a>
                <div class="flex gap-2">
                    <a href="{{ route('users.show', $user->id) }}" class="kt-btn kt-btn-light">
                        <i class="ki-filled ki-eye"></i>
                        Voir détails
                    </a>
                    <button type="submit" class="kt-btn kt-btn-primary">
                        <i class="ki-filled ki-check"></i>
                        Enregistrer les modifications
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Preview avatar
document.getElementById('avatar')?.addEventListener('change', function(e) {
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
document.getElementById('role')?.addEventListener('change', function() {
    const showFields = ['doctor', 'nurse'].includes(this.value);
    document.getElementById('speciality-field').style.display = showFields ? 'block' : 'none';
    document.getElementById('license-field').style.display = showFields ? 'block' : 'none';
});
</script>
@endpush
@endsection
