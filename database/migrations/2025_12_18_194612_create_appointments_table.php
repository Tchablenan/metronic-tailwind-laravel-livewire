<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('doctor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('nurse_id')->nullable()->constrained('users')->onDelete('set null');
            //$table->foreignId('service_request_id')->nullable()->constrained('service_requests')->onDelete('set null');

            // Informations du rendez-vous
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->integer('duration')->default(30); // en minutes

            $table->enum('type', [
                'consultation',     // Consultation générale
                'followup',        // Suivi
                'exam',            // Examen médical
                'emergency',       // Urgence
                'vaccination',     // Vaccination
                'home_visit',      // Visite à domicile
                'telehealth'       // Téléconsultation
            ])->default('consultation');

            $table->enum('status', [
                'scheduled',       // Prévu
                'confirmed',       // Confirmé par le patient
                'in_progress',     // En cours
                'completed',       // Terminé
                'cancelled',       // Annulé
                'no_show'          // Patient absent
            ])->default('scheduled');

            // Détails
            $table->text('reason')->nullable(); // Motif de consultation
            $table->text('notes')->nullable(); // Notes du médecin/infirmier
            $table->text('patient_notes')->nullable(); // Notes du patient

            // Rappels
            $table->boolean('reminder_sent')->default(false);
            $table->timestamp('reminder_sent_at')->nullable();

            // Métadonnées
            $table->string('location')->default('cabinet'); // cabinet, domicile, urgence, hopital
            $table->decimal('price', 10, 2)->nullable();
            $table->boolean('is_emergency')->default(false);
            $table->string('cancelled_by')->nullable(); // patient, doctor, secretary, system
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index pour améliorer les performances
            $table->index(['appointment_date', 'appointment_time']);
            $table->index(['patient_id', 'appointment_date']);
            $table->index(['doctor_id', 'appointment_date']);
            $table->index('status');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
