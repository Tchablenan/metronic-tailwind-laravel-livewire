#!/bin/bash
# Quick Commands - Phase 1 Dashboards

# ============================================
# VÉRIFICATIONS TECHNIQUES
# ============================================

echo "🔍 Vérification PHP..."
php -l app/Http/Controllers/DashboardController.php

echo "🔍 Vérification Configuration..."
php artisan config:cache

echo "🔍 Vérification Routes..."
php artisan route:list | grep -i dashboard

# ============================================
# COMMANDES UTILES
# ============================================

# Lancer serveur Laravel
php artisan serve

# Lancer Tinker pour tests
php artisan tinker

# Voir tous les utilisateurs
php artisan tinker << EOF
use App\Models\User;
User::all(['id', 'first_name', 'last_name', 'email', 'role', 'is_chief'])->toArray();
EOF

# Créer médecin régulier de test
php artisan tinker << EOF
use App\Models\User, Illuminate\Support\Facades\Hash;
$doctor = User::create([
    'first_name' => 'Adjoua',
    'last_name' => 'N\'Dri',
    'email' => 'doctor.regular@cmovistamd.local',
    'password' => Hash::make('password123'),
    'role' => 'doctor',
    'is_chief' => false,
    'is_active' => true,
]);
echo "✅ Médecin créé: {$doctor->full_name}";
EOF

# ============================================
# COMMANDES GIT
# ============================================

# Voir les changements
git status

# Ajouter les changements
git add app/Http/Controllers/DashboardController.php
git add resources/views/demo1/doctor/dashboard.blade.php
git add resources/views/demo1/doctor/dashboard-chief.blade.php
git add routes/web.php

# Commit
git commit -m "Phase 1: Dashboards différenciés pour médecins réguliers et chefs"

# ============================================
# NETTOYAGE DU CACHE
# ============================================

# Nettoyer tous les caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# ============================================
# LIEN DE TEST DIRECT
# ============================================

# Copier cette URL dans votre navigateur après `php artisan serve`
# http://localhost:8000/dashboard

echo "✅ Setup complet!"
echo "Connectez-vous avec:"
echo "  Email: doctor.regular@cmovistamd.local"
echo "  Password: password123"
echo ""
echo "Puis accédez à: http://localhost:8000/dashboard"
