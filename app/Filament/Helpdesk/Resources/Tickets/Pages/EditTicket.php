<?php

namespace App\Filament\Helpdesk\Resources\Tickets\Pages;

use App\Filament\Helpdesk\Resources\Tickets\TicketResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTicket extends EditRecord
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('liberar_atencion')
                ->label('Liberar Atención')
                ->color('danger')
                ->icon('heroicon-m-arrow-uturn-left')
                ->requiresConfirmation()
                ->modalHeading('¿Liberar la atención de este ticket?')
                ->modalDescription('El ticket volverá al estado "Abierto" y quedará sin técnico asignado para que otro personal de soporte pueda atenderlo.')
                ->visible(function (): bool {
                    $record = $this->getRecord();
                    $statusLower = strtolower($record->status ?? '');
                    if (in_array($statusLower, ['resuelto', 'cerrado'])) {
                        return false;
                    }
                    $isAssignedOrInProgress = ! empty($record->assigned_to) || in_array($statusLower, ['en proceso', 'en espera', 'esperando terceros', 'internado']);
                    if (! $isAssignedOrInProgress) {
                        return false;
                    }
                    $user = auth()->user();
                    if (! $user) return false;
                    $isAdmin = $user->allRoles()->whereIn('roles.name', ['Administrador Central', 'Administrador de Helpdesk', 'Administrador de TI', 'Admin-Soporte', 'admin-soporte'])->exists();
                    return $isAdmin || ((int) $user->id === (int) $record->assigned_to);
                })
                ->action(function () {
                    $record = $this->getRecord();
                    $record->update([
                        'assigned_to' => null,
                        'status' => 'abierto',
                        'started_at' => null,
                    ]);

                    \Filament\Notifications\Notification::make()
                        ->title('Atención liberada')
                        ->body('El ticket ' . $record->ticket_code . ' ha vuelto a estar disponible para atención.')
                        ->warning()
                        ->send();

                    $this->redirect(TicketResource::getUrl('index'));
                }),
            DeleteAction::make(),
        ];
    }
}
