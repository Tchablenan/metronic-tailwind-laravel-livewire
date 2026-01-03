<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'phone_number' => '+225' . fake()->numerify('07########'),
            'role' => 'patient', // Par défaut
            'is_active' => true,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Create a doctor user
     */
    public function doctor(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'doctor',
            'speciality' => fake()->randomElement([
                'Médecine Générale',
                'Pédiatrie',
                'Cardiologie',
                'Chirurgie',
                'Gynécologie',
                'Dermatologie',
            ]),
            'license_number' => 'CI-MED-' . fake()->year() . '-' . fake()->unique()->numberBetween(100, 999),
        ])->afterCreating(function (User $user) {
            $user->assignRole('doctor');
        });
    }

    /**
     * Create a nurse user
     */
    public function nurse(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'nurse',
            'speciality' => fake()->randomElement([
                'Soins Infirmiers',
                'Soins Intensifs',
                'Pédiatrie',
                'Urgences',
                'Bloc Opératoire',
            ]),
            'license_number' => 'CI-INF-' . fake()->year() . '-' . fake()->unique()->numberBetween(100, 999),
        ])->afterCreating(function (User $user) {
            $user->assignRole('nurse');
        });
    }

    /**
     * Create a secretary user
     */
    public function secretary(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'secretary',
            'speciality' => null,
            'license_number' => null,
        ])->afterCreating(function (User $user) {
            $user->assignRole('secretary');
        });
    }

    /**
     * Create a patient user
     */
    public function patient(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'patient',
            'speciality' => null,
            'license_number' => null,
        ])->afterCreating(function (User $user) {
            $user->assignRole('patient');
        });
    }

    /**
     * Create a partner user
     */
    public function partner(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'partner',
            'first_name' => fake()->randomElement(['CHU', 'Clinique', 'Laboratoire', 'Centre']),
            'last_name' => fake()->randomElement(['Cocody', 'Treichville', 'Yopougon', 'Plateau', 'Marcory']),
            'speciality' => null,
            'license_number' => null,
        ])->afterCreating(function (User $user) {
            $user->assignRole('partner');
        });
    }

    /**
     * Create a home care member user
     */
    public function homeCare(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'home_care_member',
            'speciality' => 'Assistance à Domicile',
            'license_number' => null,
        ])->afterCreating(function (User $user) {
            $user->assignRole('home_care_member');
        });
    }

    /**
     * Create an inactive user
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
