<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketConsumable extends Model
{
    protected $fillable = [
        'ticket_id',
        'consumable_id',
        'quantity',
    ];

    protected $casts = [
        'ticket_id' => 'integer',
        'consumable_id' => 'integer',
        'quantity' => 'integer',
    ];

    /**
     * @return BelongsTo<Ticket, $this>
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * @return BelongsTo<Consumable, $this>
     */
    public function consumable(): BelongsTo
    {
        return $this->belongsTo(Consumable::class);
    }

    protected static function booted(): void
    {
        static::created(function (TicketConsumable $ticketConsumable) {
            $ticket = $ticketConsumable->ticket;
            if ($ticket && in_array($ticket->status, ['Resuelto', 'Cerrado'])) {
                $ticketConsumable->consumable->decrement('stock', $ticketConsumable->quantity);
            }
        });

        static::deleted(function (TicketConsumable $ticketConsumable) {
            $ticket = $ticketConsumable->ticket;
            if ($ticket && in_array($ticket->status, ['Resuelto', 'Cerrado'])) {
                $ticketConsumable->consumable->increment('stock', $ticketConsumable->quantity);
            }
        });

        static::updated(function (TicketConsumable $ticketConsumable) {
            $ticket = $ticketConsumable->ticket;
            if ($ticket && in_array($ticket->status, ['Resuelto', 'Cerrado'])) {
                $diff = $ticketConsumable->quantity - $ticketConsumable->getOriginal('quantity');
                $ticketConsumable->consumable->decrement('stock', $diff);
            }
        });
    }
}
