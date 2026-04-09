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
}
