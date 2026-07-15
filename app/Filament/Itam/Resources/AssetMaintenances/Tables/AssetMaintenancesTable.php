<?php

declare(strict_types=1);

namespace App\Filament\Itam\Resources\AssetMaintenances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AssetMaintenancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('asset.asset_code')
                    ->label('Activo')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipo')
                    ->sortable(),
                TextColumn::make('scheduled_date')
                    ->label('F. Programada')
                    ->date()
                    ->sortable(),
                TextColumn::make('performed_date')
                    ->label('F. Realizado')
                    ->date()
                    ->sortable()
                    ->placeholder('Pendiente'),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->state(fn ($record): string => $record->performed_date
                        ? 'Realizado'
                        : ($record->scheduled_date->isPast() ? 'Vencido' : 'Programado')
                    )
                    ->color(fn (string $state): string => match ($state) {
                        'Realizado' => 'success',
                        'Vencido' => 'danger',
                        'Programado' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('cost')
                    ->label('Costo (S/.)')
                    ->money('PEN')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
