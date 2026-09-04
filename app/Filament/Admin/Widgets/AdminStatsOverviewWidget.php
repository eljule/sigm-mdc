<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use App\Models\Office;
use App\Models\Subsystem;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $totalOffices = Office::count();
        $totalSubsystems = Subsystem::count();

        return [
            Stat::make('Usuarios Registrados', $totalUsers)
                ->description($activeUsers . ' activos en plataforma')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Oficinas Municipales', $totalOffices)
                ->description('Dependencias y áreas')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('info'),

            Stat::make('Subsistemas Habilitados', $totalSubsystems)
                ->description('Central, ITAM, Helpdesk, Licencias de Transportes')
                ->descriptionIcon('heroicon-m-squares-2x2')
                ->color('warning'),
        ];
    }
}
