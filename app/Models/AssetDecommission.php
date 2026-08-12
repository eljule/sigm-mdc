<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetDecommission extends Model
{
    protected $fillable = [
        'asset_id',
        'ticket_id',
        'decommissioned_by',
        'authorized_by',
        'decommissioned_at',
        'reason',
        'resolution_type',
        'evaluation_summary',
        'notes',
    ];

    protected $casts = [
        'decommissioned_at' => 'datetime',
    ];

    /** Opciones de motivo de baja */
    public static function reasonOptions(): array
    {
        return [
            'Obsolescencia tecnológica'  => 'Obsolescencia tecnológica',
            'Daño irreparable'           => 'Daño irreparable',
            'Robo / Siniestro'           => 'Robo / Siniestro',
            'Fin de vida útil'           => 'Fin de vida útil',
            'Defecto de fábrica'         => 'Defecto de fábrica',
            'Otro'                       => 'Otro',
        ];
    }

    /** Opciones de tipo de resolución */
    public static function resolutionTypeOptions(): array
    {
        return [
            'Chatarreo'                     => 'Chatarreo',
            'Donación'                      => 'Donación',
            'Venta'                         => 'Venta',
            'Transferencia a otra unidad'   => 'Transferencia a otra unidad',
            'Devolución al proveedor'       => 'Devolución al proveedor',
        ];
    }

    /** Número de ficha correlativo formateado */
    public function getFichaNumberAttribute(): string
    {
        return 'FICH-BAJA-' . str_pad((string) $this->id, 5, '0', STR_PAD_LEFT);
    }

    /** @return BelongsTo<Asset, $this> */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    /** @return BelongsTo<Ticket, $this> */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /** @return BelongsTo<User, $this> */
    public function decommissionedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'decommissioned_by');
    }
}
