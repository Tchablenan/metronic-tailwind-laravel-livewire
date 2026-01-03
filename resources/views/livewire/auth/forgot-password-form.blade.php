

<div class="flex items-center justify-center grow bg-center bg-no-repeat page-bg">
    <div class="kt-card max-w-[370px] w-full  max-w-[440px] ">
        <form wire:submit="sendResetLink" class="kt-card-content flex flex-col gap-5 p-10">
            <!-- Logo et Titre -->
            <div class="text-center mb-2.5">
                <div class="flex justify-center mb-5">
                    <img
                        src="{{ asset('assets/media/logos/cmo-vistamd-logo.jpg') }}"
                        alt="CMO VISTAMD Logo"
                        class="h-20 w-auto object-contain"
                    />
                </div>
                <h3 class="text-lg font-medium text-mono">
                    Votre Email
                </h3>
                <span class="text-sm text-gray-600">
                    Entrez votre email pour réinitialiser le mot de passe
                </span>
            </div>

            <!-- Success Alert -->
            @if($successMessage)
                <div class="alert alert-success">
                    <div class="flex items-start gap-2.5">
                        <i class="ki-filled ki-check-circle text-lg"></i>
                        <div class="flex flex-col gap-1">
                            <span class="font-semibold text-sm">Email envoyé!</span>
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

            <!-- Email Field -->
            <div class="flex flex-col gap-1">
                <label class="form-label font-normal text-gray-900">
                    Email
                </label>
                <input
                    class="kt-input"
                    wire:model="email"
                    placeholder="email@email.com"
                    type="email"
                />
                @error('email')
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
                <span wire:loading.remove class="flex items-center gap-2">
                    Continuer
                    <i class="ki-filled ki-black-right"></i>
                </span>
                <span wire:loading class="flex items-center gap-2">
                    <span class="kt-spinner kt-spinner-sm"></span>
                    Envoi en cours...
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
