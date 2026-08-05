<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asset extends Model
{
    protected $fillable = [
        'parent_id',
        'asset_category_id',
        'asset_model_id',
        'asset_code',
        'computer_code',
        'serial_number',
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

    protected static function booted(): void
    {
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
}
