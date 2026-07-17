<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetModel extends Model
{
    protected $fillable = ['asset_brand_id', 'name', 'description'];

    /**
     * @return BelongsTo<AssetBrand, $this>
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(AssetBrand::class, 'asset_brand_id');
    }

    /**
     * @return HasMany<Asset, $this>
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'asset_model_id');
    }
}
