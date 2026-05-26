<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $rows = DB::table('patients')
            ->select('id', 'gegas_code')
            ->whereNotNull('gegas_code')
            ->whereRaw("TRIM(gegas_code) <> ''")
            ->orderBy('id')
            ->get();

        $usedCodes = [];
        $maxNumericCode = (int) DB::table('patients')
            ->whereNotNull('gegas_code')
            ->whereRaw("TRIM(gegas_code) REGEXP '^[0-9]+$'")
            ->selectRaw('MAX(CAST(TRIM(gegas_code) AS UNSIGNED)) as max_code')
            ->value('max_code');
        $nextNumericCode = $maxNumericCode + 1;

        foreach ($rows as $row) {
            $code = trim((string) $row->gegas_code);
            if (! isset($usedCodes[$code])) {
                $usedCodes[$code] = true;
                continue;
            }

            while (isset($usedCodes[(string) $nextNumericCode])) {
                $nextNumericCode++;
            }
            $newCode = (string) $nextNumericCode;
            $nextNumericCode++;

            DB::table('patients')
                ->where('id', $row->id)
                ->update(['gegas_code' => $newCode]);

            $usedCodes[$newCode] = true;
        }

        Schema::table('patients', function (Blueprint $table) {
            $table->unique('gegas_code');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropUnique('patients_gegas_code_unique');
        });
    }
};
