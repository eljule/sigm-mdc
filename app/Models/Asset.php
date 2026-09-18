<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUppercaseAttributes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asset extends Model
{
    use HasUppercaseAttributes;

    protected $fillable = [
        'parent_id',
        'asset_category_id',
        'asset_model_id',
        'asset_code',
        'computer_code',
        'serial_number',
        'color',
        'estado',
        'status',
        'warranty_expiration',
        'purchase_date',
        'notes',
    ];

    /**
     * @return BelongsTo<AssetModel, $this>
     */
    public function model(): BelongsTo
    {
        return $this->belongsTo(AssetModel::class, 'asset_model_id');
    }

    /**
     * @return BelongsTo<AssetCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(AssetCategory::class, 'asset_category_id');
    }

    /**
     * @return HasMany<AssetCharacteristicValue, $this>
     */
    public function characteristicValues(): HasMany
    {
        return $this->hasMany(AssetCharacteristicValue::class, 'asset_id');
    }

    /**
     * @return BelongsTo<Asset, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'parent_id');
    }

    /**
     * Alias de relación inversa para Filament AssociateAction
     *
     * @return BelongsTo<Asset, $this>
     */
    public function asset(): BelongsTo
    {
        return $this->parent();
    }

    /**
     * @return HasMany<Asset, $this>
     */
    public function components(): HasMany
    {
        return $this->hasMany(Asset::class, 'parent_id');
    }

    protected $casts = [
        'warranty_expiration' => 'date',
        'purchase_date' => 'date',
    ];

    /**
     * Genera el siguiente Código de TI automático (ej. COD-TI-0001, COD-TI-0002).
     */
    public static function generateNextComputerCode(): string
    {
        $maxId = static::max('id') ?? 0;
        $nextNum = $maxId + 1;

        while (static::where('computer_code', sprintf('COD-TI-%04d', $nextNum))->exists()) {
            $nextNum++;
        }

        return sprintf('COD-TI-%04d', $nextNum);
    }

    protected static function booted(): void
    {
        static::creating(function (Asset $asset) {
            if (empty($asset->computer_code)) {
                $asset->computer_code = static::generateNextComputerCode();
            }
        });
        static::updated(function (Asset $asset) {
            if ($asset->isDirty('status')) {
                foreach ($asset->components as $component) {
                    $component->update(['status' => $asset->status]);
                }
            }
        });
    }

    /**
     * @return HasMany<AssetAssignment, $this>
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(AssetAssignment::class);
    }

    /**
     * @return HasMany<AssetMaintenance, $this>
     */
    public function maintenances(): HasMany
    {
        return $this->hasMany(AssetMaintenance::class);
    }

    /**
     * @return BelongsToMany<Software, $this>
     */
    public function softwares(): BelongsToMany
    {
        return $this->belongsToMany(Software::class, 'asset_software')
            ->withPivot('installed_at');
    }

    /**
     * @return HasMany<AssetLoan, $this>
     */
    public function loans(): HasMany
    {
        return $this->hasMany(AssetLoan::class);
    }

    /**
     * Scope para filtrar activos principales libres para préstamo temporal (sin asignación activa y no componentes de otra PC).
     */
    public function scopeAvailableForLoan($query)
    {
        return $query->whereNull('parent_id')
            ->where(function ($q) {
                $q->where('status', 'Disponible')
                  ->orWhere('status', 'disponible');
            })
            ->whereDoesntHave('assignments', fn ($q) => $q->whereNull('returned_at'));
    }

    /**
     * Retorna una etiqueta descriptiva para combos y selects.
     */
    public function getSelectOptionLabelAttribute(): string
    {
        $parts = [];
        $parts[] = "[{$this->computer_code}]";

        $brand = $this->model?->brand?->name ?? '';
        $model = $this->model?->name ?? '';
        $brandModel = trim("{$brand} {$model}");
        if ($brandModel !== '') {
            $parts[] = mb_strtoupper($brandModel);
        }

        if ($this->status) {
            $parts[] = "(" . mb_strtoupper((string) $this->status) . ")";
        }

        if ($this->asset_code) {
            $parts[] = "(PATRIMONIAL: {$this->asset_code})";
        }

        return implode(' ', $parts);
    }

    /**
     * Scope para búsqueda multi-campo y multi-palabra sobre activos
     * (código TI, código patrimonial, serie, color, estado físico, situación, modelo, marca, categoría).
     */
    public function scopeSearchTerms($query, ?string $search)
    {
        $search = trim((string) $search);
        if ($search === '') {
            return $query;
        }

        $terms = array_filter(explode(' ', $search));

        return $query->where(function ($q) use ($terms) {
            foreach ($terms as $term) {
                $q->where(function ($sub) use ($term) {
                    $sub->where('computer_code', 'ilike', "%{$term}%")
                        ->orWhere('asset_code', 'ilike', "%{$term}%")
                        ->orWhere('serial_number', 'ilike', "%{$term}%")
                        ->orWhere('color', 'ilike', "%{$term}%")
                        ->orWhere('estado', 'ilike', "%{$term}%")
                        ->orWhere('status', 'ilike', "%{$term}%")
                        ->orWhereHas('model', function ($mq) use ($term) {
                            $mq->where('name', 'ilike', "%{$term}%")
                               ->orWhereHas('brand', function ($bq) use ($term) {
                                   $bq->where('name', 'ilike', "%{$term}%");
                               });
                        })
                        ->orWhereHas('category', function ($cq) use ($term) {
                            $cq->where('name', 'ilike', "%{$term}%");
                        });
                });
            }
        });
    }
}
