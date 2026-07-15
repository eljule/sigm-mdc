<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ubigeo extends Model
{
    protected $primaryKey = 'code';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'code',
        'department',
        'province',
        'district',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
