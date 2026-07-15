<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    protected $fillable = [
        'asset_code',
        'category',
        'brand',
        'model',
        'serial_number',
        'processor',
        'ram',
        'storage',
        'ip_address',
        'mac_address',
        'status',
        'warranty_expiration',
        'purchase_date',
        'notes',
    ];

    protected $casts = [
        'warranty_expiration' => 'date',
        'purchase_date' => 'date',
    ];

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
