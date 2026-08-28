<?php

namespace App\Filament\Itam\Widgets;

use App\Models\Asset;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class AssetsByCategoryChart extends ChartWidget
{
    protected static ?int $sort = 2;
    protected ?string $maxHeight = '280px';

    public function getHeading(): ?string
    {
        return 'Flota Tecnológica por Categoría';
    }

    protected function getData(): array
    {
        $data = Asset::join('asset_categories', 'asset_categories.id', '=', 'assets.asset_category_id')
            ->select('asset_categories.name as category_name', DB::raw('count(assets.id) as total'))
            ->groupBy('asset_categories.name')
            ->pluck('total', 'category_name')
            ->toArray();

        $labels = array_keys($data);
        $values = array_values($data);

        if (empty($labels)) {
            $labels = ['Sin activos registrados'];
            $values = [0];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Equipos',
                    'data' => $values,
                    'backgroundColor' => [
                        '#008435',
                        '#0284c7',
                        '#f59e0b',
                        '#8b5cf6',
                        '#10b981',
                        '#6366f1',
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
