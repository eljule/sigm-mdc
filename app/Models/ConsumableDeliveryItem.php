<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsumableDeliveryItem extends Model
{
    protected $fillable = [
        'consumable_delivery_id',
        'consumable_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    /**
     * @return BelongsTo<ConsumableDelivery, $this>
     */
    public function delivery(): BelongsTo
    {
        return $this->belongsTo(ConsumableDelivery::class, 'consumable_delivery_id');
    }

    /**
     * @return BelongsTo<Consumable, $this>
     */
    public function consumable(): BelongsTo
    {
        return $this->belongsTo(Consumable::class);
    }
}
