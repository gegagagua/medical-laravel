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
            // Drop foreign key constraints if they exist (try both naming conventions)
            try {
                \DB::statement('ALTER TABLE appointments DROP FOREIGN KEY appointments_user_id_foreign');
            } catch (\Throwable $e) {
                // Foreign key might not exist or have a different name
            }
            try {
                \DB::statement('ALTER TABLE appointments DROP FOREIGN KEY appointments_userId_foreign');
            } catch (\Throwable $e) {
                // Foreign key might not exist or have a different name
            }
            
            // Drop the userId column if it exists (camelCase)
            if (Schema::hasColumn('appointments', 'userId')) {
                $table->dropColumn('userId');
            }
            
            // Also drop user_id if it exists (snake_case)
            if (Schema::hasColumn('appointments', 'user_id')) {
                $table->dropColumn('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Re-add userId column if needed (for rollback)
            if (!Schema::hasColumn('appointments', 'userId')) {
                $table->unsignedBigInteger('userId')->nullable()->after('id');
            }
        });
    }
};
