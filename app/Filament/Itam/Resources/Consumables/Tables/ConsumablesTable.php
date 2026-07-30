<?php

namespace App\Filament\Itam\Resources\Consumables\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ConsumablesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre del Insumo / Consumible')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('stock')
                    ->label('Stock Actual')
                    ->sortable()
                    ->badge()
                    ->color(fn ($record) => $record->stock <= $record->min_stock ? 'danger' : 'success'),
                TextColumn::make('unit')
                    ->label('Unidad de Medida'),
                TextColumn::make('min_stock')
                    ->label('Stock Mínimo Alerta')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Registrado el')
                    ->dateTime('d/m/Y H:i')
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
