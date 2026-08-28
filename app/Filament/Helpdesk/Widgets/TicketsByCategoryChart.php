<?php

namespace App\Filament\Helpdesk\Widgets;

use App\Models\Ticket;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TicketsByCategoryChart extends ChartWidget
{
    protected static ?int $sort = 2;
    protected ?string $maxHeight = '280px';

    public function getHeading(): ?string
    {
        return 'Incidencias por Categoría';
    }

    protected function getData(): array
    {
        $data = Ticket::select('user_category', DB::raw('count(*) as total'))
            ->whereNotNull('user_category')
            ->groupBy('user_category')
            ->pluck('total', 'user_category')
            ->toArray();

        $labels = array_keys($data);
        $values = array_values($data);

        if (empty($labels)) {
            $labels = ['Sin datos registrados'];
            $values = [0];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Incidencias',
                    'data' => $values,
                    'backgroundColor' => [
                        '#008435',
                        '#0284c7',
                        '#6366f1',
                        '#d97706',
                        '#ec4899',
                        '#8b5cf6',
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
