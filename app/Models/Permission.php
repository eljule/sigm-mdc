<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    /**
     * @return BelongsTo<Subsystem, $this>
     */
    public function subsystem(): BelongsTo
    {
        return $this->belongsTo(Subsystem::class);
    }
}
