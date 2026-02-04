<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            // ============================================
            // GROUPE 1 : TRIAGE INITIAL (après urgency)
            // ============================================
            $table->decimal('temperature', 4, 1)->nullable()->after('urgency')->comment('Température corporelle en °C');
            $table->integer('blood_pressure_systolic')->nullable()->after('temperature')->comment('Tension systolique en mmHg');
            $table->integer('blood_pressure_diastolic')->nullable()->after('blood_pressure_systolic')->comment('Tension diastolique en mmHg');
            $table->decimal('weight', 5, 2)->nullable()->after('blood_pressure_diastolic')->comment('Poids en kg');
            $table->decimal('height', 5, 2)->nullable()->after('weight')->comment('Taille en cm');
            $table->text('known_allergies')->nullable()->after('height')->comment('Allergies connues');
            $table->text('current_medications')->nullable()->after('known_allergies')->comment('Médicaments actuels');

            // ============================================
            // GROUPE 2 : INFORMATIONS ASSURANCE
            // ============================================
            $table->boolean('has_insurance')->default(false)->after('current_medications')->comment('Patient assuré');
            $table->string('insurance_company', 100)->nullable()->after('has_insurance')->comment('Compagnie d\'assurance');
            $table->string('insurance_policy_number', 100)->nullable()->after('insurance_company')->comment('Numéro de police');
            $table->integer('insurance_coverage_rate')->nullable()->after('insurance_policy_number')->comment('Taux de couverture (%)');
            $table->decimal('insurance_ceiling', 12, 2)->nullable()->after('insurance_coverage_rate')->comment('Plafond annuel en FCFA');
            $table->date('insurance_expiry_date')->nullable()->after('insurance_ceiling')->comment('Date d\'expiration assurance');

            // ============================================
            // GROUPE 3 : EXAMENS DÉJÀ EFFECTUÉS
            // ============================================
            $table->boolean('has_previous_exams')->default(false)->after('insurance_expiry_date')->comment('Patient a déjà fait des examens');
            $table->string('previous_exam_type', 50)->nullable()->after('has_previous_exams')->comment('Type d\'examen (laboratory, imaging, ecg, covid, checkup, other)');
            $table->string('previous_exam_name', 255)->nullable()->after('previous_exam_type')->comment('Nom de l\'examen');
            $table->string('previous_exam_facility', 255)->nullable()->after('previous_exam_name')->comment('Établissement où l\'examen a été fait');
            $table->date('previous_exam_date')->nullable()->after('previous_exam_facility')->comment('Date de l\'examen');
            $table->string('previous_exam_file_path', 500)->nullable()->after('previous_exam_date')->comment('Chemin du fichier résultat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropColumn([
                // Triage Initial
                'temperature',
                'blood_pressure_systolic',
                'blood_pressure_diastolic',
                'weight',
                'height',
                'known_allergies',
                'current_medications',

                // Assurance
                'has_insurance',
                'insurance_company',
                'insurance_policy_number',
                'insurance_coverage_rate',
                'insurance_ceiling',
                'insurance_expiry_date',

                // Examens
                'has_previous_exams',
                'previous_exam_type',
                'previous_exam_name',
                'previous_exam_facility',
                'previous_exam_date',
                'previous_exam_file_path',
            ]);
        });
    }
};
