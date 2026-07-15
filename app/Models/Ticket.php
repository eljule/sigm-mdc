<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_code',
        'category_id',
        'requester_id',
        'office_id',
        'assigned_to',
        'title',
        'description',
        'priority',
        'status',
        'solution_applied',
        'sla_expires_at',
        'resolved_at',
        'closed_at',
    ];

    protected $casts = [
        'sla_expires_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Ticket $ticket) {
            // Generar código de ticket correlativo anual
            if (empty($ticket->ticket_code)) {
                $year = date('Y');
                $latest = self::where('ticket_code', 'like', "INC-{$year}-%")
                    ->orderBy('id', 'desc')
                    ->first();

                if ($latest) {
                    $parts = explode('-', $latest->ticket_code);
                    $seq = (int) end($parts);
                    $ticket->ticket_code = "INC-{$year}-".str_pad((string) ($seq + 1), 4, '0', STR_PAD_LEFT);
                } else {
                    $ticket->ticket_code = "INC-{$year}-0001";
                }
            }

            // Calcular expiración del SLA
            if ($ticket->category_id && empty($ticket->sla_expires_at)) {
                $category = TicketCategory::find($ticket->category_id);
                if ($category) {
                    $ticket->sla_expires_at = now()->addHours($category->sla_hours);
                }
            }
        });

        static::updating(function (Ticket $ticket) {
            if ($ticket->isDirty('status') && $ticket->status === 'Resuelto') {
                $ticket->resolved_at = now();
            }
            if ($ticket->isDirty('status') && $ticket->status === 'Cerrado') {
                $ticket->closed_at = now();
            }
        });
    }

    /**
     * @return BelongsTo<TicketCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(TicketCategory::class, 'category_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    /**
     * @return BelongsTo<Office, $this>
     */
    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class, 'office_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
