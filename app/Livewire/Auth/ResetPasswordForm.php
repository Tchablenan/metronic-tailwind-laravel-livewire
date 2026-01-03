<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class ResetPasswordForm extends Component
{
    public string $token = '';

    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|string|min:8|confirmed')]
    public string $password = '';

    #[Validate('required|string|min:8')]
    public string $password_confirmation = '';

    public string $successMessage = '';
    public string $errorMessage = '';

    /**
     * Monter le composant avec token et email
     */
public function mount(string $token): void
{
    $this->token = $token;
    $this->email = request()->query('email', '');  // ✅ Récupère depuis l'URL
}

    /**
     * Réinitialiser le mot de passe
     */
public function resetPassword(): void
{
    \Log::info('=== DÉBUT RESET PASSWORD ===');
    \Log::info('Email: ' . $this->email);
    \Log::info('Token: ' . $this->token);

    $this->validate();

    \Log::info('Validation passée');

    $status = Password::reset(
        [
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
            'token' => $this->token,
        ],
        function ($user, $password) {
            \Log::info('Dans la fonction de callback - User: ' . $user->email);

            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();

            \Log::info('Mot de passe sauvegardé');

            event(new PasswordReset($user));
        }
    );

    \Log::info('Status retourné: ' . $status);

    if ($status === Password::PASSWORD_RESET) {
        \Log::info('SUCCESS - Mot de passe réinitialisé');
        $this->successMessage = 'Votre mot de passe a été réinitialisé avec succès! Redirection...';

        sleep(2);
        $this->redirect(route('login'), navigate: true);
    } else {
        \Log::info('ERREUR - Status: ' . $status);
        $this->errorMessage = 'Impossible de réinitialiser le mot de passe. Le lien est peut-être expiré.';
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
        return view('livewire.auth.reset-password-form')
            ->layout('layouts.guest');
    }
}
