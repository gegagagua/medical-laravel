<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'doctor_id',
        'doctor_name',
        'patient_name',
        'patient_id_number',
        'patient_phone',
        'date',
        'time',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
