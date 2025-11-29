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
            if (!Schema::hasColumn('payments', 'appointment_id')) {
                $table->unsignedBigInteger('appointment_id')->nullable()->after('patient_id');
            }
        });
        
        // Add foreign key using raw SQL to avoid constraint issues
        if (Schema::hasColumn('payments', 'appointment_id')) {
            try {
                DB::statement('ALTER TABLE payments ADD CONSTRAINT payments_appointment_id_foreign FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE SET NULL');
            } catch (\Exception $e) {
                // Foreign key might already exist, continue
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            try {
                DB::statement('ALTER TABLE payments DROP FOREIGN KEY payments_appointment_id_foreign');
            } catch (\Exception $e) {
                // Foreign key might not exist
            }
            
            if (Schema::hasColumn('payments', 'appointment_id')) {
                $table->dropColumn('appointment_id');
            }
        });
    }
};
