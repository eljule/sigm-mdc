<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUppercaseAttributes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetMaintenance extends Model
{
    use HasUppercaseAttributes;

    protected $fillable = [
        'asset_id',
        'ticket_id',
        'type',
        'scheduled_date',
        'performed_date',
        'description',
        'technician_notes',
        'cost',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'performed_date' => 'date',
        'cost' => 'decimal:2',
        'ticket_id' => 'integer',
        'asset_id' => 'integer',
    ];

    /**
     * @return BelongsTo<Asset, $this>
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    /**
     * @return BelongsTo<Ticket, $this>
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
