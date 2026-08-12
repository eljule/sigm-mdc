<?php

declare(strict_types=1);

namespace App\Filament\Helpdesk\Resources\Tickets\Schemas;

use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TicketForm
{
    public static function configure(Schema $schema): Schema
    {
        $user = auth()->user();
        $isEditMode = $schema->getRecord() !== null;

        $isTechnician = $user?->hasRole('Técnico de Soporte') || false;
        $isAdmin = $user?->hasRole('Administrador Central') || false;

        // Campos reportados por el usuario (solo lectura al editar para el técnico)
        $disableUserFields = $isEditMode && $isTechnician && ! $isAdmin;

        // Campos técnicos y de resolución (solo lectura si el ticket está cerrado)
        $isClosed = $isEditMode && $schema->getRecord()?->status === 'Cerrado';
        $disableTechnicalFields = $isClosed && ! $isAdmin;

        return $schema
            ->components([
                Section::make('1. Información de la Incidencia (Reportado por el Usuario)')
                    ->description('Detalle inicial y síntoma del problema provisto por el usuario solicitante.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('ticket_code')
                            ->label('Código de Incidencia')
                            ->disabled()
                            ->placeholder('Autogenerado (ej: INC-2026-0001)'),
                        Select::make('user_category')
                            ->label('Tipo de Incidencia / Área')
                            ->options([
                                'Equipos/Hardware' => 'Equipos / Hardware (Computadora, Impresora, etc.)',
                                'Sistemas/Programas' => 'Sistemas / Programas (SIGM, Navegador, Office)',
                                'Accesos/Contraseñas' => 'Accesos / Contraseñas (Restablecer contraseña, cuentas)',
                                'Red/Internet' => 'Red / Internet (Sin red, desconexión)',
                                'Otros' => 'Otros problemas',
                            ])
                            ->required()
                            ->disabled($disableUserFields),
                        Select::make('requester_id')
                            ->relationship('requester', 'name')
                            ->label('Solicitante')
                            ->default(auth()->id())
                            ->disabled($disableUserFields)
                            ->dehydrated()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('office_id', User::find($state)?->office_id)),
                        Select::make('office_id')
                            ->relationship('office', 'name')
                            ->label('Oficina / Dependencia')
                            ->default(auth()->user()?->office_id)
                            ->disabled($disableUserFields)
                            ->dehydrated()
                            ->required(),
                        TextInput::make('title')
                            ->label('Asunto / Falla corta')
                            ->required()
                            ->maxLength(150)
                            ->placeholder('Ej. No puedo imprimir mi documento')
                            ->disabled($disableUserFields)
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label('¿Qué intentaba hacer, qué pasó y qué error salió? (Descripción del Síntoma)')
                            ->required()
                            ->maxLength(2000)
                            ->columnSpanFull()
                            ->placeholder('Ej. Estaba intentando imprimir mi informe mensual en la impresora de administración, al enviar el documento la impresora empezó a parpadear una luz roja y se detuvo. Sale mensaje de error de atasco en la pantalla del computador...')
                            ->disabled($disableUserFields),
                        Select::make('impact')
                            ->label('Nivel de Afectación (Impacto)')
                            ->options([
                                'Individual' => 'Solo me pasa a mí (Trabajo parcial detenido)',
                                'Grupal' => 'Afecta a mi área / oficina (Varios usuarios afectados)',
                                'Critico' => 'Todo el departamento / oficina está parado (Operación crítica detenida)',
                            ])
                            ->required()
                            ->disabled($disableUserFields),
                        FileUpload::make('attachments')
                            ->label('Adjuntos (Capturas de pantalla, fotos o videos del error)')
                            ->multiple()
                            ->disk('public')
                            ->directory('ticket-attachments')
                            ->downloadable()
                            ->openable()
                            ->columnSpanFull()
                            ->disabled($disableUserFields),
                    ]),

                Section::make('2. Clasificación y Resolución Técnica (Uso Exclusivo de Soporte)')
                    ->description('Diagnóstico detallado de la causa raíz, clasificación técnica y solución aplicada.')
                    ->columns(2)
                    ->schema([
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->label('Categorización Real / Tipo de Falla Técnica')
                            ->required()
                            ->preload()
                            ->disabled($disableTechnicalFields),
                        Select::make('assigned_to')
                            ->relationship('assignee', 'name')
                            ->label('Técnico Asignado')
                            ->placeholder('Sin asignar')
                            ->searchable()
                            ->preload()
                            ->disabled($disableTechnicalFields),
                        Select::make('priority')
                            ->label('Prioridad Interna')
                            ->options([
                                'Baja' => 'Baja',
                                'Media' => 'Media',
                                'Alta' => 'Alta',
                            ])
                            ->default('Baja')
                            ->required()
                            ->disabled($disableTechnicalFields),
                        Select::make('status')
                            ->label('Estado del Ticket')
                            ->options([
                                'Abierto' => 'Abierto',
                                'En Proceso' => 'En Proceso',
                                'Internado' => 'Internado',
                                'En Espera' => 'En Espera',
                                'Esperando Terceros' => 'Esperando Terceros',
                                'Resuelto' => 'Resuelto',
                                'Cerrado' => 'Cerrado',
                            ])
                            ->default('Abierto')
                            ->required()
                            ->disabled($disableTechnicalFields),
                        Select::make('affected_asset_id')
                            ->label(fn ($get) => $get('status') === 'Internado'
                                ? 'Activo a Internar al Taller'
                                : 'Activo con Falla (Entrada / Desvincular)')
                            ->relationship('affectedAsset', 'computer_code', fn ($query, $get, $record) => 
                                $query->where(function ($q1) use ($get, $record) {
                                    $q1->whereHas('assignments', fn ($q) => 
                                        $q->where('user_id', $get('requester_id'))->whereNull('returned_at')
                                    )->orWhereHas('parent.assignments', fn ($q) => 
                                        $q->where('user_id', $get('requester_id'))->whereNull('returned_at')
                                    );

                                    if ($record && $record->affected_asset_id) {
                                        $q1->orWhere('id', $record->affected_asset_id);
                                    }
                                })
                            )
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->computer_code} - {$record->category->name} (S/N: {$record->serial_number})")
                            ->placeholder('Ningún activo asociado')
                            ->searchable()
                            ->preload()
                            ->disabled($disableTechnicalFields),
                        Select::make('replacement_asset_id')
                            ->label(fn ($get) => $get('status') === 'Internado'
                                ? 'Activo de Préstamo Temporal (mientras está internado)'
                                : 'Activo de Repuesto (Salida / Asignar)')
                            ->relationship('replacementAsset', 'computer_code', fn ($query, $record) => 
                                $query->where(function ($q) use ($record) {
                                    $q->where('status', 'Disponible');

                                    if ($record && $record->replacement_asset_id) {
                                        $q->orWhere('id', $record->replacement_asset_id);
                                    }
                                })
                            )
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->computer_code} - {$record->category->name} (S/N: {$record->serial_number})")
                            ->placeholder('Sin reemplazo / No aplica')
                            ->searchable()
                            ->preload()
                            ->disabled($disableTechnicalFields),
                        Select::make('root_cause')
                            ->label('Causa Raíz / Código de Cierre')
                            ->options([
                                'Usuario (Capacitacion)' => 'Falla de usuario (Capacitación / Uso incorrecto)',
                                'Desgaste / Hardware' => 'Desgaste de equipo (Falla física / Hardware)',
                                'Bug / Software' => 'Bug de software (Error de programación)',
                                'Proveedor / Externo' => 'Falla de proveedor (Internet, fluido eléctrico, etc.)',
                                'Otro' => 'Otro (Especificar en diagnóstico)',
                            ])
                            ->required(fn (callable $get) => in_array($get('status'), ['Resuelto', 'Cerrado']))
                            ->disabled($disableTechnicalFields),
                        Toggle::make('save_to_knowledge_base')
                            ->label('¿Guardar como artículo en la Base de Conocimiento?')
                            ->helperText('Activa esta opción si la solución encontrada sirve como referencia para futuros incidentes similares.')
                            ->default(false)
                            ->disabled($disableTechnicalFields),
                        Repeater::make('ticketConsumables')
                            ->relationship('ticketConsumables')
                            ->label('Materiales / Consumibles Utilizados')
                            ->schema([
                                Select::make('consumable_id')
                                    ->label('Material / Insumo')
                                    ->relationship('consumable', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                TextInput::make('quantity')
                                    ->label('Cantidad Usada')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->required(),
                            ])
                            ->columns(2)
                            ->columnSpanFull()
                            ->disabled($disableTechnicalFields),
                        Textarea::make('diagnosis')
                            ->label('Diagnóstico técnico (Causa Raíz encontrada)')
                            ->maxLength(1000)
                            ->columnSpanFull()
                            ->placeholder('Describa qué causó realmente el problema técnico...')
                            ->disabled($disableTechnicalFields)
                            ->required(fn (callable $get) => in_array($get('status'), ['Resuelto', 'Cerrado'])),
                        Textarea::make('solution_applied')
                            ->label('Solución Aplicada')
                            ->maxLength(1000)
                            ->columnSpanFull()
                            ->disabled($disableTechnicalFields)
                            ->required(fn (callable $get) => in_array($get('status'), ['Resuelto', 'Cerrado']))
                            ->placeholder('Describa detalladamente los pasos realizados para resolver la falla...'),
                    ]),

                Section::make('3. Métricas y SLA')
                    ->columns(3)
                    ->collapsed()
                    ->schema([
                        DateTimePicker::make('sla_expires_at')
                            ->label('Expiración SLA')
                            ->disabled(),
                        DateTimePicker::make('resolved_at')
                            ->label('Resuelto el')
                            ->disabled(),
                        DateTimePicker::make('closed_at')
                            ->label('Cerrado el')
                            ->disabled(),
                    ]),
            ]);
    }
}
