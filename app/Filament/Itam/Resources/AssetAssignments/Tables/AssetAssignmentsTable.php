<?php

declare(strict_types=1);

namespace App\Filament\Itam\Resources\AssetAssignments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
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
                    ->sortable(),
                TextColumn::make('asset.asset_code')
                    ->label('Cód. Patrimonial')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('asset.brand')
                    ->label('Marca')
                    ->placeholder('-'),
                TextColumn::make('asset.model')
                    ->label('Modelo')
                    ->placeholder('-'),
                TextColumn::make('user.name')
                    ->label('Asignado a')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('office.name')
                    ->label('Oficina')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('assigned_at')
                    ->label('Fecha Entrega')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('returned_at')
                    ->label('Fecha Devolución')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Activo actualmente'),
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
