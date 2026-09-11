<?php

namespace App\Filament\Itam\Widgets;

use App\Models\Asset;
use App\Models\Consumable;
use App\Models\AssetLoan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ItamStatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $availableAssets = Asset::whereIn('status', ['disponible', 'Disponible'])->count();
        $assignedAssets = Asset::whereIn('status', ['asignado', 'Asignado'])->count();
        $lowStockConsumables = Consumable::whereColumn('stock', '<=', 'min_stock')->count();
        $activeLoans = AssetLoan::whereIn('status', ['active', 'pending', 'overdue'])->count();

        return [
            Stat::make('Activos Disponibles', $availableAssets)
                ->description('Equipos listos para asignación')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),

            Stat::make('Activos Asignados', $assignedAssets)
                ->description('Equipos en uso operativo')
                ->descriptionIcon('heroicon-m-user')
                ->color('info'),

            Stat::make('Consumables Alerta Stock', $lowStockConsumables)
                ->description('Bajo el stock mínimo')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($lowStockConsumables > 0 ? 'danger' : 'success'),

            Stat::make('Préstamos de Equipos', $activeLoans)
                ->description('Préstamos activos o pendientes')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}
