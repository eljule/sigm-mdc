<?php

declare(strict_types=1);

namespace App\Filament\Helpdesk\Resources\Tickets\Schemas;

use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TicketForm
{
    public static function configure(Schema $schema): Schema
    {
        $isTechnician = auth()->user()?->hasRole('Técnico de Soporte') || auth()->user()?->hasRole('Administrador Central') || false;

        return $schema
            ->components([
                TextInput::make('ticket_code')
                    ->label('Código de Incidencia')
                    ->disabled()
                    ->placeholder('Autogenerado al guardar (ej: INC-2026-0001)'),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Categoría de Incidencia')
                    ->required()
                    ->preload()
                    ->disabled(! $isTechnician && $schema->getRecord() !== null),
                Select::make('requester_id')
                    ->relationship('requester', 'name')
                    ->label('Solicitante')
                    ->default(auth()->id())
                    ->disabled(! $isTechnician)
                    ->dehydrated()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('office_id', User::find($state)?->office_id)),
                Select::make('office_id')
                    ->relationship('office', 'name')
                    ->label('Oficina / Dependencia')
                    ->default(auth()->user()?->office_id)
                    ->disabled(! $isTechnician)
                    ->dehydrated()
                    ->required(),
                TextInput::make('title')
                    ->label('Asunto / Falla corta')
                    ->required()
                    ->maxLength(150)
                    ->placeholder('Ej. No puedo imprimir en red'),
                Textarea::make('description')
                    ->label('Descripción detallada del problema')
                    ->required()
                    ->maxLength(2000)
                    ->columnSpanFull()
                    ->placeholder('Por favor, describa detalladamente lo que ocurre...'),
                Select::make('priority')
                    ->label('Prioridad')
                    ->options([
                        'Baja' => 'Baja',
                        'Media' => 'Media',
                        'Alta' => 'Alta',
                    ])
                    ->default('Baja')
                    ->required()
                    ->disabled(! $isTechnician),
                Select::make('status')
                    ->label('Estado')
                    ->options([
                        'Abierto' => 'Abierto',
                        'En Proceso' => 'En Proceso',
                        'Esperando Terceros' => 'Esperando Terceros',
                        'Resuelto' => 'Resuelto',
                        'Cerrado' => 'Cerrado',
                    ])
                    ->default('Abierto')
                    ->required()
                    ->disabled(! $isTechnician),
                Select::make('assigned_to')
                    ->relationship('assignee', 'name')
                    ->label('Técnico Asignado')
                    ->placeholder('Sin asignar')
                    ->searchable()
                    ->preload()
                    ->disabled(! $isTechnician),
                Textarea::make('solution_applied')
                    ->label('Solución Aplicada')
                    ->maxLength(1000)
                    ->columnSpanFull()
                    ->disabled(! $isTechnician)
                    ->required(fn (callable $get) => $get('status') === 'Resuelto')
                    ->placeholder('Técnico: Describa la solución aplicada para resolver la incidencia...'),
                DateTimePicker::make('sla_expires_at')
                    ->label('Expiración SLA')
                    ->disabled(),
                DateTimePicker::make('resolved_at')
                    ->label('Resuelto el')
                    ->disabled(),
                DateTimePicker::make('closed_at')
                    ->label('Cerrado el')
                    ->disabled(),
            ]);
    }
}
