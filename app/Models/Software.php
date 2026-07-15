<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Software extends Model
{
    protected $table = 'softwares';

    protected $fillable = [
        'name',
        'version',
        'license_type',
        'license_key',
        'expiration_date',
        'max_activations',
    ];

    protected $casts = [
        'expiration_date' => 'date',
        'max_activations' => 'integer',
    ];

    /**
     * @return BelongsToMany<Asset, $this>
     */
    public function assets(): BelongsToMany
    {
        return $this->belongsToMany(Asset::class, 'asset_software')
            ->withPivot('installed_at');
    }
}
