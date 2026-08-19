<?php

namespace App\Filament\Itam\Resources\AssetLoans\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AssetLoansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('N° Reserva')
                    ->formatStateUsing(fn ($state, $record) => $record->loan_number)
                    ->sortable()
                    ->badge()
                    ->color('primary')
                    ->searchable(query: function ($query, string $search) {
                        $clean = preg_replace('/[^0-9]/', '', $search);
                        if ($clean !== '') {
                            $query->where('id', (int) $clean);
                        } else {
                            $query->where('borrower_name', 'LIKE', "%{$search}%");
                        }
                    }),

                TextColumn::make('asset.computer_code')
                    ->label('Equipo / Activo')
                    ->formatStateUsing(function ($state, $record) {
                        $code = $record->asset?->computer_code ?? $record->asset?->asset_code ?? 'S/N';
                        $cat = $record->asset?->category?->name ?? 'Equipo';
                        $model = $record->asset?->model?->name ?? '';
                        return "{$code} ({$cat} {$model})";
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('office.name')
                    ->label('Oficina Solicitante')
                    ->default('No especificada')
                    ->searchable(),

                TextColumn::make('borrower_name')
                    ->label('Solicitante / Responsable')
                    ->searchable(),

                TextColumn::make('start_time')
                    ->label('Inicio')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('end_time')
                    ->label('Fin Estimado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pending'   => 'warning',
                        'active'    => 'success',
                        'returned'  => 'gray',
                        'overdue'   => 'danger',
                        'cancelled' => 'gray',
                        default     => 'info',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pending'   => 'Pendiente',
                        'active'    => 'En Préstamo',
                        'returned'  => 'Devuelto',
                        'overdue'   => 'Vencido',
                        'cancelled' => 'Cancelado',
                        default     => ucfirst((string) $state),
                    })
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->actions([
                Action::make('marcar_devolucion')
                    ->label('Devolver Equipo')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => in_array($record->status, ['active', 'pending', 'overdue']))
                    ->modalHeading('Registrar Devolución de Equipo')
                    ->modalDescription('Confirmar la recepción del equipo en el área de TI.')
                    ->modalSubmitActionLabel('Confirmar Devolución')
                    ->form([
                        DateTimePicker::make('returned_at')
                            ->label('Fecha y Hora Real de Devolución')
                            ->default(now())
                            ->required(),
                        Textarea::make('notes')
                            ->label('Observaciones de Devolución (Estado del equipo)')
                            ->placeholder('Ej: Devuelto en perfecto estado con todos sus accesorios.'),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status'      => 'returned',
                            'returned_at' => $data['returned_at'] ?? now(),
                            'notes'       => trim(($record->notes ? $record->notes . "\n" : '') . "Devuelto: " . ($data['notes'] ?? 'Sin observaciones')),
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->title('Equipo Devuelto')
                            ->body("El préstamo {$record->loan_number} se marcó como Devuelto.")
                            ->success()
                            ->send();
                    }),

                Action::make('ver_acta')
                    ->label('Ficha Préstamo')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->url(fn ($record) => route('fichas.prestamo_equipo', ['id' => $record->id]))
                    ->openUrlInNewTab(),

                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
