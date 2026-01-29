<?php

namespace App\Http\Controllers\Demo1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    /**
     * Afficher la liste des utilisateurs
     */
/**
 * Afficher la liste des utilisateurs
 */
public function index(Request $request)
{
    $query = User::query();

    // Recherche
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone_number', 'like', "%{$search}%");
        });
    }

    // Filtre par rôle
    if ($request->filled('role')) {
        $query->where('role', $request->role);
    }

    // Tri
    $sortBy = $request->get('sort_by', 'created_at');
    $sortOrder = $request->get('sort_order', 'desc');
    $query->orderBy($sortBy, $sortOrder);

    // Pagination avec conservation des paramètres de recherche
    $users = $query->paginate(15)->withQueryString();

    // Liste des rôles pour le filtre
    $roles = [
        'doctor' => 'Médecin',
        'nurse' => 'Infirmier(ère)',
        'secretary' => 'Secrétaire',
        'patient' => 'Patient',
        'partner' => 'Partenaire',
        'home_care_member' => 'Équipe à domicile'
    ];

    return view('demo1.users.index', compact('users', 'roles'));
}

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $roles = [
            'doctor' => 'Médecin',
            'nurse' => 'Infirmier(ère)',
            'secretary' => 'Secrétaire',
            'patient' => 'Patient',
            'partner' => 'Partenaire',
            'home_care_member' => 'Équipe à domicile'
        ];

        return view('demo1.users.create', compact('roles'));
    }

    /**
     * Créer un nouvel utilisateur
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'first_name' => 'required|string|min:2|max:255',
            'last_name' => 'required|string|min:2|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:doctor,nurse,secretary,patient,partner,home_care_member',
            'speciality' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'avatar' => 'nullable|image|max:2048',
        ]);

        // Validation conditionnelle pour doctors/nurses
        if (in_array($validated['role'], ['doctor', 'nurse'])) {
            $request->validate([
                'speciality' => 'required|string|max:255',
                'license_number' => 'required|string|max:255',
            ]);
        }

        try {
            // Préparer les données
            $data = [
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'is_active' => $request->boolean('is_active', true),
            ];

            // Champs conditionnels
            if (in_array($validated['role'], ['doctor', 'nurse'])) {
                $data['speciality'] = $validated['speciality'];
                $data['license_number'] = $validated['license_number'];
            }

            // Upload de l'avatar
            if ($request->hasFile('avatar')) {
                $data['avatar_url'] = $request->file('avatar')->store('avatars', 'public');
            }

            // Créer l'utilisateur
            $user = User::create($data);

            // Assigner le rôle Spatie
            $user->assignRole($validated['role']);

            return redirect()->route('users.index')
                           ->with('success', 'Utilisateur créé avec succès !');

        } catch (\Exception $e) {
            return back()->withInput()
                       ->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    /**
     * Afficher les détails d'un utilisateur
     */
    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);
        return view('demo1.users.show', compact('user'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        $roles = [
            'doctor' => 'Médecin',
            'nurse' => 'Infirmier(ère)',
            'secretary' => 'Secrétaire',
            'patient' => 'Patient',
            'partner' => 'Partenaire',
            'home_care_member' => 'Équipe à domicile'
        ];

        return view('demo1.users.edit', compact('user', 'roles'));
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validation
        $validated = $request->validate([
            'first_name' => 'required|string|min:2|max:255',
            'last_name' => 'required|string|min:2|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($id)],
            'phone_number' => 'required|string|max:20',
            'role' => 'required|in:doctor,nurse,secretary,patient,partner,home_care_member',
            'speciality' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'avatar' => 'nullable|image|max:2048',
        ]);

        // Validation conditionnelle
        if (in_array($validated['role'], ['doctor', 'nurse'])) {
            $request->validate([
                'speciality' => 'required|string|max:255',
                'license_number' => 'required|string|max:255',
            ]);
        }

        // Empêcher de désactiver son propre compte
        if ($user->id === Auth::user()->id && !$request->boolean('is_active')) {
            return back()->with('error', 'Vous ne pouvez pas désactiver votre propre compte!');
        }

        try {
            // Préparer les données
            $data = [
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'],
                'role' => $validated['role'],
                'is_active' => $request->boolean('is_active', true),
            ];

            // Champs conditionnels
            if (in_array($validated['role'], ['doctor', 'nurse'])) {
                $data['speciality'] = $validated['speciality'];
                $data['license_number'] = $validated['license_number'];
            } else {
                $data['speciality'] = null;
                $data['license_number'] = null;
            }

            // Upload du nouvel avatar
            if ($request->hasFile('avatar')) {
                // Supprimer l'ancien avatar
                if ($user->avatar_url) {
                    Storage::disk('public')->delete($user->avatar_url);
                }
                $data['avatar_url'] = $request->file('avatar')->store('avatars', 'public');
            }

            // Mettre à jour
            $user->update($data);

            // Mettre à jour le rôle Spatie
            $user->syncRoles([$validated['role']]);

            return redirect()->route('users.index')
                           ->with('success', 'Utilisateur mis à jour avec succès !');

        } catch (\Exception $e) {
            return back()->withInput()
                       ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    /**
     * Supprimer un utilisateur
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Empêcher de supprimer son propre compte
        if ($user->id === Auth::user()->id) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte!');
        }

        try {
            $userName = $user->full_name;
            $user->delete();

            return redirect()->route('users.index')
                           ->with('success', "L'utilisateur {$userName} a été supprimé avec succès !");

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * Changer le statut d'un utilisateur (via AJAX)
     */
    public function toggleStatus($id)
    {
        try {
            $user = User::findOrFail($id);

            // Empêcher de désactiver son propre compte
            if ($user->id === Auth::user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous ne pouvez pas désactiver votre propre compte!'
                ], 403);
            }

            $user->update(['is_active' => !$user->is_active]);

            $status = $user->is_active ? 'activé' : 'désactivé';

            return response()->json([
                'success' => true,
                'message' => "Utilisateur {$status} avec succès!",
                'is_active' => $user->is_active
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Réinitialiser le mot de passe (via AJAX)
     */
    public function resetPassword($id)
    {
        try {
            $user = User::findOrFail($id);

            // Générer un mot de passe temporaire
            $tempPassword = 'Temp@' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $user->update(['password' => Hash::make($tempPassword)]);

            // Send email with temporary password
            $user->notify(new \App\Notifications\NewUserCreatedNotification($user, $tempPassword));

            return response()->json([
                'success' => true,
                'message' => "Mot de passe réinitialisé! Un email a été envoyé.",
                'temp_password' => $tempPassword
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage()
            ], 500);
        }
    }
}
