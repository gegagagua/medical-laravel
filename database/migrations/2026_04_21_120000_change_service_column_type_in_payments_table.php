<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE payments MODIFY service TEXT NOT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE payments MODIFY service VARCHAR(255) NOT NULL');
    }
};
