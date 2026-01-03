<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            // Statut de paiement
            $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])
                  ->default('unpaid')
                  ->after('status');

            // Montant payé
            $table->decimal('payment_amount', 10, 2)
                  ->nullable()
                  ->after('payment_status');

            // Méthode de paiement
            $table->enum('payment_method', ['cash', 'mobile_money', 'card', 'insurance'])
                  ->nullable()
                  ->after('payment_amount');

            // Date de paiement
            $table->timestamp('paid_at')
                  ->nullable()
                  ->after('payment_method');

            // Envoyé au médecin chef?
            $table->boolean('sent_to_doctor')
                  ->default(false)
                  ->after('paid_at');

            // Date d'envoi au médecin
            $table->timestamp('sent_to_doctor_at')
                  ->nullable()
                  ->after('sent_to_doctor');

            // Qui a envoyé au médecin (secrétaire)
            $table->foreignId('sent_by')
                  ->nullable()
                  ->after('sent_to_doctor_at')
                  ->constrained('users')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropForeign(['sent_by']);
            $table->dropColumn([
                'payment_status',
                'payment_amount',
                'payment_method',
                'paid_at',
                'sent_to_doctor',
                'sent_to_doctor_at',
                'sent_by',
            ]);
        });
    }
};
