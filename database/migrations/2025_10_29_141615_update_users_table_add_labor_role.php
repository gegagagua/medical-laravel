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
        Schema::table('users', function (Blueprint $table) {
            // Modify the role enum to include LABOR
            \DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('PATIENT', 'DOCTOR', 'ADMIN', 'LABOR') DEFAULT 'PATIENT'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revert back to original enum values
            \DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('PATIENT', 'DOCTOR', 'ADMIN') DEFAULT 'PATIENT'");
        });
    }
};
