<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
   protected $fillable = [
    'service_id',
    'amount',
    'payment_method',
    'amount_paid',
    'change_amount',
    'paid_by',
    'payment_status',
    'payment_date',
];

    protected function casts(): array
    {
        return [
            'payment_date' => 'datetime',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}