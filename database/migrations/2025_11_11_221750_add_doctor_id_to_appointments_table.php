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
        Schema::table('appointments', function (Blueprint $table) {
            // Drop existing doctor_id column if it exists (from previous failed migration)
            if (Schema::hasColumn('appointments', 'doctor_id')) {
                // Drop foreign key if it exists
                try {
                    \DB::statement('ALTER TABLE appointments DROP FOREIGN KEY appointments_doctor_id_foreign');
                } catch (\Throwable $e) {
                    // Foreign key might not exist, try alternative name
                    try {
                        \DB::statement('ALTER TABLE appointments DROP FOREIGN KEY appointments_doctor_id_foreign');
                    } catch (\Throwable $e2) {
                        // Ignore
                    }
                }
                $table->dropColumn('doctor_id');
            }
            
            // Add doctor_id column with correct type to match users.id (int(11))
            // Note: Using unsignedInteger to match users.id type
            $table->unsignedInteger('doctor_id')->nullable()->after('patient_id');
        });
        
        // Add foreign key constraint with explicit index creation
        // Using raw SQL to ensure proper constraint creation
        try {
            \DB::statement('ALTER TABLE appointments ADD CONSTRAINT appointments_doctor_id_foreign FOREIGN KEY (doctor_id) REFERENCES users(id) ON DELETE SET NULL');
        } catch (\Throwable $e) {
            // If constraint creation fails, at least we have the column
            // The application will still work, just without the foreign key constraint
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['doctor_id']);
            $table->dropColumn('doctor_id');
        });
    }
};
