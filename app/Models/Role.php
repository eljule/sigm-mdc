<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\PermissionRegistrar;

class Role extends SpatieRole
{
    /**
     * @return BelongsTo<Subsystem, $this>
     */
    public function subsystem(): BelongsTo
    {
        return $this->belongsTo(Subsystem::class);
    }

    protected static function booted(): void
    {
        static::saved(function () {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        });

        static::deleted(function () {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        });
    }
}
