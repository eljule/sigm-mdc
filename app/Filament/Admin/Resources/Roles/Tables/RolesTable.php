<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Roles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subsystem.name')
                    ->label('Subsistema')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Rol')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('guard_name')
                    ->label('Guard')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('subsystem_id')
                    ->label('Subsistema')
                    ->relationship('subsystem', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),

                SelectFilter::make('guard_name')
                    ->label('Guard')
                    ->options([
                        'web' => 'web',
                        'api' => 'api',
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
