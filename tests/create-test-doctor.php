<?php
// Script to create test data for Phase 1 Dashboard testing

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Support\Facades\Hash;

// Create regular doctor
$doctor = User::updateOrCreate(
    ['email' => 'doctor.regular@cmovistamd.local'],
    [
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
    ]
);

echo "✅ Doctor created: " . $doctor->full_name . " (ID: {$doctor->id})\n";

// Get a patient
$patient = User::where('role', 'patient')->first();
if (!$patient) {
    echo "❌ No patient found! Creating one...\n";
    $patient = User::create([
        'first_name' => 'Jean',
        'last_name' => 'Dupont',
        'email' => 'patient.test@cmovistamd.local',
        'password' => Hash::make('password123'),
        'role' => 'patient',
        'is_active' => true,
        'email_verified_at' => now(),
    ]);
    echo "✅ Patient created: " . $patient->full_name . "\n";
}

// Delete existing appointments for today from this doctor
Appointment::where('doctor_id', $doctor->id)
    ->whereDate('appointment_date', today())
    ->delete();

// Create 2 appointments for today
$appt1 = Appointment::create([
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

echo "✅ 2 appointments created for today:\n";
echo "   - 10:00 (confirmed)\n";
echo "   - 14:00 (scheduled)\n";
echo "\n📧 Test credentials:\n";
echo "   Email: doctor.regular@cmovistamd.local\n";
echo "   Password: password123\n";
echo "   Role: Doctor (Regular)\n";
echo "\n🔗 Test URL: http://localhost:8000/dashboard\n";
?>
