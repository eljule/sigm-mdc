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
            $assignment->asset->update(['status' => 'asignado']);
        });

        static::updated(function (AssetAssignment $assignment) {
            if ($assignment->isDirty('returned_at') && $assignment->returned_at !== null) {
                $asset = $assignment->asset;
                if ($asset && ! in_array(strtolower($asset->status ?? ''), ['baja', 'mantenimiento', 'en evaluación', 'en evaluacion'])) {
                    $asset->update(['status' => 'disponible']);
                }
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
