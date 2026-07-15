<?php

declare(strict_types=1);

namespace App\Filament\Itam\Resources\Software\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SoftwareTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Software')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('version')
                    ->label('Versión')
                    ->searchable(),
                TextColumn::make('license_type')
                    ->label('Licencia')
                    ->sortable(),
                TextColumn::make('max_activations')
                    ->label('Máx. Activaciones')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('expiration_date')
                    ->label('Vencimiento')
                    ->date()
                    ->sortable()
                    ->placeholder('N/A'),
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
