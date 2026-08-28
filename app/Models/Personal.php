<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUppercaseAttributes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Personal extends Model
{
    use HasUppercaseAttributes;

    protected $table = 'personals';

    protected $fillable = [
        'document_type_id',
        'document_number',
        'first_name',
        'paternal_surname',
        'maternal_surname',
        'full_name',
        'gender',
        'birth_date',
        'email',
        'phone',
        'address',
        'ubigeo_code',
        'labor_condition_id',
        'office_id',
        'position',
        'hire_date',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'hire_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function laborCondition(): BelongsTo
    {
        return $this->belongsTo(LaborCondition::class);
    }

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    public function ubigeo(): BelongsTo
    {
        return $this->belongsTo(Ubigeo::class, 'ubigeo_code', 'code');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'personal_id');
    }
}
