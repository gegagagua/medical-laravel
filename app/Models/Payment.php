<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Payment extends Model
{
    protected $fillable = [
        'patient_id',
        'user_id',
        'appointment_id',
        'service',
        'doctor',
        'amount',
        'has_discount',
        'discount_percentage',
        'services_discounts',
        'payment_date',
        'payment_method',
        'status',
        'invoice_number',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
        'has_discount' => 'boolean',
        'discount_percentage' => 'decimal:2',
        'services_discounts' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->invoice_number)) {
                $year = date('Y');
                $count = self::whereYear('created_at', $year)->count() + 1;
                $payment->invoice_number = 'INV-' . $year . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
            }
        });
    }
}
