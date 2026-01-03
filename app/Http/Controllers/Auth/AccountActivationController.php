<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AccountActivationController extends Controller
{
    /**
     * Afficher le formulaire d'activation
     */
    public function show($token)
    {
        $user = User::where('activation_token', $token)
            ->where('activation_token_expires_at', '>', now())
            ->first();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Ce lien d\'activation est invalide ou a expiré.');
        }

        return view('auth.activate', compact('user', 'token'));
    }

    /**
     * Activer le compte et définir le mot de passe
     */
    public function activate(Request $request, $token)
    {
        $user = User::where('activation_token', $token)
            ->where('activation_token_expires_at', '>', now())
            ->first();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Ce lien d\'activation est invalide ou a expiré.');
        }

        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        ]);

        // Activer le compte
        $user->update([
            'password' => Hash::make($request->password),
            'is_active' => true,
            'email_verified_at' => now(),
            'activation_token' => null,
            'activation_token_expires_at' => null,
        ]);

        // Connecter automatiquement l'utilisateur
        auth()->login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Votre compte a été activé avec succès ! Bienvenue sur CMO VISTAMD.');
    }
}
