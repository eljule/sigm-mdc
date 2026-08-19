<?php

declare(strict_types=1);

namespace App\Filament\Resources\Permissions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PermissionsTable
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
                    ->label('Permiso')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('guard_name')
                    ->label('Guard')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Filter::make('search_permission')
                    ->label('Nombre de Permiso')
                    ->form([
                        TextInput::make('name_query')
                            ->label('Permiso contiene la palabra')
                            ->placeholder('Ej: ticket, activo, usuario...'),
                    ])
                    ->query(function ($query, array $data) {
                        if (! empty($data['name_query'])) {
                            $query->where('name', 'ILIKE', '%' . trim($data['name_query']) . '%');
                        }
                    }),

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
