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
            $table->boolean('has_discount')->default(false)->after('amount');
            $table->decimal('discount_percentage', 5, 2)->nullable()->after('has_discount');
            $table->json('services_discounts')->nullable()->after('discount_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['has_discount', 'discount_percentage', 'services_discounts']);
        });
    }
};
