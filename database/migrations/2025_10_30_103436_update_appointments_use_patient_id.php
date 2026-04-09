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
        try {
            \DB::statement('ALTER TABLE appointments DROP FOREIGN KEY appointments_user_id_foreign');
        } catch (\Throwable $e) {
        }

        // Use if/elseif: after rename, patient_id does not exist yet in hasColumn checks, so two
        // separate `if` blocks would queue both rename and add — duplicate column error.
        if (Schema::hasColumn('appointments', 'user_id') && ! Schema::hasColumn('appointments', 'patient_id')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->renameColumn('user_id', 'patient_id');
            });
        } elseif (! Schema::hasColumn('appointments', 'patient_id')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->unsignedBigInteger('patient_id')->after('id');
            });
        }

        try {
            \DB::statement('ALTER TABLE appointments ADD CONSTRAINT appointments_patient_id_foreign FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE');
        } catch (\Throwable $e) {
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            try {
                \DB::statement('ALTER TABLE appointments DROP FOREIGN KEY appointments_patient_id_foreign');
            } catch (\Throwable $e) {
            }
            if (Schema::hasColumn('appointments', 'patient_id') && ! Schema::hasColumn('appointments', 'user_id')) {
                $table->renameColumn('patient_id', 'user_id');
            }
        });
    }
};
