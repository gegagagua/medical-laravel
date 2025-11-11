<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Check if user_id exists and patient_id doesn't
            if (Schema::hasColumn('payments', 'user_id') && !Schema::hasColumn('payments', 'patient_id')) {
                // Drop foreign key if exists
                try {
                    DB::statement('ALTER TABLE payments DROP FOREIGN KEY payments_user_id_foreign');
                } catch (\Exception $e) {
                    // Foreign key might not exist, continue
                }
                
                // Rename column using raw SQL (Laravel's renameColumn doesn't work well with MySQL)
                DB::statement('ALTER TABLE payments CHANGE user_id patient_id BIGINT UNSIGNED NOT NULL');
                
                // Add foreign key to patients table
                try {
                    DB::statement('ALTER TABLE payments ADD CONSTRAINT payments_patient_id_foreign FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE');
                } catch (\Exception $e) {
                    // Foreign key might already exist, continue
                }
            } elseif (!Schema::hasColumn('payments', 'patient_id')) {
                // If neither column exists, add patient_id
                $table->unsignedBigInteger('patient_id')->after('id');
                $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'patient_id') && !Schema::hasColumn('payments', 'user_id')) {
                // Drop foreign key
                try {
                    DB::statement('ALTER TABLE payments DROP FOREIGN KEY payments_patient_id_foreign');
                } catch (\Exception $e) {
                    // Foreign key might not exist
                }
                
                // Rename back to user_id
                DB::statement('ALTER TABLE payments CHANGE patient_id user_id BIGINT UNSIGNED NOT NULL');
            }
        });
    }
};
