<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetBlock extends Model
{
    protected $fillable = ['asset_category_id', 'name', 'sort_order'];

    /**
     * @return BelongsTo<AssetCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(AssetCategory::class, 'asset_category_id');
    }

    /**
     * @return HasMany<AssetCharacteristic, $this>
     */
    public function characteristics(): HasMany
    {
        return $this->hasMany(AssetCharacteristic::class, 'asset_block_id')->orderBy('sort_order');
    }
}
