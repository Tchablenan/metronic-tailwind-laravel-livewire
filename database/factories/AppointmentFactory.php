<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\User;
use App\Enums\AppointmentStatus;
use App\Enums\AppointmentType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        $doctor = User::factory()->create(['role' => 'doctor']);
        $patient = User::factory()->create(['role' => 'patient']);

        return [
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
            'nurse_id' => null,
            'appointment_date' => Carbon::now()->addDays($this->faker->numberBetween(1, 30)),
            'appointment_time' => $this->faker->time('H:i'),
            'duration' => 30,
            'status' => $this->faker->randomElement(AppointmentStatus::cases()),
            'type' => $this->faker->randomElement(AppointmentType::cases()),
            'notes' => $this->faker->sentence(),
            'reason' => $this->faker->sentence(),
            'patient_notes' => null,
            'reminder_sent' => false,
            'location' => 'cabinet',
            'is_emergency' => false,
        ];
    }

    public function withDoctor(User $doctor): self
    {
        return $this->state(fn (array $attributes) => [
            'doctor_id' => $doctor->id,
        ]);
    }

    public function withPatient(User $patient): self
    {
        return $this->state(fn (array $attributes) => [
            'patient_id' => $patient->id,
        ]);
    }

    public function scheduled(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => AppointmentStatus::SCHEDULED,
        ]);
    }
}
