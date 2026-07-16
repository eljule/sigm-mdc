<?php

declare(strict_types=1);

namespace App\Filament\Itam\Resources\Assets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AssetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('computer_code')
                    ->label('Cód. Informático')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('asset_code')
                    ->label('Cód. Patrimonial')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Categoría')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('brand')
                    ->label('Marca')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('model')
                    ->label('Modelo')
                    ->searchable(),
                TextColumn::make('serial_number')
                    ->label('S/N')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Disponible' => 'success',
                        'Asignado' => 'info',
                        'Mantenimiento' => 'warning',
                        'Baja' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('ip_address')
                    ->label('Dirección IP')
                    ->searchable(),
                TextColumn::make('warranty_expiration')
                    ->label('Fin Garantía')
                    ->date()
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
