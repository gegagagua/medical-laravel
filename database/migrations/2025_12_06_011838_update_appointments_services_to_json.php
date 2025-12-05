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
        // First, change column types to TEXT temporarily to allow data conversion
        DB::statement('ALTER TABLE appointments MODIFY service TEXT NULL');
        DB::statement('ALTER TABLE appointments MODIFY service_id TEXT NULL');
        
        // Convert existing string values to JSON arrays
        $appointments = DB::table('appointments')->whereNotNull('service')->get();
        
        foreach ($appointments as $appointment) {
            // Convert string service to JSON array
            $serviceArray = $appointment->service ? [$appointment->service] : [];
            $serviceIdArray = $appointment->service_id ? [$appointment->service_id] : [];
            
            DB::table('appointments')
                ->where('id', $appointment->id)
                ->update([
                    'service' => json_encode($serviceArray),
                    'service_id' => json_encode($serviceIdArray)
                ]);
        }
        
        // Now change column types to JSON
        DB::statement('ALTER TABLE appointments MODIFY service JSON NULL');
        DB::statement('ALTER TABLE appointments MODIFY service_id JSON NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First, convert JSON arrays back to strings (take first element)
        $appointments = DB::table('appointments')->whereNotNull('service')->get();
        
        foreach ($appointments as $appointment) {
            $serviceArray = json_decode($appointment->service, true);
            $serviceIdArray = json_decode($appointment->service_id, true);
            
            $serviceString = is_array($serviceArray) && count($serviceArray) > 0 ? $serviceArray[0] : null;
            $serviceIdValue = is_array($serviceIdArray) && count($serviceIdArray) > 0 ? $serviceIdArray[0] : null;
            
            DB::table('appointments')
                ->where('id', $appointment->id)
                ->update([
                    'service' => $serviceString,
                    'service_id' => $serviceIdValue
                ]);
        }
        
        // Now change column types back
        Schema::table('appointments', function (Blueprint $table) {
            // Convert back to string
            DB::statement('ALTER TABLE appointments MODIFY service VARCHAR(255) NULL');
            DB::statement('ALTER TABLE appointments MODIFY service_id BIGINT UNSIGNED NULL');
        });
    }
};
