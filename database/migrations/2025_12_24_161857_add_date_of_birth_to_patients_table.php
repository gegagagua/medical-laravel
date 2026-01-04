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
        Schema::table('patients', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable()->after('id_number');
            // Make age nullable since we'll calculate it from date_of_birth
            $table->unsignedSmallInteger('age')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn('date_of_birth');
            // Revert age to not nullable if needed
            $table->unsignedSmallInteger('age')->nullable(false)->change();
        });
    }
};
