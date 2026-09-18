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

        $isTechnician = $user?->allRoles()->whereIn('roles.name', ['Tecnico de Soporte', 'Técnico de Soporte', 'Tecnico de soporte', 'Admin-Soporte', 'admin-soporte', 'Administrador de Helpdesk'])->exists() ?? false;
        $isAdmin = $user?->allRoles()->whereIn('roles.name', ['Administrador Central', 'Administrador de Helpdesk', 'Administrador de TI'])->exists() ?? false;

        // Campos reportados por el usuario (solo lectura al editar para el técnico)
        $disableUserFields = $isEditMode && $isTechnician && ! $isAdmin;

        // Campos técnicos y de resolución (solo lectura si el ticket está cerrado)
        $isClosed = $isEditMode && in_array(strtolower((string) $schema->getRecord()?->status), ['cerrado']);
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
                                'equipos/hardware' => 'Equipos / Hardware (Computadora, Impresora, etc.)',
                                'sistemas/programas' => 'Sistemas / Programas (SIGM, Navegador, Office)',
                                'accesos/contraseñas' => 'Accesos / Contraseñas (Restablecer contraseña, cuentas)',
                                'red/internet' => 'Red / Internet (Sin red, desconexión)',
                                'otros' => 'Otros problemas',
                            ])
                            ->formatStateUsing(fn ($state) => strtolower((string) $state))
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $catMap = [
                                    'equipos/hardware' => 'hardware y computadores',
                                    'sistemas/programas' => 'sistemas municipales',
                                    'accesos/contraseñas' => 'sistemas municipales',
                                    'accesos/contrasenas' => 'sistemas municipales',
                                    'red/internet' => 'red y conectividad',
                                    'otros' => 'sistemas municipales',
                                ];
                                $target = $catMap[strtolower((string) $state)] ?? null;
                                if ($target) {
                                    $cat = \App\Models\TicketCategory::whereRaw('LOWER(name) = ?', [$target])->first();
                                    if ($cat) {
                                        $set('category_id', $cat->id);
                                    }
                                }
                            })
                            ->disabled($disableUserFields),
                        Select::make('requester_id')
                            ->relationship('requester', 'name')
                            ->label('Usuario Solicitante (SIGM)')
                            ->default(auth()->id())
                            ->disabled($disableUserFields)
                            ->dehydrated()
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('office_id', User::find($state)?->office_id)),
                        TextInput::make('requester_name')
                            ->label('Nombre del Solicitante / Responsable')
                            ->maxLength(150)
                            ->disabled($disableUserFields)
                            ->placeholder('Nombre completo del solicitante o responsable'),
                        TextInput::make('contact_phone')
                            ->label('Teléfono / Anexo de Contacto')
                            ->maxLength(50)
                            ->disabled($disableUserFields)
                            ->placeholder('Ej: Anexo 104 / 987654321'),
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
                                'individual' => 'Solo me pasa a mí (Trabajo parcial detenido)',
                                'grupal' => 'Afecta a mi área / oficina (Varios usuarios afectados)',
                                'critico' => 'Todo el departamento / oficina está parado (Operación crítica detenida)',
                            ])
                            ->formatStateUsing(fn ($state) => strtolower((string) $state))
                            ->default('individual')
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
                            ->getOptionLabelFromRecordUsing(fn ($record) => mb_strtoupper((string) $record->name, 'UTF-8'))
                            ->label('Categorización Real / Tipo de Falla Técnica')
                            ->required()
                            ->preload()
                            ->disabled($disableTechnicalFields),
                        Select::make('assigned_to')
                            ->relationship('assignee', 'username')
                            ->getOptionLabelFromRecordUsing(fn ($record) => mb_strtoupper((string) $record->username, 'UTF-8') . ($record->name ? " - " . mb_strtoupper((string) $record->name, 'UTF-8') : ''))
                            ->label('Técnico Asignado')
                            ->placeholder('Sin asignar')
                            ->searchable()
                            ->preload()
                            ->disabled($disableTechnicalFields),
                        Select::make('priority')
                            ->label('Prioridad Interna')
                            ->options([
                                'baja' => 'Baja',
                                'media' => 'Media',
                                'alta' => 'Alta',
                            ])
                            ->formatStateUsing(fn ($state) => strtolower((string) $state))
                            ->default('baja')
                            ->required()
                            ->disabled($disableTechnicalFields),
                        Select::make('status')
                            ->label('Estado del Ticket')
                            ->options([
                                'abierto' => 'Abierto',
                                'en proceso' => 'En Proceso',
                                'internado' => 'Internado',
                                'en espera' => 'En Espera',
                                'esperando terceros' => 'Esperando Terceros',
                                'resuelto' => 'Resuelto',
                                'cerrado' => 'Cerrado',
                            ])
                            ->formatStateUsing(fn ($state) => strtolower((string) $state))
                            ->default('abierto')
                            ->required()
                            ->live()
                            ->disabled($disableTechnicalFields),
                        Select::make('affected_asset_id')
                            ->label(fn ($get) => in_array(strtolower((string) $get('status')), ['internado'])
                                ? 'Activo a Internar al Taller'
                                : 'Activo con Falla (Entrada / Desvincular)')
                            ->relationship('affectedAsset', 'computer_code', fn ($query, $get, $record) => 
                                $query->with(['model.brand', 'category'])
                                    ->where(function ($q1) use ($get, $record) {
                                        $requesterId = $get('requester_id');
                                        $officeId = $get('office_id');

                                        $q1->where(function ($sub) use ($requesterId, $officeId) {
                                            if ($requesterId) {
                                                $sub->whereHas('assignments', fn ($q) => 
                                                    $q->where('user_id', $requesterId)->whereNull('returned_at')
                                                )->orWhereHas('parent.assignments', fn ($q) => 
                                                    $q->where('user_id', $requesterId)->whereNull('returned_at')
                                                );
                                            } elseif ($officeId) {
                                                $sub->whereHas('assignments', fn ($q) => 
                                                    $q->where('office_id', $officeId)->whereNull('returned_at')
                                                )->orWhereHas('parent.assignments', fn ($q) => 
                                                    $q->where('office_id', $officeId)->whereNull('returned_at')
                                                );
                                            }
                                        });

                                        if ($record && $record->affected_asset_id) {
                                            $q1->orWhere('id', $record->affected_asset_id);
                                        }
                                    })
                            )
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->select_option_label)
                            ->getSearchResultsUsing(function (string $search, $get, $record) {
                                return \App\Models\Asset::query()
                                    ->where(function ($q1) use ($get, $record) {
                                        $requesterId = $get('requester_id');
                                        $officeId = $get('office_id');

                                        $q1->where(function ($sub) use ($requesterId, $officeId) {
                                            if ($requesterId) {
                                                $sub->whereHas('assignments', fn ($q) => 
                                                    $q->where('user_id', $requesterId)->whereNull('returned_at')
                                                )->orWhereHas('parent.assignments', fn ($q) => 
                                                    $q->where('user_id', $requesterId)->whereNull('returned_at')
                                                );
                                            } elseif ($officeId) {
                                                $sub->whereHas('assignments', fn ($q) => 
                                                    $q->where('office_id', $officeId)->whereNull('returned_at')
                                                )->orWhereHas('parent.assignments', fn ($q) => 
                                                    $q->where('office_id', $officeId)->whereNull('returned_at')
                                                );
                                            }
                                        });

                                        if ($record && $record->affected_asset_id) {
                                            $q1->orWhere('id', $record->affected_asset_id);
                                        }
                                    })
                                    ->searchTerms($search)
                                    ->with(['model.brand', 'category'])
                                    ->limit(50)
                                    ->get()
                                    ->mapWithKeys(fn ($asset) => [$asset->id => $asset->select_option_label])
                                    ->toArray();
                            })
                            ->placeholder('Ningún activo asociado')
                            ->searchable()
                            ->preload()
                            ->disabled($disableTechnicalFields),
                        Select::make('replacement_asset_id')
                            ->label(fn ($get) => in_array(strtolower((string) $get('status')), ['internado'])
                                ? 'Activo de Préstamo Temporal (mientras está internado)'
                                : 'Activo de Repuesto (Salida / Asignar)')
                            ->relationship('replacementAsset', 'computer_code', fn ($query, $record) => 
                                $query->with(['model.brand', 'category'])
                                    ->where(function ($q) use ($record) {
                                        $q->where('status', 'Disponible');

                                        if ($record && $record->replacement_asset_id) {
                                            $q->orWhere('id', $record->replacement_asset_id);
                                        }
                                    })
                            )
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->select_option_label)
                            ->getSearchResultsUsing(function (string $search, $record) {
                                return \App\Models\Asset::query()
                                    ->where(function ($q) use ($record) {
                                        $q->where('status', 'Disponible');

                                        if ($record && $record->replacement_asset_id) {
                                            $q->orWhere('id', $record->replacement_asset_id);
                                        }
                                    })
                                    ->searchTerms($search)
                                    ->with(['model.brand', 'category'])
                                    ->limit(50)
                                    ->get()
                                    ->mapWithKeys(fn ($asset) => [$asset->id => $asset->select_option_label])
                                    ->toArray();
                            })
                            ->placeholder('Sin reemplazo / No aplica')
                            ->searchable()
                            ->preload()
                            ->disabled($disableTechnicalFields),
                        Select::make('root_cause')
                            ->label('Causa Raíz / Código de Cierre')
                            ->options([
                                'usuario (capacitacion)' => 'Falla de usuario (Capacitación / Uso incorrecto)',
                                'desgaste / hardware' => 'Desgaste de equipo (Falla física / Hardware)',
                                'bug / software' => 'Bug de software (Error de programación)',
                                'proveedor / externo' => 'Falla de proveedor (Internet, fluido eléctrico, etc.)',
                                'otro' => 'Otro (Especificar en diagnóstico)',
                            ])
                            ->formatStateUsing(fn ($state) => strtolower((string) $state))
                            ->required(fn (callable $get) => in_array(strtolower((string) $get('status')), ['resuelto', 'cerrado']))
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
                            ->required(fn (callable $get) => in_array(strtolower((string) $get('status')), ['resuelto', 'cerrado'])),
                        Textarea::make('solution_applied')
                            ->label('Solución Aplicada')
                            ->maxLength(1000)
                            ->columnSpanFull()
                            ->disabled($disableTechnicalFields)
                            ->required(fn (callable $get) => in_array(strtolower((string) $get('status')), ['resuelto', 'cerrado']))
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
