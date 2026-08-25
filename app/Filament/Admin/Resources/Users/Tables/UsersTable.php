<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('username')
                    ->label('Usuario')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nombre Completo')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Correo')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('office.name')
                    ->label('Oficina')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('laborCondition.name')
                    ->label('Condición')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active')
                    ->label('Aprobado / Activo')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                 SelectFilter::make('office_id')
                    ->label('Oficina')
                    ->relationship('office', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),

                 SelectFilter::make('labor_condition_id')
                    ->label('Condición')
                    ->relationship('laborCondition', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),

                SelectFilter::make('is_active')
                    ->label('Estado de Aprobación')
                    ->options([
                        '1' => 'Aprobados (Activos)',
                        '0' => 'Pendientes de Aprobación (ODT)',
                    ]),

            ])
            ->recordActions([
                Action::make('aprobar_odt')
                    ->label('Aprobar Cuenta (ODT)')
                    ->button()
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->modalHeading('¿Aprobar solicitud de registro?')
                    ->modalDescription('El usuario quedará activado y podrá acceder a los módulos autorizados por la Municipalidad.')
                    ->visible(fn ($record): bool => ! (bool) $record->is_active)
                    ->action(function ($record) {
                        $record->update(['is_active' => true]);

                        Notification::make()
                            ->title('Usuario Aprobado')
                            ->body("La cuenta de {$record->name} ({$record->username}) ha sido aprobada y activada por ODT.")
                            ->success()
                            ->send();
                    }),

                Action::make('desactivar_odt')
                    ->label('Desactivar')
                    ->button()
                    ->outlined()
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->requiresConfirmation()
                    ->modalHeading('¿Desactivar/Rechazar esta cuenta?')
                    ->visible(fn ($record): bool => (bool) $record->is_active)
                    ->action(function ($record) {
                        $record->update(['is_active' => false]);

                        Notification::make()
                            ->title('Usuario Desactivado')
                            ->body("La cuenta de {$record->name} ha sido desactivada.")
                            ->warning()
                            ->send();
                    }),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
