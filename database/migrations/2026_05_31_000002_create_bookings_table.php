<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->string('doctor_name');
            $table->string('patient_name');
            $table->string('patient_id_number', 50);
            $table->string('patient_phone', 50);
            $table->date('date');
            $table->string('time', 10);
            $table->timestamps();

            $table->index(['date', 'time']);
            $table->index('doctor_id');

            $table->foreign('doctor_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
