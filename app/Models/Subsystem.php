<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subsystem extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'icon',
        'url_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
