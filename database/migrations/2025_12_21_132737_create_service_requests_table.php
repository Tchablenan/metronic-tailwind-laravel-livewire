<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();

            // Informations du demandeur (anonyme)
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone_number');

            // Type de service
            $table->enum('service_type', [
                'appointment',      // Rendez-vous médical
                'home_visit',       // Visite à domicile
                'emergency',        // Urgence
                'transport',        // Transport médicalisé
                'consultation',     // Consultation
                'other'            // Autre
            ])->default('appointment');

            // Détails de la demande
            $table->text('message')->nullable();
            $table->date('preferred_date')->nullable();
            $table->time('preferred_time')->nullable();
            $table->enum('urgency', ['low', 'medium', 'high'])->default('medium');

            // Statut de la demande
            $table->enum('status', [
                'pending',          // En attente
                'contacted',        // Contacté
                'converted',        // Converti en RDV
                'rejected',         // Rejeté
                'cancelled'        // Annulé
            ])->default('pending');

            // Lien avec patient (si converti)
            $table->foreignId('patient_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('appointment_id')->nullable()->constrained('appointments')->onDelete('set null');

            // Traçabilité
            $table->foreignId('handled_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('handled_at')->nullable();
            $table->text('internal_notes')->nullable();

            $table->timestamps();

            // Index
            $table->index(['email', 'phone_number']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
