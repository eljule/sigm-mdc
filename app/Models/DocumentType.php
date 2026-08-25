<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUppercaseAttributes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentType extends Model
{
    use HasUppercaseAttributes;

    protected $fillable = [
        'code',
        'name',
        'length',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'length' => 'integer',
    ];

    /**
     * @return HasMany<User, $this>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
