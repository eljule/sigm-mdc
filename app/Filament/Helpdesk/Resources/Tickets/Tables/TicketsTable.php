<?php

declare(strict_types=1);

namespace App\Filament\Helpdesk\Resources\Tickets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TicketsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ticket_code')
                    ->label('Código')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Asunto')
                    ->searchable()
                    ->limit(30),
                TextColumn::make('category.name')
                    ->label('Categoría')
                    ->sortable(),
                TextColumn::make('priority')
                    ->label('Prioridad')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Alta' => 'danger',
                        'Media' => 'warning',
                        'Baja' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Abierto' => 'danger',
                        'En Proceso' => 'warning',
                        'Esperando Terceros' => 'gray',
                        'Resuelto' => 'info',
                        'Cerrado' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('requester.name')
                    ->label('Solicitante')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('office.acronym')
                    ->label('Oficina')
                    ->sortable(),
                TextColumn::make('assignee.name')
                    ->label('Técnico')
                    ->sortable()
                    ->placeholder('Sin asignar'),
                TextColumn::make('sla_expires_at')
                    ->label('Límite SLA')
                    ->dateTime()
                    ->sortable()
                    ->color(fn ($record): ?string => ($record->sla_expires_at && $record->sla_expires_at->isPast() && ! in_array($record->status, ['Resuelto', 'Cerrado'])) ? 'danger' : null)
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
