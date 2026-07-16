<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetCharacteristicValue extends Model
{
    protected $fillable = ['asset_id', 'asset_characteristic_id', 'value'];

    /**
     * @return BelongsTo<Asset, $this>
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    /**
     * @return BelongsTo<AssetCharacteristic, $this>
     */
    public function characteristic(): BelongsTo
    {
        return $this->belongsTo(AssetCharacteristic::class, 'asset_characteristic_id');
    }
}
