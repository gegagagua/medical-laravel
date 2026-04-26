<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

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
            if (! empty($payment->invoice_number)) {
                return;
            }
            $year = date('Y');
            $lock = 'payment_invoice_'.$year;
            $got = DB::selectOne('SELECT GET_LOCK(?, 10) AS g', [$lock]);
            if ((int) ($got->g ?? 0) !== 1) {
                throw new \RuntimeException('Could not acquire invoice number lock.');
            }
            try {
                $prefix = 'INV-'.$year.'-';
                $substrStart = (int) strlen($prefix) + 1;
                $maxSeq = (int) (static::query()
                    ->where('invoice_number', 'like', $prefix.'%')
                    ->selectRaw('MAX(CAST(SUBSTRING(invoice_number, '.$substrStart.') AS UNSIGNED)) AS m')
                    ->value('m'));
                $next = $maxSeq + 1;
                $payment->invoice_number = $prefix.str_pad((string) $next, 3, '0', STR_PAD_LEFT);
            } finally {
                DB::selectOne('SELECT RELEASE_LOCK(?) AS r', [$lock]);
            }
        });
    }
}
