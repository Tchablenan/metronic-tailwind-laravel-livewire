<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterForm extends Component
{
    #[Validate('required|string|min:2')]
    public string $firstName = '';

    #[Validate('required|string|min:2')]
    public string $lastName = '';

    #[Validate('required|email|unique:users')]
    public string $email = '';

    #[Validate('required|string|min:8|confirmed')]
    public string $password = '';

    #[Validate('required|string|min:8')]
    public string $password_confirmation = '';

    #[Validate('required|regex:/^\+?[1-9]\d{1,14}$/')]
    public string $phoneNumber = '';

    public string $role = 'patient'; // Par défaut patient

    public string $registrationError = '';

    public string $registrationSuccess = '';

    /**
     * Traiter le formulaire d'inscription
     */
    public function register(): void
    {
        $this->validate();

        try {
            // Créer l'utilisateur
            $user = User::create([
                'first_name' => $this->firstName,
                'last_name' => $this->lastName,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'phone_number' => $this->phoneNumber,
                'role' => $this->role,
                'is_active' => true,
            ]);

            // Assigner le rôle Spatie
            $user->assignRole($this->role);

            // Succès
            $this->registrationSuccess = 'Inscription réussie! Redirection...';

            // Connexion automatique
            Auth::login($user);
            session()->regenerate();

            // Redirection
            $this->redirect(route('demo1.index'), navigate: true);

        } catch (\Exception $e) {
            $this->registrationError = 'Erreur lors de l\'inscription: ' . $e->getMessage();
        }
    }

    /**
     * Aller à la page de login
     */
    public function goToLogin(): void
    {
        $this->redirect(route('login'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.register-form')
            ->layout('layouts.guest'); // Layout sans auth
    }
}
