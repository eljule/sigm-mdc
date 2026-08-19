<?php

namespace App\Filament\Itam\Pages;

use App\Models\Asset;
use App\Models\AssetLoan;
use BackedEnum;
use Filament\Pages\Page;

class LoanCalendarPage extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar';

    protected static string|\UnitEnum|null $navigationGroup = 'Gestión TI';

    protected static ?string $navigationLabel = 'Calendario de Disponibilidad';

    protected static ?int $navigationSort = 13;

    protected static ?string $title = 'Calendario de Disponibilidad';

    public function getHeading(): string
    {
        return 'Calendario de Disponibilidad de Equipos';
    }

    public function getSubheading(): ?string
    {
        return 'Agenda interactiva y reservas de cañones multimedia, laptops y activos independientes disponibles.';
    }

    protected string $view = 'filament.itam.pages.loan-calendar-page';

    public ?int $selectedAssetId = null;

    public function mount(): void
    {
        // Seleccionar por defecto el primer activo prestable sin asignación ni vinculación como componente
        $projector = Asset::availableForLoan()
            ->whereHas('category', function ($q) {
                $q->where('name', 'ILIKE', '%cañón%')
                  ->orWhere('name', 'ILIKE', '%proyector%')
                  ->orWhere('name', 'ILIKE', '%multimedia%');
            })->first();

        $this->selectedAssetId = $projector?->id ?? Asset::availableForLoan()->first()?->id;
    }

    public function getAssetsProperty()
    {
        // Solo activos principales que NO son componentes de otra PC y NO están asignados a un área
        return Asset::availableForLoan()
            ->with(['model.brand', 'category'])
            ->get();
    }

    public function getEventsProperty()
    {
        $query = AssetLoan::with(['asset.category', 'office']);

        if ($this->selectedAssetId) {
            $query->where('asset_id', $this->selectedAssetId);
        }

        return $query->get()->map(function ($loan) {
            $color = match ($loan->status) {
                'pending'   => '#eab308',
                'active'    => '#22c55e',
                'returned'  => '#64748b',
                'overdue'   => '#ef4444',
                'cancelled' => '#94a3b8',
                default     => '#3b82f6',
            };

            $office = $loan->office?->name ?? 'Oficina N/A';
            $title = "{$loan->loan_number}: {$loan->borrower_name} ({$office})";

            return [
                'id'       => (string) $loan->id,
                'title'    => $title,
                'start'    => $loan->start_time->toIso8601String(),
                'end'      => $loan->end_time->toIso8601String(),
                'color'    => $color,
                'url'      => route('fichas.prestamo_equipo', ['id' => $loan->id]),
            ];
        });
    }
}
