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
        'requester_name',
        'contact_phone',
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
                $userCat = mb_strtolower(trim((string) $ticket->user_category), 'UTF-8');
                $mapping = [
                    'equipos/hardware' => 'hardware y computadores',
                    'sistemas/programas' => 'sistemas municipales',
                    'accesos/contraseñas' => 'sistemas municipales',
                    'accesos/contrasenas' => 'sistemas municipales',
                    'red/internet' => 'red y conectividad',
                    'otros' => 'sistemas municipales',
                ];

                $targetName = $mapping[$userCat] ?? 'sistemas municipales';
                $category = TicketCategory::whereRaw('LOWER(name) = ?', [$targetName])->first();
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
            $statusLower = strtolower((string) $ticket->status);
            $origStatusLower = strtolower((string) $ticket->getOriginal('status'));

            // Revertir consumibles si el ticket estaba Resuelto/Cerrado y cambia a cualquier otro estado abierto
            if ($ticket->isDirty('status') && ! in_array($statusLower, ['resuelto', 'cerrado'])) {
                if (in_array($origStatusLower, ['resuelto', 'cerrado'])) {
                    foreach ($ticket->ticketConsumables as $tc) {
                        $tc->consumable->increment('stock', $tc->quantity);
                    }
                }
            }

            // Descontar consumibles si el ticket cambia a Resuelto/Cerrado
            if ($ticket->isDirty('status') && in_array($statusLower, ['resuelto', 'cerrado'])) {
                if (! in_array($origStatusLower, ['resuelto', 'cerrado'])) {
                    foreach ($ticket->ticketConsumables as $tc) {
                        $tc->consumable->decrement('stock', $tc->quantity);
                    }
                }
            }

            if ($ticket->isDirty('status') && in_array($statusLower, ['en proceso', 'internado', 'en espera', 'esperando terceros']) && empty($ticket->started_at)) {
                $ticket->started_at = now();
            }
            if ($ticket->isDirty('status') && $statusLower === 'abierto') {
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
            if ($ticket->isDirty('status') && $statusLower === 'resuelto' && empty($ticket->resolved_at)) {
                $ticket->resolved_at = now();
            }
            if ($ticket->isDirty('status') && $statusLower === 'cerrado' && empty($ticket->closed_at)) {
                $ticket->closed_at = now();
            }

            // ─────────────────────────────────────────────────────────────────
            // LÓGICA DE ACTIVOS EN INVENTARIO (EVALUACIÓN, REEMPLAZO Y BAJA)
            // ─────────────────────────────────────────────────────────────────
            // Estados procesables: "internado", "resuelto", "cerrado"
            // Se dispara si cambia el estado o si se vincula/actualiza el activo con falla o el de reemplazo.
            // ─────────────────────────────────────────────────────────────────
            $estadosProcesables = ['internado', 'resuelto', 'cerrado'];
            $isTerminalOrInternado = in_array($statusLower, $estadosProcesables);

            $debeProcessarActivos = $isTerminalOrInternado && (
                $ticket->isDirty('status') ||
                $ticket->isDirty('affected_asset_id') ||
                $ticket->isDirty('replacement_asset_id')
            );

            if ($debeProcessarActivos) {
                $originalParentId = null;

                // 1. GESTIÓN DEL ACTIVO AFECTADO (Pase a "En Evaluación" para taller / dictamen)
                if ($ticket->affected_asset_id) {
                    $affected = \App\Models\Asset::find($ticket->affected_asset_id);
                    if ($affected) {
                        $originalParentId = $affected->parent_id;
                        $affectedStatus = strtolower((string) $affected->status);

                        // Si no está ya En Evaluación ni dado de baja, pasar a En Evaluación
                        if (! in_array($affectedStatus, ['en evaluación', 'en evaluacion', 'baja'])) {
                            $contexto = $statusLower === 'internado'
                                ? "Internado al taller en Ticket {$ticket->ticket_code}. Pendiente evaluación técnica."
                                : "Puesto En Evaluación por reemplazo en Ticket {$ticket->ticket_code}. Pendiente dictamen técnico para determinar baja definitiva.";

                            $affected->update([
                                'status'    => 'En Evaluación',
                                'parent_id' => null,
                                'notes'     => trim(($affected->notes ?? '') . "\n" . $contexto),
                            ]);

                            \App\Models\AssetAssignment::where('asset_id', $affected->id)
                                ->whereNull('returned_at')
                                ->update([
                                    'returned_at' => now(),
                                    'notes'       => $statusLower === 'internado'
                                        ? "Asignación suspendida por internado del equipo en Ticket {$ticket->ticket_code}"
                                        : "Asignación finalizada por reemplazo en Ticket {$ticket->ticket_code}",
                                ]);
                        }
                    }
                }

                // 2. GESTIÓN DEL ACTIVO DE REEMPLAZO O PRÉSTAMO
                if ($ticket->replacement_asset_id) {
                    $replacement = \App\Models\Asset::find($ticket->replacement_asset_id);
                    if ($replacement) {
                        $hasActiveAssignment = \App\Models\AssetAssignment::where('asset_id', $replacement->id)
                            ->whereNull('returned_at')
                            ->exists();

                        if (! $hasActiveAssignment) {
                            $esInterno = $statusLower === 'internado';
                            $notaRepuesto = $esInterno
                                ? "Prestado temporalmente mientras el equipo afectado está internado — Ticket {$ticket->ticket_code}"
                                : "Asignado como reemplazo en Ticket {$ticket->ticket_code}";

                            $targetParentId = $originalParentId ?? $ticket->affectedAsset?->parent_id;

                            $replacement->update([
                                'status'    => 'Asignado',
                                'parent_id' => $targetParentId,
                                'notes'     => trim(($replacement->notes ?? '') . "\n" . $notaRepuesto),
                            ]);

                            $targetUserId = $ticket->requester_id
                                ?? \App\Models\AssetAssignment::where('asset_id', $ticket->affected_asset_id)->latest('id')->value('user_id')
                                ?? auth()->id();

                            $targetOfficeId = $ticket->office_id
                                ?? \App\Models\AssetAssignment::where('asset_id', $ticket->affected_asset_id)->latest('id')->value('office_id');

                            if ($targetUserId) {
                                \App\Models\AssetAssignment::create([
                                    'asset_id'    => $replacement->id,
                                    'user_id'     => $targetUserId,
                                    'office_id'   => $targetOfficeId,
                                    'assigned_at' => now(),
                                    'notes'       => $notaRepuesto,
                                ]);
                            }
                        }
                    }
                }
            }
        });

        static::saved(function (Ticket $ticket) {
            if ($ticket->save_to_knowledge_base && in_array(strtolower((string) $ticket->status), ['resuelto', 'cerrado'])) {
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
        return $this->belongsTo(User::class, 'requester_id')
            ->withDefault(function ($user, $ticket) {
                $user->name = $ticket->requester_name ?? 'Usuario Municipal';
            });
    }

    public function getRequesterDisplayNameAttribute(): string
    {
        return $this->requester?->name ?? $this->requester_name ?? 'Usuario Municipal';
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
