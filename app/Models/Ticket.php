<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUppercaseAttributes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasUppercaseAttributes;

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
        'started_at',
    ];

    protected $casts = [
        'attachments' => 'array',
        'save_to_knowledge_base' => 'boolean',
        'sla_expires_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
        'started_at' => 'datetime',
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

            if ($ticket->isDirty('status') && in_array($ticket->status, ['En Proceso', 'en_proceso', 'Internado', 'En Espera', 'Esperando Terceros']) && empty($ticket->started_at)) {
                $ticket->started_at = now();
            }
            if ($ticket->isDirty('status') && $ticket->status === 'Abierto') {
                $ticket->started_at = null;
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

            // ─────────────────────────────────────────────────────────────────
            // LÓGICA DE ACTIVOS EN INVENTARIO
            // ─────────────────────────────────────────────────────────────────
            // "Internado" → El equipo entra al taller para evaluación profunda.
            //               El activo pasa a "En Evaluación" y se cierra su asignación.
            //               Si hay un activo de préstamo, se asigna temporalmente al solicitante.
            //
            // "Resuelto"  → Solo actúa si el activo NO está ya en "En Evaluación"
            //               (evita doble procesamiento cuando el ticket pasó por "Internado").
            //               Para tickets resueltos directamente (sin internar), funciona igual.
            //
            // "Cerrado"   → Solo cierre administrativo. No modifica el inventario.
            // ─────────────────────────────────────────────────────────────────

            $debeProcessarActivos =
                $ticket->isDirty('status') && (
                    // Caso 1: Internado → siempre procesa
                    $ticket->status === 'Internado'
                    ||
                    // Caso 2: Resuelto → solo si el activo afectado NO está ya en Evaluación
                    (
                        $ticket->status === 'Resuelto'
                        && (function () use ($ticket): bool {
                            if (! $ticket->affected_asset_id) return true; // sin activo: procesar normalmente
                            $affected = \App\Models\Asset::find($ticket->affected_asset_id);
                            // Si ya está En Evaluación, significa que "Internado" ya lo procesó → omitir
                            return $affected && $affected->status !== 'En Evaluación';
                        })()
                    )
                );

            if ($debeProcessarActivos && $ticket->affected_asset_id) {
                $affected = $ticket->affectedAsset;
                if ($affected) {
                    $parentId = $affected->parent_id;

                    // Determinar contexto para la nota
                    $contexto = $ticket->status === 'Internado'
                        ? "Internado al taller en Ticket {$ticket->ticket_code}. Pendiente evaluación técnica."
                        : "Puesto En Evaluación por reemplazo en Ticket {$ticket->ticket_code}. Pendiente dictamen técnico para determinar baja definitiva.";

                    // Cambiar estado a En Evaluación
                    $affected->update([
                        'status'    => 'En Evaluación',
                        'parent_id' => null,
                        'notes'     => trim(($affected->notes ?? '') . "\n" . $contexto),
                    ]);

                    // Finalizar asignación activa del afectado
                    \App\Models\AssetAssignment::where('asset_id', $affected->id)
                        ->whereNull('returned_at')
                        ->update([
                            'returned_at' => now(),
                            'notes'       => $ticket->status === 'Internado'
                                ? "Asignación suspendida por internado del equipo en Ticket {$ticket->ticket_code}"
                                : "Asignación finalizada por reemplazo en Ticket {$ticket->ticket_code}",
                        ]);

                    // Si hay activo de préstamo/repuesto, asignarlo al solicitante
                    if ($ticket->replacement_asset_id) {
                        $replacement = $ticket->replacementAsset;
                        if ($replacement) {
                            $esInterno     = $ticket->status === 'Internado';
                            $notaRepuesto  = $esInterno
                                ? "Prestado temporalmente mientras {$affected->computer_code} está internado — Ticket {$ticket->ticket_code}"
                                : "Asignado como reemplazo de {$affected->computer_code} en Ticket {$ticket->ticket_code}";

                            $replacement->update([
                                'status'   => 'Asignado',
                                'parent_id' => $parentId,
                                'notes'    => trim(($replacement->notes ?? '') . "\n" . $notaRepuesto),
                            ]);

                            // Crear asignación para el solicitante
                            \App\Models\AssetAssignment::create([
                                'asset_id'    => $replacement->id,
                                'user_id'     => $ticket->requester_id,
                                'office_id'   => $ticket->office_id,
                                'assigned_at' => now(),
                                'notes'       => $notaRepuesto,
                            ]);
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

    /**
     * Calcula el tiempo transcurrido de atención desde que el técnico le dio en atender.
     */
    public function getElapsedAttentionTimeAttribute(): string
    {
        $startTime = $this->started_at ?? $this->updated_at ?? $this->created_at;
        if (! $startTime) {
            return '0m';
        }

        $diff = $startTime->diff(now());

        $parts = [];
        if ($diff->d > 0) {
            $parts[] = "{$diff->d}d";
        }
        if ($diff->h > 0) {
            $parts[] = "{$diff->h}h";
        }
        $parts[] = "{$diff->i}m";

        return implode(' ', $parts) ?: '0m';
    }
}
