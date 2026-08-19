<?php

namespace App\Filament\Helpdesk\Resources\Tickets\Pages;

use App\Filament\Helpdesk\Resources\Tickets\TicketResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('monitoreo_tecnicos')
                ->label('Monitoreo de Técnicos')
                ->icon('heroicon-o-user-group')
                ->color('info')
                ->url(fn () => \App\Filament\Helpdesk\Pages\TechnicianMonitoringPage::getUrl(panel: 'helpdesk')),

            CreateAction::make(),
        ];
    }
}
