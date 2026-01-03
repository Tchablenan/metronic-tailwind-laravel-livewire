<div class="flex items-center justify-center min-h-screen bg-center bg-no-repeat bg-cover page-bg">
    <div class="card max-w-[370px] w-full">
        <form wire:submit="resetPassword" class="card-body flex flex-col gap-5 p-10">
            <!-- Logo et Titre -->
            <div class="text-center mb-2.5">
                <div class="flex justify-center mb-5">
                    <img
                        src="{{ asset('assets/media/logos/cmo-vistamd-logo.jpg') }}"
                        alt="CMO VISTAMD Logo"
                        class="h-20 w-auto object-contain"
                    />
                </div>
                <h3 class="text-lg font-medium text-gray-900">
                    Réinitialiser le mot de passe
                </h3>
                <span class="text-sm text-gray-600">
                    Entrez votre nouveau mot de passe
                </span>
            </div>

            <!-- Success Alert -->
            @if($successMessage)
                <div class="alert alert-success">
                    <div class="flex items-start gap-2.5">
                        <i class="ki-filled ki-check-circle text-lg"></i>
                        <div class="flex flex-col gap-1">
                            <span class="font-semibold text-sm">Succès!</span>
                            <span class="text-xs">{{ $successMessage }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Error Alert -->
            @if($errorMessage)
                <div class="alert alert-error">
                    <div class="flex items-start gap-2.5">
                        <i class="ki-filled ki-information-5 text-lg"></i>
                        <div class="flex flex-col gap-1">
                            <span class="font-semibold text-sm">Erreur</span>
                            <span class="text-xs">{{ $errorMessage }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- New Password Field -->
            <div class="flex flex-col gap-1">
                <label class="form-label text-gray-900">
                    Nouveau mot de passe
                </label>
                <label class="kt-input" data-kt-toggle-password="true">
                    <input
                        wire:model="password"
                        placeholder="Entrez un nouveau mot de passe"
                        type="password"
                        data-kt-toggle-password-input
                    />
                    <div class="kt-btn kt-btn-sm kt-btn-ghost kt-btn-icon bg-transparent! -me-1.5" data-kt-toggle-password-trigger="true">
                        <span class="kt-toggle-password-active:hidden">
                            <i class="ki-filled ki-eye text-gray-500"></i>
                        </span>
                        <span class="hidden kt-toggle-password-active:block">
                            <i class="ki-filled ki-eye-slash text-gray-500"></i>
                        </span>
                    </div>
                </label>
                @error('password')
                    <span class="text-danger text-2xs flex items-center gap-1">
                        <i class="ki-filled ki-information-2"></i>
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <!-- Confirm Password Field -->
            <div class="flex flex-col gap-1">
                <label class="form-label font-normal text-gray-900">
                    Confirmer le nouveau mot de passe
                </label>
                <label class="kt-input" data-kt-toggle-password="true">
                    <input
                        wire:model="password_confirmation"
                        placeholder="Re-entrez le nouveau mot de passe"
                        type="password"
                        data-kt-toggle-password-input
                    />
                    <div class="kt-btn kt-btn-sm kt-btn-ghost kt-btn-icon bg-transparent! -me-1.5" data-kt-toggle-password-trigger="true">
                        <span class="kt-toggle-password-active:hidden">
                            <i class="ki-filled ki-eye text-gray-500"></i>
                        </span>
                        <span class="hidden kt-toggle-password-active:block">
                            <i class="ki-filled ki-eye-slash text-gray-500"></i>
                        </span>
                    </div>
                </label>
                @error('password_confirmation')
                    <span class="text-danger text-2xs flex items-center gap-1">
                        <i class="ki-filled ki-information-2"></i>
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="kt-btn kt-btn-primary flex justify-center grow"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove>
                    Soumettre
                </span>
                <span wire:loading class="flex items-center gap-2">
                    <span class="kt-spinner kt-spinner-sm"></span>
                    Réinitialisation...
                </span>
            </button>

            <!-- Back to Login -->
            <div class="flex items-center justify-center gap-1 mt-2">
                <span class="text-xs text-gray-600">
                    Retour à la
                </span>
                <button
                    type="button"
                    wire:click="goToLogin"
                    class="text-xs font-medium kt-link"
                >
                    connexion
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Initialize Password Toggle -->
<script>
    document.addEventListener('livewire:navigated', function() {
        const passwordToggles = document.querySelectorAll('[data-kt-toggle-password="true"]');
        passwordToggles.forEach(function(toggle) {
            const trigger = toggle.querySelector('[data-kt-toggle-password-trigger="true"]');
            const input = toggle.querySelector('[data-kt-toggle-password-input]');

            if (trigger && input) {
                trigger.addEventListener('click', function() {
                    if (input.type === 'password') {
                        input.type = 'text';
                        toggle.classList.add('kt-toggle-password-active');
                    } else {
                        input.type = 'password';
                        toggle.classList.remove('kt-toggle-password-active');
                    }
                });
            }
        });
    });
</script>
