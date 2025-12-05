<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Appointment extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'doctor_name',
        'department',
        'service',
        'service_id',
        'date',
        'time',
        'status',
        'status_changed_at',
        'notes',
    ];

    protected $casts = [
        'date' => 'datetime',
        'status_changed_at' => 'datetime',
        'service' => 'array',
        'service_id' => 'array',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
