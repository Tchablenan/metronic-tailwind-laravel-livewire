<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestDoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer le médecin régulier
        $doctor = User::updateOrCreate(
            ['email' => 'doctor.regular@cmovistamd.local'],
            [
                'first_name' => 'Dr. Regular',
                'last_name' => 'Doctor',
                'password' => bcrypt('password123'),
                'phone_number' => '+2270101234567',
                'role' => 'doctor',
                'is_chief' => false,
                'speciality' => 'Pédiatrie',
                'is_active' => true,
            ]
        );

        // Créer le patient
        $patient = User::updateOrCreate(
            ['email' => 'patient.test@cmovistamd.local'],
            [
                'first_name' => 'Jean',
                'last_name' => 'Dupont',
                'password' => bcrypt('password123'),
                'phone_number' => '+2270101234568',
                'role' => 'patient',
                'is_active' => true,
            ]
        );

        // Supprimer les anciens rendez-vous
        Appointment::where('doctor_id', $doctor->id)->delete();

        // Créer les rendez-vous pour aujourd'hui
        Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => today(),
            'appointment_time' => '09:00:00',
            'duration' => 30,
            'type' => 'consultation',
            'status' => 'scheduled',
            'reason' => 'Consultation initiale',
            'location' => 'cabinet',
        ]);

        Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => today(),
            'appointment_time' => '14:00:00',
            'duration' => 30,
            'type' => 'followup',
            'status' => 'scheduled',
            'reason' => 'Suivi pédiatrique',
            'location' => 'cabinet',
        ]);

        $this->command->info('✅ Données de test créées avec succès!');
        $this->command->info('Email: doctor.regular@cmovistamd.local');
        $this->command->info('Mot de passe: password123');
    }
}
