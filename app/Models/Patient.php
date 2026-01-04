<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'id_number',
        'date_of_birth',
        'age',
        'gender',
        'phone',
        'diagnosis',
        'status',
        'last_visit_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'last_visit_at' => 'datetime',
    ];

    /**
     * Calculate age from date of birth
     */
    public function getAgeAttribute()
    {
        if ($this->date_of_birth) {
            return $this->date_of_birth->diffInYears(now());
        }
        return $this->attributes['age'] ?? null;
    }
}
