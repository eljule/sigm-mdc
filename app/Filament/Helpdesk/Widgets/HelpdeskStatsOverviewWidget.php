<?php

namespace App\Filament\Helpdesk\Widgets;

use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class HelpdeskStatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $openCount = Ticket::whereIn('status', ['abierto', 'Abierto'])->count();
        $inProgressCount = Ticket::whereIn('status', ['en proceso', 'En Proceso'])->count();
        $resolvedToday = Ticket::whereIn('status', ['resuelto', 'cerrado', 'Resuelto', 'Cerrado'])
            ->whereDate('updated_at', now()->toDateString())
            ->count();
        $slaBreached = Ticket::whereNotIn('status', ['resuelto', 'cerrado', 'Resuelto', 'Cerrado'])
            ->whereNotNull('sla_expires_at')
            ->where('sla_expires_at', '<', now())
            ->count();

        return [
            Stat::make('Tickets Abiertos', $openCount)
                ->description('Incidencias pendientes')
                ->descriptionIcon('heroicon-m-fire')
                ->color($openCount > 0 ? 'danger' : 'success'),

            Stat::make('En Proceso', $inProgressCount)
                ->description('Atención activa por técnicos')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('warning'),

            Stat::make('Resueltos Hoy', $resolvedToday)
                ->description('Atenciones concluidas hoy')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('SLA Vencidos', $slaBreached)
                ->description('Fuera del límite de atención')
                ->descriptionIcon('heroicon-m-clock')
                ->color($slaBreached > 0 ? 'danger' : 'gray'),
        ];
    }
}
