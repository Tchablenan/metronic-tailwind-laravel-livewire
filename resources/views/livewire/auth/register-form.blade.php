<div class="flex items-center justify-center grow bg-center bg-no-repeat page-bg">
    <!-- Register Card -->
    <div class="card kt-card max-w-[370px] w-full max-w-[440px] w-full">
        <form wire:submit="register" class="card-body flex flex-col gap-5 p-10">
            <!-- Logo et Titre -->
            <div class="text-center mb-2.5">
                <div class="flex justify-center mb-5">
                    <div class="relative">
                        <!-- Logo Image -->
                        <img
                            src="{{ asset('assets/media/logos/cmo-vistamd-logo.jpg') }}"
                            alt="CMO VISTAMD Logo"
                            class="h-20 w-auto object-contain"
                        />
                    </div>
                </div>

                <p class="text-sm text-gray-600 mb-1">
                    Créer votre compte
                </p>
                <div class="flex items-center justify-center text-2xs gap-1.5">
                    <span class="text-gray-500">Vous avez déjà un compte?</span>
                    <button
                        type="button"
                        wire:click="goToLogin"
                        class="text-primary hover:text-primary-active font-medium"
                    >
                        Se connecter
                    </button>
                </div>
            </div>

            <!-- Success Alert -->
            @if($registrationSuccess)
                <div class="alert alert-success">
                    <div class="flex items-start gap-2.5">
                        <i class="ki-filled ki-check-circle text-lg"></i>
                        <div class="flex flex-col gap-1">
                            <span class="font-semibold text-sm">Inscription réussie!</span>
                            <span class="text-xs">{{ $registrationSuccess }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Error Alert -->
            @if($registrationError)
                <div class="alert alert-error">
                    <div class="flex items-start gap-2.5">
                        <i class="ki-filled ki-information-5 text-lg"></i>
                        <div class="flex flex-col gap-1">
                            <span class="font-semibold text-sm">Erreur d'inscription</span>
                            <span class="text-xs">{{ $registrationError }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Divider -->
            <div class="flex items-center gap-2 my-2.5">
                <span class="border-t border-gray-200 w-full"></span>
                <span class="text-2xs text-gray-500 font-medium uppercase">Informations personnelles</span>
                <span class="border-t border-gray-200 w-full"></span>
            </div>

            <!-- Nom et Prénom -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Prénom -->
                <div class="flex flex-col gap-1">

                    <div class="kt-input">
                        <i class="ki-filled ki-profile-circle text-gray-500"></i>
                        <input
                            type="text"
                            wire:model="firstName"
                            placeholder="Prenom"
                        />
                    </div>
                    @error('firstName')
                        <span class="text-danger text-2xs flex items-center gap-1">
                            <i class="ki-filled ki-information-2"></i>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Nom -->
                <div class="flex flex-col gap-1">

                    <div class="kt-input">
                        <i class="ki-filled ki-profile-circle text-gray-500"></i>
                        <input
                            type="text"
                            wire:model="lastName"
                            placeholder="Nom"
                        />
                    </div>
                    @error('lastName')
                        <span class="text-danger text-2xs flex items-center gap-1">
                            <i class="ki-filled ki-information-2"></i>
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Email Field -->
            <div class="flex flex-col gap-1">

                <div class="kt-input">
                    <i class="ki-filled ki-sms text-gray-500"></i>
                    <input
                        type="email"
                        wire:model="email"
                        placeholder="email@example.com"
                    />
                </div>
                @error('email')
                    <span class="text-danger text-2xs flex items-center gap-1">
                        <i class="ki-filled ki-information-2"></i>
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <!-- Phone Number Field -->
            <div class="flex flex-col gap-1">
                <div class="kt-input">
                    <i class="ki-filled ki-phone text-gray-500"></i>
                    <input
                        type="tel"
                        wire:model="phoneNumber"
                        placeholder="+225 07 00 00 00 00"
                    />
                </div>
                @error('phoneNumber')
                    <span class="text-danger text-2xs flex items-center gap-1">
                        <i class="ki-filled ki-information-2"></i>
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <!-- Divider -->
            <div class="flex items-center gap-2 my-2.5">
                <span class="border-t border-gray-200 w-full"></span>
                <span class="text-2xs text-gray-500 font-medium uppercase">Sécurité</span>
                <span class="border-t border-gray-200 w-full"></span>
            </div>

            <!-- Password Field -->
            <div class="flex flex-col gap-1">

                <div class="kt-input" data-toggle-password="true">
                    <i class="ki-filled ki-lock text-gray-500"></i>
                    <input
                        type="password"
                        wire:model="password"
                        placeholder="Mot de passe"
                        data-toggle-password-input
                    />
                    <button
                        class="kt-btn kt-btn-sm kt-btn-ghost kt-btn-icon bg-transparent! -me-1.5"
                        data-toggle-password-trigger="true"
                        type="button"
                    >
                        <i class="ki-filled ki-eye text-gray-500 toggle-password-active:hidden"></i>
                        <i class="ki-filled ki-eye-slash text-gray-500 hidden toggle-password-active:block"></i>
                    </button>
                </div>
                @error('password')
                    <span class="text-danger text-2xs flex items-center gap-1">
                        <i class="ki-filled ki-information-2"></i>
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <!-- Password Confirmation Field -->
            <div class="flex flex-col gap-1">

                <div class="kt-input" data-toggle-password="true">
                    <i class="ki-filled ki-lock text-gray-500"></i>
                    <input
                        type="password"
                        wire:model="password_confirmation"
                        placeholder="Confirmer le mot de passe"
                        data-toggle-password-input
                    />
                    <button
                        class="kt-btn kt-btn-sm kt-btn-ghost kt-btn-icon bg-transparent! -me-1.5"
                        data-toggle-password-trigger="true"
                        type="button"
                    >
                        <i class="ki-filled ki-eye text-gray-500 toggle-password-active:hidden"></i>
                        <i class="ki-filled ki-eye-slash text-gray-500 hidden toggle-password-active:block"></i>
                    </button>
                </div>
                @error('password_confirmation')
                    <span class="text-danger text-2xs flex items-center gap-1">
                        <i class="ki-filled ki-information-2"></i>
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <!-- Terms and Conditions -->
            <label class="kt-label">
                <input
                    type="checkbox"
                    wire:model="acceptTerms"
                    class="kt-checkbox kt-checkbox-sm"
                />
                <span class="kt-checkbox-label text-2xs">
                    J'accepte les <a href="#" class="kt-link">conditions d'utilisation</a> et la <a href="#" class="kt-link">politique de confidentialité</a>
                </span>
            </label>
            @error('acceptTerms')
                <span class="text-danger text-2xs flex items-center gap-1">
                    <i class="ki-filled ki-information-2"></i>
                    {{ $message }}
                </span>
            @enderror

            <!-- Submit Button -->
            <button
                type="submit"
                class="kt-btn kt-btn-primary flex justify-center grow"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove class="flex items-center gap-2">
                    <i class="ki-filled ki-check"></i>
                    Créer mon compte
                </span>
                <span wire:loading class="flex items-center gap-2">
                    <span class="kt-spinner kt-spinner-sm"></span>
                    Inscription en cours...
                </span>
            </button>

            <!-- Divider -->
            <div class="flex items-center gap-2 my-2.5">
                <span class="border-t border-gray-200 w-full"></span>
                <span class="text-2xs text-gray-500">ou</span>
                <span class="border-t border-gray-200 w-full"></span>
            </div>

            <!-- Back to Login -->
            <div class="text-center">
                <button
                    type="button"
                    wire:click="goToLogin"
                    class="kt-btn kt-btn-light kt-btn-sm"
                >
                    <i class="ki-filled ki-entrance-left"></i>
                    Retour à la connexion
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Initialize Metronic Password Toggle -->
<script>
    document.addEventListener('livewire:navigated', function() {
        // Initialize password toggle
        const passwordToggles = document.querySelectorAll('[data-toggle-password="true"]');
        passwordToggles.forEach(function(toggle) {
            const trigger = toggle.querySelector('[data-toggle-password-trigger="true"]');
            const input = toggle.querySelector('[data-toggle-password-input]');

            if (trigger && input) {
                trigger.addEventListener('click', function() {
                    if (input.type === 'password') {
                        input.type = 'text';
                        toggle.classList.add('toggle-password-active');
                    } else {
                        input.type = 'password';
                        toggle.classList.remove('toggle-password-active');
                    }
                });
            }
        });
    });
</script>
