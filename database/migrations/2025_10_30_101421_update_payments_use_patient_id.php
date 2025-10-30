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
        Schema::table('payments', function (Blueprint $table) {
            // Drop old FK if exists and rename column to patient_id, then add FK to patients
            try { \DB::statement('ALTER TABLE payments DROP FOREIGN KEY payments_user_id_foreign'); } catch (\Throwable $e) {}
            if (Schema::hasColumn('payments', 'user_id') && !Schema::hasColumn('payments', 'patient_id')) {
                $table->renameColumn('user_id', 'patient_id');
            }
            // Ensure type and add FK
            // Ensure column exists but don't re-add if already there
            if (!Schema::hasColumn('payments', 'patient_id') && Schema::hasColumn('payments', 'user_id')) {
                // renamed above
            } elseif (!Schema::hasColumn('payments', 'patient_id')) {
                $table->unsignedBigInteger('patient_id')->after('id');
            }
            try { \DB::statement('ALTER TABLE payments ADD CONSTRAINT payments_patient_id_foreign FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE'); } catch (\Throwable $e) {}
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            try { \DB::statement('ALTER TABLE payments DROP FOREIGN KEY payments_patient_id_foreign'); } catch (\Throwable $e) {}
            if (Schema::hasColumn('payments', 'patient_id') && !Schema::hasColumn('payments', 'user_id')) {
                $table->renameColumn('patient_id', 'user_id');
            }
        });
    }
};
