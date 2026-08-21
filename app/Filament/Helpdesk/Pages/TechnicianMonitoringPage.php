<?php

namespace App\Filament\Helpdesk\Pages;

use App\Models\Ticket;
use App\Models\User;
use BackedEnum;
use Filament\Pages\Page;

class TechnicianMonitoringPage extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static string|\UnitEnum|null $navigationGroup = 'Soporte TI';

    protected static ?string $navigationLabel = 'Monitoreo de Técnicos';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Monitoreo de Disponibilidad Técnica';

    public function getHeading(): string
    {
        return 'Estado y Disponibilidad del Personal Técnico';
    }

    public function getSubheading(): ?string
    {
        return 'Control en tiempo real de técnicos ocupados en atención, asignaciones pendientes y personal disponible del área ODT.';
    }

    protected string $view = 'filament.helpdesk.pages.technician-monitoring-page';

    public function getTechniciansDataProperty(): array
    {
        // Filtrar exclusivamente personal activo de ODT o que cuente con algún rol de soporte
        $users = User::query()
            ->where('is_active', true)
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
            // Tickets en proceso activo de atención
            $activeTickets = Ticket::where('assigned_to', $user->id)
                ->where('status', 'en_proceso')
                ->with(['office', 'category', 'requester'])
                ->get();

            // Tickets abiertos pendientes de iniciar atención
            $pendingTickets = Ticket::where('assigned_to', $user->id)
                ->where('status', 'abierto')
                ->with(['office', 'category'])
                ->get();

            // Tickets resueltos hoy
            $resolvedTodayCount = Ticket::where('assigned_to', $user->id)
                ->where('status', 'resuelto')
                ->whereDate('resolved_at', now()->toDateString())
                ->count();

            // Determinación de estado
            if ($activeTickets->count() > 0) {
                $statusKey   = 'busy';
                $statusLabel = '🔴 EN ATENCIÓN (OCUPADO)';
                $statusClass = 'status-badge-busy';
            } elseif ($pendingTickets->count() > 0) {
                $statusKey   = 'pending';
                $statusLabel = '🟡 CON PENDIENTES';
                $statusClass = 'status-badge-pending';
            } else {
                $statusKey   = 'free';
                $statusLabel = '🟢 DISPONIBLE (LIBRE)';
                $statusClass = 'status-badge-free';
            }

            $data[] = [
                'user'            => $user,
                'statusKey'       => $statusKey,
                'statusLabel'     => $statusLabel,
                'statusClass'     => $statusClass,
                'activeTickets'   => $activeTickets,
                'pendingTickets'  => $pendingTickets,
                'resolvedToday'   => $resolvedTodayCount,
                'totalActive'     => $activeTickets->count() + $pendingTickets->count(),
            ];
        }

        // Ordenar: Primero técnicos Ocupados, luego Pendientes, luego Libres
        usort($data, function ($a, $b) {
            $priority = ['busy' => 1, 'pending' => 2, 'free' => 3];
            return ($priority[$a['statusKey']] ?? 9) <=> ($priority[$b['statusKey']] ?? 9);
        });

        return $data;
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        return $user->can('monitorear-tecnicos') ||
               $user->can('consultar-tickets') ||
               $user->can('gestionar-tickets');
    }
}
