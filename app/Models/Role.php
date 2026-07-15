<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    /**
     * @return BelongsTo<Subsystem, $this>
     */
    public function subsystem(): BelongsTo
    {
        return $this->belongsTo(Subsystem::class);
    }
}
