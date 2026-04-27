<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'id_number',
        'gegas_code',
        'date_of_birth',
        'age',
        'gender',
        'phone',
        'email',
        'address',
        'diagnosis',
        'status',
        'last_visit_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'last_visit_at' => 'datetime',
    ];

    /**
     * სრული წლები დაბადების თარიღიდან (მაგ. 31.9 → 31).
     */
    public function getAgeAttribute(): ?int
    {
        if ($this->date_of_birth) {
            return (int) $this->date_of_birth->age;
        }

        $stored = $this->attributes['age'] ?? null;

        return $stored !== null ? (int) $stored : null;
    }

    public static function nextNumericGegasCode(): string
    {
        $lastNumericCode = static::query()
            ->whereNotNull('gegas_code')
            ->whereRaw("TRIM(gegas_code) REGEXP '^[0-9]+$'")
            ->selectRaw('MAX(CAST(TRIM(gegas_code) AS UNSIGNED)) as max_code')
            ->value('max_code');

        return (string) (((int) $lastNumericCode) + 1);
    }
}
