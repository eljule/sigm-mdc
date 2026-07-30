<?php

declare(strict_types=1);

namespace App\Filament\Helpdesk\Resources\Tickets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class TicketsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->contentGrid([
                'sm' => 1,
                'md' => 2,
                'lg' => 3,
            ])
            ->columns([
                Stack::make([
                    Split::make([
                        TextColumn::make('ticket_code')
                            ->fontFamily('mono')
                            ->weight('bold')
                            ->searchable()
                            ->sortable(),
                        TextColumn::make('priority')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'Alta' => 'danger',
                                'Media' => 'warning',
                                'Baja' => 'success',
                                default => 'gray',
                            })
                            ->alignEnd(),
                    ]),
                    TextColumn::make('title')
                        ->weight('bold')
                        ->size('lg')
                        ->searchable()
                        ->limit(50),
                    TextColumn::make('description')
                        ->color('gray')
                        ->limit(120)
                        ->searchable(),
                    Split::make([
                        TextColumn::make('office.acronym')
                            ->label('Oficina')
                            ->icon('heroicon-o-building-office')
                            ->color('gray')
                            ->size('sm'),
                        TextColumn::make('requester.name')
                            ->label('Solicitante')
                            ->icon('heroicon-o-user')
                            ->color('gray')
                            ->size('sm')
                            ->alignEnd(),
                    ]),
                    Split::make([
                        TextColumn::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'Abierto' => 'danger',
                                'En Proceso' => 'warning',
                                'Esperando Terceros' => 'gray',
                                'Resuelto' => 'info',
                                'Cerrado' => 'success',
                                default => 'gray',
                            }),
                        TextColumn::make('assignee.name')
                            ->placeholder('Sin asignar')
                            ->icon('heroicon-o-wrench-screwdriver')
                            ->color('gray')
                            ->size('sm')
                            ->alignEnd(),
                    ]),
                    TextColumn::make('sla_expires_at')
                        ->label('Límite SLA')
                        ->dateTime()
                        ->size('xs')
                        ->color(fn ($record): ?string => ($record->sla_expires_at && $record->sla_expires_at->isPast() && ! in_array($record->status, ['Resuelto', 'Cerrado'])) ? 'danger' : 'gray')
                        ->placeholder('SLA: N/A')
                        ->formatStateUsing(fn ($state) => $state ? 'Vence: ' . $state->format('d/m/Y H:i') : null),
                ])->space(3),
            ])
            ->filters([
                Filter::make('no_asignados')
                    ->label('Sin Asignar')
                    ->query(fn (Builder $query) => $query->whereNull('assigned_to')),
                Filter::make('asignados_a_mi')
                    ->label('Asignados a mí')
                    ->query(fn (Builder $query) => $query->where('assigned_to', auth()->id())),
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'Abierto' => 'Abierto',
                        'En Proceso' => 'En Proceso',
                        'Esperando Terceros' => 'Esperando Terceros',
                        'Resuelto' => 'Resuelto',
                        'Cerrado' => 'Cerrado',
                    ]),
            ])
            ->recordActions([
                Action::make('atender')
                    ->label('Atender')
                    ->button()
                    ->color('warning')
                    ->icon('heroicon-m-play')
                    ->requiresConfirmation()
                    ->modalHeading('¿Atender este ticket?')
                    ->modalDescription('Te asignarás este ticket y su estado cambiará a "En Proceso". Ningún otro técnico podrá atenderlo.')
                    ->visible(fn ($record): bool => $record->status === 'Abierto' && empty($record->assigned_to))
                    ->action(function ($record) {
                        $record->update([
                            'assigned_to' => auth()->id(),
                            'status' => 'En Proceso',
                        ]);

                        Notification::make()
                            ->title('Ticket atendido con éxito')
                            ->body('Te has asignado el ticket ' . $record->ticket_code . '.')
                            ->success()
                            ->send();
                    }),
                EditAction::make()
                    ->button()
                    ->outlined()
                    ->color('gray')
                    ->label('Gestionar'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
