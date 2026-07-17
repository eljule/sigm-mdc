<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetBrand extends Model
{
    protected $fillable = ['name', 'description'];

    /**
     * @return HasMany<AssetModel, $this>
     */
    public function models(): HasMany
    {
        return $this->hasMany(AssetModel::class, 'asset_brand_id')->orderBy('name');
    }
}
