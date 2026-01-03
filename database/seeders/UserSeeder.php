<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "🔄 Création des utilisateurs de test CMO VISTAMD...\n\n";

        /**
         * ============================================
         * UTILISATEURS PRINCIPAUX (Comptes réels)
         * ============================================
         */

        // 1. Médecin Chef (Dr. Jean Koné) - Super Admin
        $doctor = User::create([
            'first_name' => 'Jean',
            'last_name' => 'Koné',
            'email' => 'doctor@cmovistamd.local',
            'password' => Hash::make('password'),
            'phone_number' => '+22507123456',
            'role' => 'doctor',
            'speciality' => 'Médecine Générale',
            'license_number' => 'CI-MED-2024-001',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $doctor->assignRole('doctor');
        echo "✅ Dr. Jean Koné (Médecin Chef) créé\n";

        // 2. Infirmière principale (Adjoua N'Guessan)
        $nurse = User::create([
            'first_name' => 'Adjoua',
            'last_name' => 'N\'Guessan',
            'email' => 'nurse@cmovistamd.local',
            'password' => Hash::make('password'),
            'phone_number' => '+22507234567',
            'role' => 'nurse',
            'speciality' => 'Soins Infirmiers',
            'license_number' => 'CI-INF-2024-001',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $nurse->assignRole('nurse');
        echo "✅ Adjoua N'Guessan (Infirmière) créée\n";

        // 3. Secrétaire médicale (Marie Kouadio)
        $secretary = User::create([
            'first_name' => 'Marie',
            'last_name' => 'Kouadio',
            'email' => 'secretary@cmovistamd.local',
            'password' => Hash::make('password'),
            'phone_number' => '+22507345678',
            'role' => 'secretary',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $secretary->assignRole('secretary');
        echo "✅ Marie Kouadio (Secrétaire) créée\n";

        // 4. Patient test (Kouassi Yao)
        $patient = User::create([
            'first_name' => 'Kouassi',
            'last_name' => 'Yao',
            'email' => 'patient@cmovistamd.local',
            'password' => Hash::make('password'),
            'phone_number' => '+22507456789',
            'role' => 'patient',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $patient->assignRole('patient');
        echo "✅ Kouassi Yao (Patient test) créé\n";

        // 5. Partenaire (CHU de Cocody)
        $partner = User::create([
            'first_name' => 'CHU',
            'last_name' => 'Cocody',
            'email' => 'partner@cmovistamd.local',
            'password' => Hash::make('password'),
            'phone_number' => '+22507567890',
            'role' => 'partner',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $partner->assignRole('partner');
        echo "✅ CHU Cocody (Partenaire) créé\n";

        // 6. Équipe terrain (Koffi Brou)
        $homeCare = User::create([
            'first_name' => 'Koffi',
            'last_name' => 'Brou',
            'email' => 'homecare@cmovistamd.local',
            'password' => Hash::make('password'),
            'phone_number' => '+22507678901',
            'role' => 'home_care_member',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $homeCare->assignRole('home_care_member');
        echo "✅ Koffi Brou (Équipe terrain) créé\n";

        echo "\n";
        echo "════════════════════════════════════════════════════════\n";

        /**
         * ============================================
         * UTILISATEURS ADDITIONNELS (Données de test)
         * ============================================
         */
        echo "\n🔄 Création d'utilisateurs additionnels...\n\n";

        // Créer 3 médecins additionnels
        User::factory()->count(3)->doctor()->create();
        echo "✅ 3 médecins additionnels créés\n";

        // Créer 5 infirmières additionnelles
        User::factory()->count(5)->nurse()->create();
        echo "✅ 5 infirmières additionnelles créées\n";

        // Créer 2 secrétaires additionnelles
        User::factory()->count(2)->secretary()->create();
        echo "✅ 2 secrétaires additionnelles créées\n";

        // Créer 15 patients additionnels
        User::factory()->count(15)->patient()->create();
        echo "✅ 15 patients additionnels créés\n";

        // Créer 2 membres d'équipe terrain additionnels
        User::factory()->count(2)->homeCare()->create();
        echo "✅ 2 membres équipe terrain additionnels créés\n";

        echo "\n";
        echo "🎉 Total: " . User::count() . " utilisateurs créés!\n";
        echo "════════════════════════════════════════════════════════\n";
        echo "\n📋 IDENTIFIANTS DE TEST:\n";
        echo "   Email: doctor@cmovistamd.local    | Mot de passe: password\n";
        echo "   Email: nurse@cmovistamd.local     | Mot de passe: password\n";
        echo "   Email: secretary@cmovistamd.local | Mot de passe: password\n";
        echo "   Email: patient@cmovistamd.local   | Mot de passe: password\n";
        echo "   Email: partner@cmovistamd.local   | Mot de passe: password\n";
        echo "   Email: homecare@cmovistamd.local  | Mot de passe: password\n";
        echo "════════════════════════════════════════════════════════\n";
    }
}
