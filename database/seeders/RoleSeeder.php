<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        /**
         * ============================================
         * CRÉER LES 7 RÔLES CMO VISTAMD
         * ============================================
         */

        // 1. Doctor (Médecin Chef) - Admin du système + Responsable médical
        Role::create([
            'name' => 'doctor',
            'description' => 'Médecin Chef - Administrateur système + Responsable médical'
                . '\n- Accès total au système'
                . '\n- Gestion des doctors/nurses'
                . '\n- Validation des demandes de service'
                . '\n- Consultations, diagnostics, traitements'
                . '\n- Rapports administratifs'
        ]);

        // 2. Nurse - Infirmier
        Role::create([
            'name' => 'nurse',
            'description' => 'Infirmier - Soins et examens'
                . '\n- Réalise les actes médicaux'
                . '\n- Soins, examens, interventions'
                . '\n- Accès au dossier médical des patients'
                . '\n- Enregistre les observations'
        ]);

        // 3. Secretary - Secrétaire
        Role::create([
            'name' => 'secretary',
            'description' => 'Secrétaire - Gestion administrative'
                . '\n- Traitement des demandes de service'
                . '\n- Planification des rendez-vous'
                . '\n- Gestion facturation et paiements'
                . '\n- Gestion ressources et équipements'
                . '\n- Support administratif'
        ]);

        // 4. Patient - Patient
        Role::create([
            'name' => 'patient',
            'description' => 'Patient - Accès personnel'
                . '\n- Consultation du dossier médical personnel'
                . '\n- Suivi des demandes de service'
                . '\n- Suivi des rendez-vous'
                . '\n- Téléchargement des documents médicaux'
                . '\n- Communication avec secrétariat/médecin'
        ]);

        // 5. Partner - Partenaire externe (Hôpital, Labo, Clinique)
        Role::create([
            'name' => 'partner',
            'description' => 'Partenaire externe - Accès limité'
                . '\n- Accès aux patients assignés'
                . '\n- Consultation des rendez-vous assignés'
                . '\n- Transmission de résultats examens'
                . '\n- Accès en lecture seule aux données critiques'
        ]);

        // 6. Home Care Member - Équipe terrain
        Role::create([
            'name' => 'home_care_member',
            'description' => 'Équipe terrain - Interventions mobiles'
                . '\n- Interventions à domicile'
                . '\n- Partage localisation GPS en temps réel'
                . '\n- Accès aux patients assignés'
                . '\n- Enregistrement observations terrain'
                . '\n- Fonction SOS urgence'
        ]);

        // NOTE: "admin" n'existe plus, c'est "doctor" qui est le super-admin
        echo "✅ 6 rôles CMO VISTAMD créés avec succès!\n";
        echo "📌 Note: Le rôle 'doctor' (Médecin Chef) est l'administrateur principal du système.\n";
    }
}
