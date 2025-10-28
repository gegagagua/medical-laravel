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
            // Drop camelCase columns if they exist
            if (Schema::hasColumn('users', 'firstName')) {
                $table->dropColumn('firstName');
            }
            if (Schema::hasColumn('users', 'lastName')) {
                $table->dropColumn('lastName');
            }
            if (Schema::hasColumn('users', 'createdAt')) {
                $table->dropColumn('createdAt');
            }
            if (Schema::hasColumn('users', 'updatedAt')) {
                $table->dropColumn('updatedAt');
            }
            
            // Ensure first_name and last_name are not nullable if they don't exist
            if (!Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name')->nullable()->after('id');
            }
            if (!Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name')->nullable()->after('first_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this migration
    }
};

