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
        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'discounted_name')) {
                $table->string('discounted_name')->nullable()->after('name');
            }
            if (!Schema::hasColumn('services', 'department')) {
                $table->string('department')->nullable()->after('price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            if (Schema::hasColumn('services', 'discounted_name')) {
                $table->dropColumn('discounted_name');
            }
            if (Schema::hasColumn('services', 'department')) {
                $table->dropColumn('department');
            }
        });
    }
};
