<?php

declare(strict_types=1);

namespace App\Filament\Itam\Resources\AssetAssignments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class AssetAssignmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('asset.computer_code')
                    ->label('Cód. Informático')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('asset.asset_code')
                    ->label('Cód. Patrimonial')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('asset_brand')
                    ->label('Marca')
                    ->state(fn ($record) => $record->asset?->model?->brand?->name)
                    ->placeholder('-')
                    ->toggleable(),
                TextColumn::make('asset_model')
                    ->label('Modelo')
                    ->state(fn ($record) => $record->asset?->model?->name)
                    ->placeholder('-')
                    ->toggleable(),
                TextColumn::make('user.name')
                    ->label('Asignado a')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('office.name')
                    ->label('Oficina')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('assigned_at')
                    ->label('Fecha Entrega')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('returned_at')
                    ->label('Fecha Devolución')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Activo actualmente')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('office_id')
                    ->label('Oficina')
                    ->relationship('office', 'name')
                    ->preload(),
                SelectFilter::make('user_id')
                    ->label('Empleado')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                TernaryFilter::make('is_active')
                    ->label('Estado de Asignación')
                    ->placeholder('Todas')
                    ->trueLabel('Activa (No devuelta)')
                    ->falseLabel('Devuelta')
                    ->queries(
                        true: fn ($query) => $query->whereNull('returned_at'),
                        false: fn ($query) => $query->whereNotNull('returned_at'),
                    )
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
