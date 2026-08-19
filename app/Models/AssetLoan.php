<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetLoan extends Model
{
    protected $fillable = [
        'asset_id',
        'office_id',
        'borrower_name',
        'start_time',
        'end_time',
        'returned_at',
        'status',
        'purpose',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'start_time'  => 'datetime',
        'end_time'    => 'datetime',
        'returned_at' => 'datetime',
    ];

    /**
     * Correlativo del acta de préstamo
     */
    protected function loanNumber(): Attribute
    {
        return Attribute::make(
            get: fn () => 'PREST-EQ-' . str_pad((string) $this->id, 5, '0', STR_PAD_LEFT)
        );
    }

    /**
     * Accesor dinámico del estado del préstamo:
     * Si no ha sido devuelto ni cancelado y la fecha fin estimada ya pasó, retorna 'overdue' (Vencido).
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($value === 'returned' || ! is_null($this->returned_at)) {
                    return 'returned';
                }

                if ($value === 'cancelled') {
                    return 'cancelled';
                }

                if ($this->end_time && now()->greaterThan($this->end_time)) {
                    return 'overdue';
                }

                return $value ?? 'active';
            }
        );
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
