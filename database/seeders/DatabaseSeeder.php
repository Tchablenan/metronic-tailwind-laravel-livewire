<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        echo "\n";
        echo "════════════════════════════════════════════════════════\n";
        echo "   🏥 CMO VISTAMD - DATABASE SEEDING\n";
        echo "════════════════════════════════════════════════════════\n";
        echo "\n";

        // 1. Créer les rôles en premier (OBLIGATOIRE)
        echo "📌 ÉTAPE 1/2: Création des rôles\n";
        echo "────────────────────────────────────────────────────────\n";
        $this->call(RoleSeeder::class);
        echo "\n";

        // 2. Créer les utilisateurs (après les rôles)
        echo "📌 ÉTAPE 2/2: Création des utilisateurs\n";
        echo "────────────────────────────────────────────────────────\n";
        $this->call(UserSeeder::class);

        echo "\n";
        echo "════════════════════════════════════════════════════════\n";
        echo "   ✅ BASE DE DONNÉES INITIALISÉE AVEC SUCCÈS!\n";
        echo "════════════════════════════════════════════════════════\n";
        echo "\n";
        echo "🚀 Prochaines étapes:\n";
        echo "   1. Démarrer le serveur: php artisan serve\n";
        echo "   2. Lancer Vite: npm run dev\n";
        echo "   3. Accéder à: http://localhost:8000/login\n";
        echo "\n";
    }
}
