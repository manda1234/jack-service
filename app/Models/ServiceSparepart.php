<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceSparepart extends Model
{
    protected $fillable = [
        'service_id',
        'sparepart_id',
        'quantity',
        'subtotal',
        'reason',
        'approval_status',
        'replacement_type',
        'component_name',
        'included_in_service',
    ];

    protected function casts(): array
    {
        return [
            'included_in_service' => 'boolean',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function sparepart(): BelongsTo
    {
        return $this->belongsTo(Sparepart::class);
    }

    public function isComponent(): bool
    {
        return $this->replacement_type === 'component';
    }

    // Dipakai di Blade agar tidak perlu if/else berulang
    public function getItemNameAttribute(): ?string
    {
        return $this->isComponent() ? $this->component_name : $this->sparepart?->name;
    }
}