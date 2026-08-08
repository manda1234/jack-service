<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Service extends Model
{
    protected $fillable = [
    'customer_id',
    'technician_id',
    'service_code',
    'service_type',
    'item_name',
    'brand',
    'complaint',
    'address',
    'preferred_visit_date',
    'preferred_visit_time',
    'diagnosis',
    'repair_action',
    'service_fee',
    'latitude',
    'longitude',
    'scheduled_visit_date',
'scheduled_visit_time',
    'sparepart_cost',
    'total_cost',
    'photo',
    'rejection_reason',
    'region',
    'notes',
    'status',
];
protected function casts(): array
{
    return [
        'preferred_visit_date' => 'date',
         'scheduled_visit_date' => 'date',
    ];
}

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function spareparts(): HasMany
    {
        return $this->hasMany(ServiceSparepart::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function getPhotoUrlAttribute()
{
    return $this->photo ? asset('storage/' . $this->photo) : null;
}
}