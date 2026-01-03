<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginForm extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    public string $loginError = '';

    /**
     * Traiter le formulaire de login
     */
    public function login(): void
    {
        $this->validate();

        // Tenter de connecter l'utilisateur
        if (Auth::attempt([
            'email' => $this->email,
            'password' => $this->password,
        ], $this->remember)) {

            // Vérifier que l'utilisateur est actif
            $user = Auth::user();
            if (!$user->is_active) {
                Auth::logout();
                $this->loginError = 'Votre compte a été désactivé. Contactez l\'administrateur.';
                return;
            }

            // Succès - rediriger vers dashboard
            session()->regenerate();
            $this->redirect(route('demo1.index'), navigate: true);
        } else {
            // Échec - afficher erreur
            $this->loginError = 'Email ou mot de passe incorrect.';
            $this->password = '';
        }
    }

    /**
     * Aller à la page d'inscription
     */
    public function goToRegister(): void
    {
        $this->redirect(route('register'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.login-form')
            ->layout('layouts.guest'); // Layout sans auth
    }
}
