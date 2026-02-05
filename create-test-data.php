<?php
// Script de test pour créer données de test

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

require 'bootstrap/app.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Créer un médecin régulier de test
$doctor = User::firstOrCreate([
    'email' => 'doctor.regular@cmovistamd.local'
], [
    'first_name' => 'Adjoua',
    'last_name' => 'N\'Dri',
    'password' => Hash::make('password123'),
    'phone_number' => '+22507654321',
    'role' => 'doctor',
    'is_chief' => false,
    'speciality' => 'Pédiatrie',
    'license_number' => 'CI-MED-2024-003',
    'is_active' => true,
    'email_verified_at' => now(),
]);

echo "✅ Médecin créé: " . $doctor->full_name . " (ID: {$doctor->id})\n";

// Récupérer un patient
$patient = User::where('role', 'patient')->first();
if(!$patient) {
    echo "⚠️ Aucun patient trouvé!\n";
    exit(1);
}

// Créer 2 RDV pour ce médecin aujourd'hui
Appointment::create([
    'patient_id' => $patient->id,
    'doctor_id' => $doctor->id,
    'appointment_date' => today(),
    'appointment_time' => '10:00:00',
    'duration' => 30,
    'type' => 'consultation',
    'status' => 'confirmed',
    'reason' => 'Consultation pédiatrique',
    'location' => 'cabinet',
]);

Appointment::create([
    'patient_id' => $patient->id,
    'doctor_id' => $doctor->id,
    'appointment_date' => today(),
    'appointment_time' => '14:00:00',
    'duration' => 30,
    'type' => 'suivi',
    'status' => 'scheduled',
    'reason' => 'Suivi pédiatrique',
    'location' => 'cabinet',
]);

echo "✅ 2 RDV créés pour aujourd'hui\n";
echo "   - 10:00 (confirmed)\n";
echo "   - 14:00 (scheduled)\n";
echo "\n📧 Identifiants de test:\n";
echo "   Email: doctor.regular@cmovistamd.local\n";
echo "   Password: password123\n";
?>
