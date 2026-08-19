<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConsumableDelivery extends Model
{
    protected $fillable = [
        'delivered_by',
        'received_by',
        'office_id',
        'delivered_at',
        'notes',
    ];

    protected $casts = [
        'delivered_at' => 'datetime',
    ];

    /**
     * Accessor para el número de acta correlativo (ej: ACTA-ENT-00001)
     */
    protected function deliveryNumber(): Attribute
    {
        return Attribute::make(
            get: fn () => 'ACTA-ENT-' . str_pad((string) $this->id, 5, '0', STR_PAD_LEFT)
        );
    }

    /**
     * @return BelongsTo<Office, $this>
     */
    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    /**
     * @return HasMany<ConsumableDeliveryItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(ConsumableDeliveryItem::class);
    }
}
