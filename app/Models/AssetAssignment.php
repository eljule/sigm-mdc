<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUppercaseAttributes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetAssignment extends Model
{
    use HasUppercaseAttributes;

    protected $fillable = [
        'asset_id',
        'user_id',
        'office_id',
        'assigned_at',
        'returned_at',
        'notes',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (AssetAssignment $assignment) {
            // Verificar que el activo no tenga ya una asignación activa
            $activeExists = static::where('asset_id', $assignment->asset_id)
                ->whereNull('returned_at')
                ->exists();

            if ($activeExists) {
                $asset = \App\Models\Asset::find($assignment->asset_id);
                $code = $asset ? $asset->computer_code : "#{$assignment->asset_id}";

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'asset_id' => "El activo [{$code}] ya tiene una asignación activa. Primero debe registrar su devolución antes de asignarlo nuevamente.",
                ]);
            }
        });

        static::created(function (AssetAssignment $assignment) {
            $assignment->asset?->update(['status' => 'asignado']);

            // Si el activo principal asignado posee componentes hijos, registrar también la asignación para cada uno de ellos
            if ($assignment->asset && $assignment->asset->parent_id === null && $assignment->asset->components->isNotEmpty()) {
                foreach ($assignment->asset->components as $component) {
                    $activeExists = static::where('asset_id', $component->id)
                        ->whereNull('returned_at')
                        ->exists();

                    if (! $activeExists) {
                        static::create([
                            'asset_id'    => $component->id,
                            'user_id'     => $assignment->user_id,
                            'office_id'   => $assignment->office_id,
                            'assigned_at' => $assignment->assigned_at,
                            'notes'       => $assignment->notes ?? "Componente vinculado a equipo principal {$assignment->asset->computer_code}",
                        ]);
                    }
                }
            }
        });

        static::updated(function (AssetAssignment $assignment) {
            if ($assignment->isDirty('returned_at') && $assignment->returned_at !== null) {
                $asset = $assignment->asset;
                if ($asset && ! in_array(strtolower($asset->status ?? ''), ['baja', 'mantenimiento', 'en evaluación', 'en evaluacion'])) {
                    $asset->update(['status' => 'disponible']);
                }

                // Si se devuelve el equipo principal, devolver también las asignaciones de sus activos hijos
                if ($asset && $asset->parent_id === null && $asset->components->isNotEmpty()) {
                    $componentIds = $asset->components->pluck('id');
                    static::whereIn('asset_id', $componentIds)
                        ->where('user_id', $assignment->user_id)
                        ->whereNull('returned_at')
                        ->update(['returned_at' => $assignment->returned_at]);

                    foreach ($asset->components as $comp) {
                        if (! in_array(strtolower($comp->status ?? ''), ['baja', 'mantenimiento', 'en evaluación', 'en evaluacion'])) {
                            $comp->update(['status' => 'disponible']);
                        }
                    }
                }
            }
        });

        static::deleted(function (AssetAssignment $assignment) {
            $asset = $assignment->asset;
            if ($asset && $asset->parent_id === null && $asset->components->isNotEmpty()) {
                $componentIds = $asset->components->pluck('id');
                static::whereIn('asset_id', $componentIds)
                    ->where('user_id', $assignment->user_id)
                    ->delete();
            }

            if ($asset && ! in_array(strtolower($asset->status ?? ''), ['baja', 'mantenimiento', 'en evaluación', 'en evaluacion'])) {
                $asset->update(['status' => 'disponible']);
            }
        });
    }

    /**
     * @return BelongsTo<Asset, $this>
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Office, $this>
     */
    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }
}
