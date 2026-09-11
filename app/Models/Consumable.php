<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUppercaseAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Consumable extends Model
{
    use HasUppercaseAttributes;

    protected $fillable = [
        'name',
        'stock',
        'unit',
        'min_stock',
    ];

    protected $casts = [
        'stock' => 'integer',
        'min_stock' => 'integer',
    ];

    /**
     * @return HasMany<TicketConsumable, $this>
     */
    public function ticketConsumables(): HasMany
    {
        return $this->hasMany(TicketConsumable::class);
    }

    /**
     * @return HasMany<ConsumableDeliveryItem, $this>
     */
    public function deliveryItems(): HasMany
    {
        return $this->hasMany(ConsumableDeliveryItem::class);
    }
}