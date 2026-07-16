<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetCategory extends Model
{
    protected $fillable = ['name', 'description'];

    /**
     * @return HasMany<AssetBlock, $this>
     */
    public function blocks(): HasMany
    {
        return $this->hasMany(AssetBlock::class, 'asset_category_id')->orderBy('sort_order');
    }

    /**
     * @return HasMany<Asset, $this>
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'asset_category_id');
    }
}
