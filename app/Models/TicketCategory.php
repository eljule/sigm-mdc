<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUppercaseAttributes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketCategory extends Model
{
    use HasUppercaseAttributes;

    protected $fillable = [
        'name',
        'description',
        'sla_hours',
        'is_active',
    ];

    protected $casts = [
        'sla_hours' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * @return HasMany<Ticket, $this>
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'category_id');
    }

    /**
     * @return HasMany<KnowledgeBase, $this>
     */
    public function knowledgeBaseArticles(): HasMany
    {
        return $this->hasMany(KnowledgeBase::class, 'category_id');
    }
}
