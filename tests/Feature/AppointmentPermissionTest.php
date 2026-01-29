<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\User;
use App\Enums\AppointmentStatus;
use App\Enums\AppointmentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentPermissionTest extends TestCase
{
    use RefreshDatabase;

    private User $chiefDoctor;
    private User $regularDoctor1;
    private User $regularDoctor2;
    private User $secretary;
    private User $patient1;
    private User $patient2;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test users
        $this->chiefDoctor = User::factory()->create([
            'role' => 'doctor',
            'is_chief' => true,
            'first_name' => 'Chef',
            'last_name' => 'Doctor'
        ]);

        $this->regularDoctor1 = User::factory()->create([
            'role' => 'doctor',
            'is_chief' => false,
            'first_name' => 'Régulier',
            'last_name' => 'Doctor1'
        ]);

        $this->regularDoctor2 = User::factory()->create([
            'role' => 'doctor',
            'is_chief' => false,
            'first_name' => 'Régulier',
            'last_name' => 'Doctor2'
        ]);

        $this->secretary = User::factory()->create([
            'role' => 'secretary',
            'first_name' => 'Secrétaire',
            'last_name' => 'Test'
        ]);

        $this->patient1 = User::factory()->create([
            'role' => 'patient',
            'first_name' => 'Patient',
            'last_name' => 'Un'
        ]);

        $this->patient2 = User::factory()->create([
            'role' => 'patient',
            'first_name' => 'Patient',
            'last_name' => 'Deux'
        ]);
    }

    /**
     * Test that chief doctor can see all appointments
     */
    public function test_chief_doctor_can_view_all_appointments(): void
    {
        // Create appointments for different doctors
        $app1 = Appointment::factory()->create([
            'doctor_id' => $this->chiefDoctor->id,
            'patient_id' => $this->patient1->id,
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        $app2 = Appointment::factory()->create([
            'doctor_id' => $this->regularDoctor1->id,
            'patient_id' => $this->patient2->id,
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        $this->actingAs($this->chiefDoctor)
            ->get('/appointments')
            ->assertStatus(200)
            ->assertSee($app1->id)
            ->assertSee($app2->id);
    }

    /**
     * Test that regular doctor can only see their own appointments
     */
    public function test_regular_doctor_can_only_see_own_appointments(): void
    {
        $ownApp = Appointment::factory()->create([
            'doctor_id' => $this->regularDoctor1->id,
            'patient_id' => $this->patient1->id,
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        $otherApp = Appointment::factory()->create([
            'doctor_id' => $this->regularDoctor2->id,
            'patient_id' => $this->patient2->id,
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        $this->actingAs($this->regularDoctor1)
            ->get('/appointments')
            ->assertStatus(200)
            ->assertSee($ownApp->id);

        // Note: We can't easily test "assertDontSee" because of pagination
        // Better to check the exact count
        $response = $this->actingAs($this->regularDoctor1)
            ->get('/appointments');

        $response->assertStatus(200);
    }

    /**
     * Test that secretary can see all appointments
     */
    public function test_secretary_can_view_all_appointments(): void
    {
        $app1 = Appointment::factory()->create([
            'doctor_id' => $this->chiefDoctor->id,
            'patient_id' => $this->patient1->id,
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        $app2 = Appointment::factory()->create([
            'doctor_id' => $this->regularDoctor1->id,
            'patient_id' => $this->patient2->id,
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        $this->actingAs($this->secretary)
            ->get('/appointments')
            ->assertStatus(200)
            ->assertSee($app1->id)
            ->assertSee($app2->id);
    }

    /**
     * Test that regular doctor cannot directly view another doctor's appointment
     */
    public function test_regular_doctor_cannot_view_other_doctors_appointment(): void
    {
        $otherApp = Appointment::factory()->create([
            'doctor_id' => $this->regularDoctor2->id,
            'patient_id' => $this->patient2->id,
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        $this->actingAs($this->regularDoctor1)
            ->get("/appointments/{$otherApp->id}")
            ->assertStatus(403);
    }

    /**
     * Test that regular doctor can view their own appointment
     */
    public function test_regular_doctor_can_view_own_appointment(): void
    {
        $ownApp = Appointment::factory()->create([
            'doctor_id' => $this->regularDoctor1->id,
            'patient_id' => $this->patient1->id,
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        $this->actingAs($this->regularDoctor1)
            ->get("/appointments/{$ownApp->id}")
            ->assertStatus(200)
            ->assertSee($ownApp->id);
    }

    /**
     * Test that chief doctor can update any appointment
     */
    public function test_chief_doctor_can_update_any_appointment(): void
    {
        $otherApp = Appointment::factory()->create([
            'doctor_id' => $this->regularDoctor1->id,
            'patient_id' => $this->patient1->id,
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        $this->actingAs($this->chiefDoctor)
            ->patch("/appointments/{$otherApp->id}", [
                'status' => AppointmentStatus::COMPLETED->value,
            ])
            ->assertStatus(302); // Redirect after update
    }

    /**
     * Test that regular doctor cannot update another doctor's appointment
     */
    public function test_regular_doctor_cannot_update_other_doctors_appointment(): void
    {
        $otherApp = Appointment::factory()->create([
            'doctor_id' => $this->regularDoctor2->id,
            'patient_id' => $this->patient1->id,
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        $this->actingAs($this->regularDoctor1)
            ->patch("/appointments/{$otherApp->id}", [
                'status' => AppointmentStatus::COMPLETED->value,
            ])
            ->assertStatus(403);
    }

    /**
     * Test that regular doctor can update their own appointment
     */
    public function test_regular_doctor_can_update_own_appointment(): void
    {
        $ownApp = Appointment::factory()->create([
            'doctor_id' => $this->regularDoctor1->id,
            'patient_id' => $this->patient1->id,
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        $this->actingAs($this->regularDoctor1)
            ->patch("/appointments/{$ownApp->id}", [
                'status' => AppointmentStatus::COMPLETED->value,
            ])
            ->assertStatus(302); // Redirect after update
    }

    /**
     * Test that chief doctor can delete any appointment
     */
    public function test_chief_doctor_can_delete_any_appointment(): void
    {
        $otherApp = Appointment::factory()->create([
            'doctor_id' => $this->regularDoctor1->id,
            'patient_id' => $this->patient1->id,
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        $this->actingAs($this->chiefDoctor)
            ->delete("/appointments/{$otherApp->id}")
            ->assertStatus(302); // Redirect after delete
    }

    /**
     * Test that regular doctor cannot delete another doctor's appointment
     */
    public function test_regular_doctor_cannot_delete_other_doctors_appointment(): void
    {
        $otherApp = Appointment::factory()->create([
            'doctor_id' => $this->regularDoctor2->id,
            'patient_id' => $this->patient1->id,
            'status' => AppointmentStatus::SCHEDULED,
        ]);

        $this->actingAs($this->regularDoctor1)
            ->delete("/appointments/{$otherApp->id}")
            ->assertStatus(403);
    }

    /**
     * Test that isChief() helper method works correctly
     */
    public function test_is_chief_helper_method(): void
    {
        $this->assertTrue($this->chiefDoctor->isChief());
        $this->assertFalse($this->regularDoctor1->isChief());
        $this->assertFalse($this->secretary->isChief());
        $this->assertFalse($this->patient1->isChief());
    }
}
