<div class="flex items-center justify-center grow bg-center bg-no-repeat page-bg ">

    <!-- Login Card -->
    <div class="card kt-card max-w-[370px] w-full max-w-[440px] w-full">
        <form wire:submit="login" class="card-body flex flex-col gap-5 p-10">
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
                    Système de Gestion Médicale
                </p>
                <div class="flex items-center justify-center text-2xs gap-1.5">
                    <span class="text-gray-500">Nouveau sur la plateforme?</span>
                    <button
                        type="button"
                        wire:click="goToRegister"
                        class="text-primary hover:text-primary-active font-medium"
                    >
                        Créer un compte
                    </button>
                </div>
            </div>

            <!-- Error Alert -->
            @if($loginError)
                <div class="alert alert-error">
                    <div class="flex items-start gap-2.5">
                        <i class="ki-filled ki-information-5 text-lg"></i>
                        <div class="flex flex-col gap-1">
                            <span class="font-semibold text-sm">Erreur de connexion</span>
                            <span class="text-xs">{{ $loginError }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Divider -->
            <div class="flex items-center gap-2 my-2.5">
                <span class="border-t border-gray-200 w-full"></span>
                <span class="text-2xs text-gray-500 font-medium uppercase">Connexion Sécurisée</span>
                <span class="border-t border-gray-200 w-full"></span>
            </div>

            <!-- Email Field -->
            <div class="flex flex-col gap-1">

                <div class="kt-input" data-toggle-password-trigger="toggle">
                    <i class="ki-filled  ki-sms text-gray-500 "></i>
                    <input
                        type="email"
                        wire:model="email"
                        placeholder="email@example.com"
                        class="ki-filled  ki-sms text-gray-500 !pl-0"
                    />
                </div>
                @error('email')
                    <span class="text-danger text-2xs flex items-center gap-1">
                        <i class="ki-filled ki-information-2"></i>
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <!-- Password Field -->
            <div class="flex flex-col gap-1">
                <div class="flex items-center justify-between gap-1">

                    <a href="{{ route('password.request') }}" class="text-sm kt-link shrink-0">
                        Mot de passe oublié ?
                    </a>
                </div>
                <div class="kt-input" data-toggle-password="true">
                    <i class="ki-filled ki-lock text-gray-500"></i>
                    <input
                        type="password"
                        wire:model="password"
                        placeholder="Entrez votre mot de passe"
                        data-toggle-password-input
                        class="!pl-0"
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
                        <i class="ki-filled  ki-information-2"></i>
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <!-- Remember Me -->
            <label class="kt-label">
                <input
                    type="checkbox"
                    wire:model="remember"
                    class="kt-checkbox kt-checkbox-sm"
                />
                <span class="kt-checkbox-label">
                    Se souvenir de moi
                </span>
            </label>

            <!-- Submit Button -->
            <button
                type="submit"
                class="kt-btn btn-primary flex justify-center grow"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove class="kt-btn kt-btn-primary flex justify-center grow flex items-center gap-2">
                    <i class="ki-filled  ki-lock-2"></i>
                    Se connecter
                </span>
                <span wire:loading class="flex items-center gap-2">
                    <span class="kt-spinner kt-spinner-sm"></span>
                    Connexion en cours...
                </span>
            </button>

            <!-- Divider -->
            <div class="flex items-center gap-2 my-2.5">
                <span class="border-t border-gray-200 w-full"></span>
                <span class="text-2xs text-gray-500">ou</span>
                <span class="border-t border-gray-200 w-full"></span>
            </div>

            <!-- Alternative Actions -->
            <div class="grid gap-2.5">
                <button
                    type="button"
                    wire:click="goToRegister"
                    class="btn btn-light btn-sm justify-center"
                >
                    <i class="ki-filled ki-profile-circle"></i>
                    Créer un nouveau compte
                </button>
                <button
                    type="button"
                    class="btn btn-light btn-sm justify-center"
                >
                    <i class="ki-filled ki-question-2"></i>
                    Besoin d'aide ?
                </button>
            </div>

            <!-- Demo Credentials Badge -->
           
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
