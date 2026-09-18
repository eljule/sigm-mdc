<?php

namespace App\Filament\Helpdesk\Pages;

use App\Models\Ticket;
use App\Models\User;
use BackedEnum;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class TechnicianMonitoringPage extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static string|\UnitEnum|null $navigationGroup = 'Soporte TI';

    protected static ?string $navigationLabel = 'Monitoreo de Técnicos';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Monitoreo de Disponibilidad Técnica';

    public string $ticketFilter = 'all';

    public function setTicketFilter(string $filter): void
    {
        $this->ticketFilter = $filter;
    }

    public function getHeading(): string
    {
        return 'Estado y Disponibilidad del Personal Técnico';
    }

    public function getSubheading(): ?string
    {
        return 'Control en tiempo real de técnicos ocupados en atención, asignaciones pendientes y tickets sin cerrar en el sistema.';
    }

    protected string $view = 'filament.helpdesk.pages.technician-monitoring-page';

    protected function getViewData(): array
    {
        return [
            'techniciansData' => $this->techniciansData,
            'unclosedTickets' => $this->unclosedTickets,
            'unclosedStats'   => $this->unclosedStats,
            'ticketFilter'    => $this->ticketFilter,
        ];
    }

    public function getTechniciansDataProperty(): array
    {
        // IDs de usuarios con rol de Jefatura/Administrador de Helpdesk o TI que se excluyen de la atención técnica directa
        $excludedUserIds = DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->whereIn('roles.name', ['Administrador de Helpdesk', 'Administrador Central', 'Administrador de TI'])
            ->pluck('model_has_roles.model_id')
            ->unique()
            ->toArray();

        // Filtrar exclusivamente personal operativo técnico activo de ODT
        $users = User::query()
            ->where('is_active', true)
            ->whereNotIn('id', $excludedUserIds)
            ->where(function ($query) {
                $query->whereHas('office', function ($q) {
                    $q->where('acronym', 'ODT')
                      ->orWhere('code', '02.02.05')
                      ->orWhere('name', 'ILIKE', '%Desarrollo Tecnológico%');
                })
                ->orWhereHas('roles', function ($q) {
                    $q->where('name', 'ILIKE', '%soporte%');
                });
            })
            ->get();

        $data = [];

        foreach ($users as $user) {
            // Tickets en proceso activo de atención (case-insensitive para PostgreSQL)
            $activeTickets = Ticket::where('assigned_to', $user->id)
                ->whereRaw("LOWER(status) IN ('en proceso', 'en_proceso', 'internado', 'en espera', 'esperando terceros')")
                ->with(['office', 'category', 'requester'])
                ->get();

            // Tickets abiertos pendientes de iniciar atención
            $pendingTickets = Ticket::where('assigned_to', $user->id)
                ->whereRaw("LOWER(status) = 'abierto'")
                ->with(['office', 'category', 'requester'])
                ->get();

            // Tickets resueltos pero no cerrados (resueltos pendientes de cierre definitivo)
            $resolvedUnclosedTickets = Ticket::where('assigned_to', $user->id)
                ->whereRaw("LOWER(status) = 'resuelto'")
                ->whereNull('closed_at')
                ->with(['office', 'category', 'requester'])
                ->get();

            // Tickets resueltos hoy
            $resolvedTodayCount = Ticket::where('assigned_to', $user->id)
                ->whereRaw("LOWER(status) IN ('resuelto', 'cerrado')")
                ->whereDate('resolved_at', now()->toDateString())
                ->count();

            // Determinación de estado
            if ($activeTickets->count() > 0) {
                $statusKey   = 'busy';
                $statusLabel = 'EN ATENCIÓN (OCUPADO)';
                $statusClass = 'status-badge-busy';
            } elseif ($pendingTickets->count() > 0) {
                $statusKey   = 'pending';
                $statusLabel = 'CON PENDIENTES';
                $statusClass = 'status-badge-pending';
            } else {
                $statusKey   = 'free';
                $statusLabel = 'DISPONIBLE (LIBRE)';
                $statusClass = 'status-badge-free';
            }

            // Iniciales del técnico
            $words = explode(' ', trim($user->name));
            $initials = '';
            if (count($words) >= 2) {
                $initials = mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1);
            } else {
                $initials = mb_substr($user->name, 0, 2);
            }
            $initials = mb_strtoupper($initials, 'UTF-8');

            $data[] = [
                'user'                    => $user,
                'initials'                => $initials,
                'statusKey'               => $statusKey,
                'statusLabel'             => $statusLabel,
                'statusClass'             => $statusClass,
                'activeTickets'           => $activeTickets,
                'pendingTickets'          => $pendingTickets,
                'resolvedUnclosedTickets' => $resolvedUnclosedTickets,
                'resolvedToday'           => $resolvedTodayCount,
                'totalActive'             => $activeTickets->count() + $pendingTickets->count(),
            ];
        }

        // Ordenar: Primero técnicos Ocupados, luego Pendientes, luego Libres
        usort($data, function ($a, $b) {
            $priority = ['busy' => 1, 'pending' => 2, 'free' => 3];
            return ($priority[$a['statusKey']] ?? 9) <=> ($priority[$b['statusKey']] ?? 9);
        });

        return $data;
    }

    /**
     * Estadísticas de tickets sin cerrar para los botones de filtro rápido
     */
    public function getUnclosedStatsProperty(): array
    {
        $base = Ticket::query()->whereRaw("LOWER(status) != 'cerrado'")->whereNull('closed_at');

        return [
            'all'         => (clone $base)->count(),
            'in_progress' => (clone $base)->whereRaw("LOWER(status) IN ('en proceso', 'en_proceso', 'internado', 'en espera', 'esperando terceros')")->count(),
            'open'        => (clone $base)->whereRaw("LOWER(status) = 'abierto'")->count(),
            'resolved'    => (clone $base)->whereRaw("LOWER(status) = 'resuelto'")->count(),
        ];
    }

    /**
     * Obtiene todos los tickets que se encuentran sin cerrar en el sistema.
     * Soporta filtrado reactivo con Livewire.
     */
    public function getUnclosedTicketsProperty(): Collection
    {
        $query = Ticket::query()
            ->whereRaw("LOWER(status) != 'cerrado'")
            ->whereNull('closed_at')
            ->with(['office', 'category', 'requester', 'assignee']);

        if ($this->ticketFilter === 'in_progress') {
            $query->whereRaw("LOWER(status) IN ('en proceso', 'en_proceso', 'internado', 'en espera', 'esperando terceros')");
        } elseif ($this->ticketFilter === 'open') {
            $query->whereRaw("LOWER(status) = 'abierto'");
        } elseif ($this->ticketFilter === 'resolved') {
            $query->whereRaw("LOWER(status) = 'resuelto'");
        }

        return $query->orderByRaw("
                CASE 
                    WHEN LOWER(status) IN ('en proceso', 'en_proceso', 'internado') THEN 1
                    WHEN LOWER(status) = 'abierto' THEN 2
                    WHEN LOWER(status) IN ('en espera', 'esperando terceros') THEN 3
                    WHEN LOWER(status) = 'resuelto' THEN 4
                    ELSE 5
                END ASC
            ")
            ->orderBy('id', 'desc')
            ->get();
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        return $user->can('monitorear-tecnicos');
    }
}
