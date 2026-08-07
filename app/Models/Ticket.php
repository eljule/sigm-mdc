<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_code',
        'category_id',
        'user_category',
        'requester_id',
        'office_id',
        'affected_asset_id',
        'replacement_asset_id',
        'assigned_to',
        'title',
        'description',
        'attachments',
        'priority',
        'impact',
        'status',
        'solution_applied',
        'diagnosis',
        'root_cause',
        'save_to_knowledge_base',
        'sla_expires_at',
        'resolved_at',
        'closed_at',
    ];

    protected $casts = [
        'attachments' => 'array',
        'save_to_knowledge_base' => 'boolean',
        'sla_expires_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Ticket $ticket) {
            // Mapear user_category simplificada a category_id técnica si no se proveyó una
            if (empty($ticket->category_id) && ! empty($ticket->user_category)) {
                $mapping = [
                    'Equipos/Hardware' => 'Hardware y Computadores',
                    'Sistemas/Programas' => 'Sistemas Municipales',
                    'Accesos/Contraseñas' => 'Sistemas Municipales',
                    'Red/Internet' => 'Red y Conectividad',
                ];

                $targetName = $mapping[$ticket->user_category] ?? 'Sistemas Municipales';
                $category = TicketCategory::where('name', $targetName)->first();
                if ($category) {
                    $ticket->category_id = $category->id;
                } else {
                    $ticket->category_id = TicketCategory::first()?->id;
                }
            }

            // Generar código de ticket correlativo anual
            if (empty($ticket->ticket_code)) {
                $year = date('Y');
                $latest = self::where('ticket_code', 'like', "INC-{$year}-%")
                    ->orderBy('id', 'desc')
                    ->first();

                if ($latest) {
                    $parts = explode('-', $latest->ticket_code);
                    $seq = (int) end($parts);
                    $ticket->ticket_code = "INC-{$year}-".str_pad((string) ($seq + 1), 4, '0', STR_PAD_LEFT);
                } else {
                    $ticket->ticket_code = "INC-{$year}-0001";
                }
            }

            // Calcular expiración del SLA
            if ($ticket->category_id && empty($ticket->sla_expires_at)) {
                $category = TicketCategory::find($ticket->category_id);
                if ($category) {
                    $ticket->sla_expires_at = now()->addHours($category->sla_hours);
                }
            }
        });

        static::updating(function (Ticket $ticket) {
            // Revertir consumibles si el ticket estaba Resuelto/Cerrado y cambia a cualquier otro estado abierto
            if ($ticket->isDirty('status') && ! in_array($ticket->status, ['Resuelto', 'Cerrado'])) {
                if (in_array($ticket->getOriginal('status'), ['Resuelto', 'Cerrado'])) {
                    foreach ($ticket->ticketConsumables as $tc) {
                        $tc->consumable->increment('stock', $tc->quantity);
                    }
                }
            }

            // Descontar consumibles si el ticket cambia a Resuelto/Cerrado
            if ($ticket->isDirty('status') && in_array($ticket->status, ['Resuelto', 'Cerrado'])) {
                if (! in_array($ticket->getOriginal('status'), ['Resuelto', 'Cerrado'])) {
                    foreach ($ticket->ticketConsumables as $tc) {
                        $tc->consumable->decrement('stock', $tc->quantity);
                    }
                }
            }

            if ($ticket->isDirty('status') && $ticket->status === 'Abierto') {
                $ticket->assigned_to = null;
                $ticket->solution_applied = null;
                $ticket->diagnosis = null;
                $ticket->root_cause = null;
                $ticket->save_to_knowledge_base = false;
                $ticket->resolved_at = null;
                $ticket->closed_at = null;
                $ticket->affected_asset_id = null;
                $ticket->replacement_asset_id = null;
            }
            if ($ticket->isDirty('status') && $ticket->status === 'Resuelto') {
                $ticket->resolved_at = now();
            }
            if ($ticket->isDirty('status') && $ticket->status === 'Cerrado') {
                $ticket->closed_at = now();
            }

            // Ejecutar reemplazo de activos en inventario SOLO al pasar a Resuelto.
            // 'Cerrado' es una confirmación administrativa y no debe repetir la lógica de inventario.
            if ($ticket->isDirty('status') && $ticket->status === 'Resuelto') {
                if ($ticket->affected_asset_id) {
                    $affected = $ticket->affectedAsset;
                    if ($affected) {
                        $parentId = $affected->parent_id;

                        // Cambiar estado a En Evaluación (pendiente dictamen técnico)
                        $affected->update([
                            'status' => 'En Evaluación',
                            'parent_id' => null,
                            'notes' => trim(($affected->notes ?? "") . "\nPuesto En Evaluación por reemplazo en Ticket " . $ticket->ticket_code . ". Pendiente dictamen técnico para determinar baja definitiva."),
                        ]);

                        // Finalizar asignación activa del afectado
                        \App\Models\AssetAssignment::where('asset_id', $affected->id)
                            ->whereNull('returned_at')
                            ->update([
                                'returned_at' => now(),
                                'notes' => "Asignación finalizada por baja en Ticket " . $ticket->ticket_code,
                            ]);

                        // Si hay un repuesto disponible, reasignarlo y reconstruir jerarquía
                        if ($ticket->replacement_asset_id) {
                            $replacement = $ticket->replacementAsset;
                            if ($replacement) {
                                $replacement->update([
                                    'status' => 'Asignado',
                                    'parent_id' => $parentId,
                                    'notes' => trim(($replacement->notes ?? "") . "\nAsignado como reemplazo de " . $affected->computer_code . " en Ticket " . $ticket->ticket_code),
                                ]);

                                // Crear nueva asignación para el solicitante
                                \App\Models\AssetAssignment::create([
                                    'asset_id' => $replacement->id,
                                    'user_id' => $ticket->requester_id,
                                    'office_id' => $ticket->office_id,
                                    'assigned_at' => now(),
                                    'notes' => "Asignado como reemplazo de " . $affected->computer_code . " en Ticket " . $ticket->ticket_code,
                                ]);
                            }
                        }
                    }
                }
            }
        });

        static::saved(function (Ticket $ticket) {
            if ($ticket->save_to_knowledge_base && in_array($ticket->status, ['Resuelto', 'Cerrado'])) {
                // Evitar duplicados por título y categoría
                $exists = KnowledgeBase::where('title', $ticket->title)
                    ->where('category_id', $ticket->category_id)
                    ->exists();

                if (! $exists) {
                    KnowledgeBase::create([
                        'category_id' => $ticket->category_id,
                        'title' => $ticket->title,
                        'symptoms' => $ticket->description,
                        'solution' => "Diagnóstico: " . ($ticket->diagnosis ?? 'N/D') . "\n\nSolución: " . ($ticket->solution_applied ?? 'N/D'),
                        'is_published' => true,
                        'author_id' => $ticket->assigned_to ?? auth()->id(),
                    ]);
                }
            }
        });
    }

    /**
     * @return BelongsTo<TicketCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(TicketCategory::class, 'category_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    /**
     * @return BelongsTo<Office, $this>
     */
    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class, 'office_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * @return BelongsTo<Asset, $this>
     */
    public function affectedAsset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'affected_asset_id');
    }

    /**
     * @return BelongsTo<Asset, $this>
     */
    public function replacementAsset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'replacement_asset_id');
    }

    /**
     * @return HasMany<TicketConsumable, $this>
     */
    public function ticketConsumables(): HasMany
    {
        return $this->hasMany(TicketConsumable::class);
    }

    /**
     * @return HasMany<AssetMaintenance, $this>
     */
    public function maintenances(): HasMany
    {
        return $this->hasMany(AssetMaintenance::class);
    }
}
