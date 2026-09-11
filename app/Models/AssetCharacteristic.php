<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUppercaseAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetCharacteristic extends Model
{
    use HasUppercaseAttributes;

    protected $fillable = ['asset_block_id', 'name', 'type', 'options', 'is_required', 'sort_order'];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    /**
     * @return BelongsTo<AssetBlock, $this>
     */
    public function block(): BelongsTo
    {
        return $this->belongsTo(AssetBlock::class, 'asset_block_id');
    }

    /**
     * @return HasMany<AssetCharacteristicValue, $this>
     */
    public function values(): HasMany
    {
        return $this->hasMany(AssetCharacteristicValue::class, 'asset_characteristic_id');
    }
}