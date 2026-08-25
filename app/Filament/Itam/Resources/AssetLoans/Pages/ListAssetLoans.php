<?php

namespace App\Filament\Itam\Resources\AssetLoans\Pages;

use App\Filament\Itam\Resources\AssetLoans\AssetLoanResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAssetLoans extends ListRecords
{
    protected static string $resource = AssetLoanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('ver_calendario')
                ->label('Ver Calendario de Disponibilidad')
                ->icon('heroicon-o-calendar')
                ->color('success')
                ->url(fn () => \App\Filament\Itam\Pages\LoanCalendarPage::getUrl(panel: 'itam')),

            CreateAction::make()->label('Crear Préstamo')
                ->label('Crear Préstamo / Reserva'),
        ];
    }
}
