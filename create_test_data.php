<?php
require __DIR__ . '/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Appointment;

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

echo "Médecin régulier créé/mis à jour : {$doctor->email}\n";

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

echo "Patient créé/mis à jour : {$patient->email}\n";

// Supprimer les anciens rendez-vous
Appointment::where('doctor_id', $doctor->id)->delete();

// Créer les rendez-vous
$appt1 = Appointment::create([
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

echo "RDV 1 créé : {$appt1->appointment_date} {$appt1->appointment_time}\n";

$appt2 = Appointment::create([
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

echo "RDV 2 créé : {$appt2->appointment_date} {$appt2->appointment_time}\n";

echo "\n✅ Données de test créées avec succès!\n";
echo "Email : doctor.regular@cmovistamd.local\n";
echo "Mot de passe : password123\n";
