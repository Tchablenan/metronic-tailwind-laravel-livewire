<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_chief')->default(false)->after('role');
        });

        // Marquer automatiquement le premier docteur créé comme médecin chef
        $firstDoctor = \App\Models\User::where('role', 'doctor')
            ->orderBy('created_at', 'asc')
            ->first();

        if ($firstDoctor) {
            $firstDoctor->update(['is_chief' => true]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_chief');
        });
    }
};
