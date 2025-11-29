<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'discounted_name',
        'price',
        'department',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];
}
