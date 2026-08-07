<?php

declare(strict_types=1);

namespace App\Filament\Itam\Resources\Assets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
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
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('asset_code')
                    ->label('Cód. Patrimonial')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('category.name')
                    ->label('Categoría')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('model.brand.name')
                    ->label('Marca')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('model.name')
                    ->label('Modelo')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('serial_number')
                    ->label('S/N')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Disponible' => 'success',
                        'Asignado' => 'info',
                        'Mantenimiento' => 'warning',
                        'En Evaluación' => 'warning',
                        'Baja' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'Disponible' => 'heroicon-o-check-circle',
                        'Asignado' => 'heroicon-o-user',
                        'Mantenimiento' => 'heroicon-o-wrench-screwdriver',
                        'En Evaluación' => 'heroicon-o-magnifying-glass',
                        'Baja' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('warranty_expiration')
                    ->label('Fin Garantía')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('asset_category_id')
                    ->label('Categoría')
                    ->relationship('category', 'name')
                    ->preload()
                    ->multiple(),
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'Disponible' => 'Disponible',
                        'Asignado' => 'Asignado',
                        'Mantenimiento' => 'Mantenimiento',
                        'En Evaluación' => 'En Evaluación',
                        'Baja' => 'Baja',
                    ]),
                SelectFilter::make('brand')
                    ->label('Marca')
                    ->relationship('model.brand', 'name')
                    ->preload(),
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
