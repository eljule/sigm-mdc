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
                    ->formatStateUsing(fn (?string $state): string => match (strtolower((string) $state)) {
                        'disponible' => 'DISPONIBLE',
                        'asignado' => 'ASIGNADO',
                        'mantenimiento' => 'MANTENIMIENTO',
                        'en evaluación', 'en evaluacion' => 'EN EVALUACIÓN',
                        'baja' => 'BAJA',
                        default => strtoupper((string) $state),
                    })
                    ->color(fn (?string $state): string => match (strtolower((string) $state)) {
                        'disponible' => 'success',
                        'asignado' => 'info',
                        'mantenimiento' => 'warning',
                        'en evaluación', 'en evaluacion' => 'warning',
                        'baja' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn (?string $state): string => match (strtolower((string) $state)) {
                        'disponible' => 'heroicon-o-check-circle',
                        'asignado' => 'heroicon-o-user',
                        'mantenimiento' => 'heroicon-o-wrench-screwdriver',
                        'en evaluación', 'en evaluacion' => 'heroicon-o-magnifying-glass',
                        'baja' => 'heroicon-o-x-circle',
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
                        'disponible' => 'DISPONIBLE',
                        'asignado' => 'ASIGNADO',
                        'mantenimiento' => 'MANTENIMIENTO',
                        'en evaluación' => 'EN EVALUACIÓN',
                        'baja' => 'BAJA',
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
