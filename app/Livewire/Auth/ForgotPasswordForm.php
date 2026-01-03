<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Password;

class ForgotPasswordForm extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    public string $successMessage = '';
    public string $errorMessage = '';

    /**
     * Envoyer le lien de réinitialisation
     */
    public function sendResetLink(): void
    {
        $this->validate();

        // Envoyer le lien de réinitialisation
        $status = Password::sendResetLink(
            ['email' => $this->email]
        );

        if ($status === Password::RESET_LINK_SENT) {
            $this->successMessage = 'Un email de réinitialisation a été envoyé à votre adresse email.';
            $this->email = ''; // Vider le champ
        } else {
            $this->errorMessage = 'Impossible d\'envoyer le lien de réinitialisation. Vérifiez votre email.';
        }
    }

    /**
     * Retour au login
     */
    public function goToLogin(): void
    {
        $this->redirect(route('login'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.forgot-password-form')
            ->layout('layouts.guest');
    }
}
