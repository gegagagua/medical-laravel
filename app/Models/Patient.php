<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'id_number',
        'age',
        'gender',
        'phone',
        'diagnosis',
        'status',
        'last_visit_at',
    ];

    protected $casts = [
        'last_visit_at' => 'datetime',
    ];
}
