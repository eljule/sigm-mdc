<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Offices\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OfficesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Código')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Oficina')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('acronym')
                    ->label('Siglas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('parent.name')
                    ->label('Depende de')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Nivel Principal'),
                IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('parent_id')
                    ->label('Depende de')
                    ->relationship('parent', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),
                SelectFilter::make('is_active')
                    ->label('Activo')
                    ->options([
                        '1' => 'Activo',
                        '0' => 'Inactivo',
                    ]),
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
