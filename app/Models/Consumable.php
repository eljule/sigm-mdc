<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\EloquentRelations\HasMany;

Class Consumable extends Model
{
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