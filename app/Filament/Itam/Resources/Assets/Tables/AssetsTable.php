<?php

declare(strict_types=1);

namespace App\Filament\Itam\Resources\Assets\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Placeholder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use App\Models\AssetDecommission;
use App\Models\AssetAssignment;
use App\Models\Ticket;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AssetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('computer_code')
                    ->label('Cód. Informático')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('asset_code')
                    ->label('Cód. Patrimonial')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('category.name')
                    ->label('Categoría')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('model.brand.name')
                    ->label('Marca')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('model.name')
                    ->label('Modelo')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('serial_number')
                    ->label('S/N')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('color')
                    ->label('Color')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (?string $state): string => match (strtolower((string) $state)) {
                        'muy bueno' => 'success',
                        'bueno' => 'info',
                        'regular' => 'warning',
                        'malo' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => strtoupper((string) $state))
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->label('Situación')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match (strtolower((string) $state)) {
                        'disponible' => 'DISPONIBLE',
                        'asignado' => 'ASIGNADO',
                        'mantenimiento' => 'MANTENIMIENTO',
                        'en evaluación', 'en evaluacion' => 'EN EVALUACIÓN',
                        'baja' => 'BAJA',
                        default => strtoupper((string) $state),
                    })
                    ->color(fn (?string $state): string => match (strtolower((string) $state)) {
                        'disponible' => 'success',
                        'asignado' => 'info',
                        'mantenimiento' => 'warning',
                        'en evaluación', 'en evaluacion' => 'warning',
                        'baja' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn (?string $state): string => match (strtolower((string) $state)) {
                        'disponible' => 'heroicon-o-check-circle',
                        'asignado' => 'heroicon-o-user',
                        'mantenimiento' => 'heroicon-o-wrench-screwdriver',
                        'en evaluación', 'en evaluacion' => 'heroicon-o-magnifying-glass',
                        'baja' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('warranty_expiration')
                    ->label('Fin Garantía')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('asset_category_id')
                    ->label('Categoría')
                    ->relationship('category', 'name')
                    ->preload()
                    ->multiple(),
                SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        'muy bueno' => 'MUY BUENO',
                        'bueno' => 'BUENO',
                        'regular' => 'REGULAR',
                        'malo' => 'MALO',
                    ]),
                SelectFilter::make('status')
                    ->label('Situación')
                    ->options([
                        'disponible' => 'DISPONIBLE',
                        'asignado' => 'ASIGNADO',
                        'mantenimiento' => 'MANTENIMIENTO',
                        'en evaluación' => 'EN EVALUACIÓN',
                        'baja' => 'BAJA',
                    ]),
                SelectFilter::make('brand')
                    ->label('Marca')
                    ->relationship('model.brand', 'name')
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),

                Action::make('confirmar_baja')
                    ->label('Dar de Baja')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->modalHeading('Registro de Baja Definitiva')
                    ->modalSubmitActionLabel('Confirmar Baja')
                    ->modalWidth('2xl')
                    ->visible(fn ($record) => in_array(strtolower((string) ($record->status ?? '')), ['en evaluación', 'en evaluacion']) && (auth()->user()?->isTiStaff() ?? false))
                    ->form([
                        Select::make('decommissioned_by')
                            ->label('Técnico Responsable (TI)')
                            ->options(function () {
                                return \App\Models\User::all()
                                    ->filter(fn ($u) => $u->isTiStaff())
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->default(auth()->id())
                            ->required()
                            ->native(false)
                            ->helperText('Solo personal técnico del área de Desarrollo Tecnológico / TI está facultado.'),

                        Select::make('reason')
                            ->label('Motivo de Baja')
                            ->options(AssetDecommission::reasonOptions())
                            ->required()
                            ->native(false),

                        Select::make('resolution_type')
                            ->label('Tipo de Resolución')
                            ->options(AssetDecommission::resolutionTypeOptions())
                            ->required()
                            ->native(false)
                            ->helperText('Destino final del activo después de la baja.'),

                        Select::make('ticket_id')
                            ->label('Ticket de Origen (opcional)')
                            ->options(function () {
                                return \App\Models\Ticket::whereIn('status', ['resuelto', 'cerrado', 'Resuelto', 'Cerrado'])
                                    ->orderByDesc('id')
                                    ->limit(50)
                                    ->get()
                                    ->mapWithKeys(fn ($t) => [$t->id => "{$t->ticket_code} — {$t->title}"])
                                    ->toArray();
                            })
                            ->searchable()
                            ->nullable()
                            ->helperText('Si esta baja fue originada por un ticket, selecciónelo.'),

                        Textarea::make('evaluation_summary')
                            ->label('Dictamen Técnico')
                            ->placeholder('Describa detalladamente la evaluación técnica que determina la baja del activo...')
                            ->rows(4)
                            ->columnSpanFull(),

                        TextInput::make('authorized_by')
                            ->label('Autorizado por')
                            ->placeholder('Nombre del responsable que autoriza la baja')
                            ->helperText('Funcionario o jefe de área que autoriza este dictamen.')
                            ->nullable(),
                    ])
                    ->action(function ($record, array $data) {
                        $decommission = AssetDecommission::create([
                            'asset_id'           => $record->id,
                            'ticket_id'          => $data['ticket_id'] ?? null,
                            'decommissioned_by'  => $data['decommissioned_by'] ?? auth()->id(),
                            'authorized_by'      => $data['authorized_by'] ?? null,
                            'decommissioned_at'  => now(),
                            'reason'             => $data['reason'],
                            'resolution_type'    => $data['resolution_type'],
                            'evaluation_summary' => $data['evaluation_summary'] ?? null,
                            'notes'              => null,
                        ]);

                        $record->update([
                            'status' => 'baja',
                            'notes'  => trim(($record->notes ?? '') . "\nBaja definitiva registrada ({$decommission->ficha_number}) el " . now()->format('d/m/Y H:i') . ". Motivo: {$data['reason']}. Resolución: {$data['resolution_type']}."),
                        ]);

                        Notification::make()
                            ->title("Baja registrada — {$decommission->ficha_number}")
                            ->body('El activo fue dado de baja definitivamente.')
                            ->danger()
                            ->send();
                    }),

                Action::make('recuperar_activo')
                    ->label('Recuperar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->modalHeading('Recuperar Activo')
                    ->modalWidth('xl')
                    ->modalSubmitActionLabel('Confirmar Recuperación')
                    ->visible(fn ($record) => in_array(strtolower((string) ($record->status ?? '')), ['en evaluación', 'en evaluacion']) && (auth()->user()?->isTiStaff() ?? false))
                    ->form(function ($record) {
                        $ticket = Ticket::where('affected_asset_id', $record->id)
                            ->whereNotNull('replacement_asset_id')
                            ->latest('id')
                            ->first();

                        $replacement = $ticket?->replacementAsset;
                        $activeReplacementAssignment = $replacement 
                            ? AssetAssignment::where('asset_id', $replacement->id)->whereNull('returned_at')->latest('id')->first()
                            : null;

                        $schema = [];

                        if ($replacement && $activeReplacementAssignment) {
                            $repUser = $activeReplacementAssignment->user?->name ?? 'Usuario';
                            $repOffice = $activeReplacementAssignment->office?->name ?? '';
                            $parentAsset = $replacement->parent;
                            $parentCode = $parentAsset ? $parentAsset->computer_code : 'Equipo Principal';

                            $schema[] = Placeholder::make('replacement_info')
                                ->label('Activo de Reemplazo / Préstamo Detectado')
                                ->content(new HtmlString("
                                    <div style='padding: 12px; background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.35); border-radius: 8px; font-size: 0.875rem;'>
                                        <div style='font-weight: 600; margin-bottom: 4px; color: #b45309;'>Ticket de Origen: {$ticket->ticket_code} — {$ticket->title}</div>
                                        <div style='color: inherit;'>Este activo fue sustituido por <strong>{$replacement->computer_code}</strong>" . ($replacement->model ? " ({$replacement->model->brand?->name} {$replacement->model->name})" : "") . ", asignado actualmente a <strong>{$repUser}</strong>" . ($repOffice ? " — {$repOffice}" : "") . ($parentAsset ? " en el equipo <strong>{$parentAsset->computer_code}</strong>" : "") . ".</div>
                                    </div>
                                "));

                            $schema[] = Radio::make('resolution_mode')
                                ->label('¿Qué acción deseas realizar con los activos?')
                                ->options([
                                    'swap_back' => "Reinstalar en puesto original y devolver reemplazo al almacén (Recomendado)\n• {$record->computer_code} vuelve al usuario y se acopla a {$parentCode}.\n• {$replacement->computer_code} se desasigna y pasa a DISPONIBLE en el almacén.",
                                    'keep_replacement' => "Enviar el activo reparado al almacén (Mantener el reemplazo en el puesto)\n• {$record->computer_code} pasa a DISPONIBLE en el almacén para futuros tickets.\n• {$replacement->computer_code} permanece asignado permanentemente al usuario.",
                                ])
                                ->default('swap_back')
                                ->required();
                        } else {
                            $lastAssignment = AssetAssignment::where('asset_id', $record->id)
                                ->whereNotNull('returned_at')
                                ->orderByDesc('returned_at')
                                ->first();

                            if ($lastAssignment) {
                                $user = $lastAssignment->user?->name ?? "usuario #{$lastAssignment->user_id}";
                                $office = $lastAssignment->office?->name ?? '';
                                $schema[] = Radio::make('resolution_mode')
                                    ->label('Destino del activo recuperado')
                                    ->options([
                                        'reactivate' => "Reactivar asignación anterior ({$user}" . ($office ? " — {$office}" : "") . ")",
                                        'to_stock'   => "Pasar al almacén general como DISPONIBLE",
                                    ])
                                    ->default('reactivate')
                                    ->required();
                            } else {
                                $schema[] = Placeholder::make('no_prev_assignment')
                                    ->label('Estado')
                                    ->content('El activo no contaba con asignación previa. Al recuperarlo, pasará al estado DISPONIBLE en el inventario.');
                            }
                        }

                        $schema[] = Textarea::make('work_performed')
                            ->label('Dictamen / Trabajo Realizado (Opcional)')
                            ->placeholder('Detalle las acciones de reparación o mantenimiento efectuadas...')
                            ->rows(3);

                        return $schema;
                    })
                    ->action(function ($record, array $data) {
                        $mode = $data['resolution_mode'] ?? 'to_stock';
                        $workNotes = ! empty($data['work_performed']) ? "\nTrabajo realizado: {$data['work_performed']}" : '';

                        $ticket = Ticket::where('affected_asset_id', $record->id)
                            ->whereNotNull('replacement_asset_id')
                            ->latest('id')
                            ->first();

                        $replacement = $ticket?->replacementAsset;

                        if ($mode === 'swap_back' && $replacement) {
                            $targetParentId = $replacement->parent_id;

                            // 1. Liberar el activo de reemplazo y regresarlo a DISPONIBLE
                            AssetAssignment::where('asset_id', $replacement->id)
                                ->whereNull('returned_at')
                                ->update([
                                    'returned_at' => now(),
                                    'notes'       => trim(($replacement->notes ?? '') . "\nDevuelto al almacén por retorno del activo original {$record->computer_code} reparado en Ticket {$ticket->ticket_code} el " . now()->format('d/m/Y H:i') . '.'),
                                ]);

                            $replacement->update([
                                'status'    => 'disponible',
                                'parent_id' => null,
                                'notes'     => trim(($replacement->notes ?? '') . "\nLiberado y marcado como Disponible tras recuperación de {$record->computer_code}."),
                            ]);

                            // 2. Reactivar la asignación de $record
                            $lastAssignment = AssetAssignment::where('asset_id', $record->id)
                                ->whereNotNull('returned_at')
                                ->orderByDesc('returned_at')
                                ->first();

                            if ($lastAssignment) {
                                $lastAssignment->update([
                                    'returned_at' => null,
                                    'notes'       => trim(($lastAssignment->notes ?? '') . "\nAsignación reactivada por retorno del activo reparado el " . now()->format('d/m/Y H:i') . '.' . $workNotes),
                                ]);
                            } else {
                                $targetUser = $ticket->requester_id ?? $ticket->office?->users()->first()?->id ?? auth()->id();
                                AssetAssignment::create([
                                    'asset_id'    => $record->id,
                                    'user_id'     => $targetUser,
                                    'office_id'   => $ticket->office_id,
                                    'assigned_at' => now(),
                                    'notes'       => "Asignación reactivada tras reparación en Ticket {$ticket->ticket_code}." . $workNotes,
                                ]);
                            }

                            $record->update([
                                'status'    => 'asignado',
                                'parent_id' => $targetParentId,
                                'notes'     => trim(($record->notes ?? '') . "\nActivo recuperado y reinstalado en su puesto original el " . now()->format('d/m/Y H:i') . '.' . $workNotes),
                            ]);

                            Notification::make()
                                ->title('Activo Reinstalado y Reemplazo Liberado')
                                ->body("{$record->computer_code} ha vuelto a su puesto asignado. {$replacement->computer_code} ahora está DISPONIBLE.")
                                ->success()
                                ->send();

                        } elseif ($mode === 'keep_replacement' || $mode === 'to_stock') {
                            $record->update([
                                'status'    => 'disponible',
                                'parent_id' => null,
                                'notes'     => trim(($record->notes ?? '') . "\nActivo recuperado por dictamen técnico y enviado al almacén como Disponible el " . now()->format('d/m/Y H:i') . '.' . $workNotes),
                            ]);

                            Notification::make()
                                ->title('Activo Recuperado como Disponible')
                                ->body("{$record->computer_code} está ahora disponible en el inventario general.")
                                ->success()
                                ->send();

                        } elseif ($mode === 'reactivate') {
                            $lastAssignment = AssetAssignment::where('asset_id', $record->id)
                                ->whereNotNull('returned_at')
                                ->orderByDesc('returned_at')
                                ->first();

                            if ($lastAssignment) {
                                $lastAssignment->update([
                                    'returned_at' => null,
                                    'notes'       => trim(($lastAssignment->notes ?? '') . "\nAsignación reactivada por recuperación el " . now()->format('d/m/Y H:i') . '.' . $workNotes),
                                ]);
                                $record->update([
                                    'status' => 'asignado',
                                    'notes'  => trim(($record->notes ?? '') . "\nActivo recuperado el " . now()->format('d/m/Y H:i') . '.' . $workNotes),
                                ]);
                                Notification::make()
                                    ->title('Activo Recuperado')
                                    ->body('La asignación previa ha sido reactivada.')
                                    ->success()
                                    ->send();
                            } else {
                                $record->update([
                                    'status'    => 'disponible',
                                    'parent_id' => null,
                                    'notes'     => trim(($record->notes ?? '') . "\nActivo recuperado el " . now()->format('d/m/Y H:i') . '.' . $workNotes),
                                ]);
                                Notification::make()
                                    ->title('Activo Disponible')
                                    ->body('El activo no tenía asignación previa. Marcado como Disponible.')
                                    ->success()
                                    ->send();
                            }
                        }
                    }),

                Action::make('ver_ficha_baja')
                    ->label('Ficha Baja')
                    ->icon('heroicon-o-document-text')
                    ->color('gray')
                    ->visible(fn ($record) => strtolower((string) ($record->status ?? '')) === 'baja' && AssetDecommission::where('asset_id', $record->id)->exists())
                    ->url(fn ($record) => route('fichas.baja', ['id' => AssetDecommission::where('asset_id', $record->id)->latest()->value('id')]))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('asignar_masivo')
                        ->label('Asignar Seleccionados')
                        ->icon('heroicon-o-user-plus')
                        ->color('success')
                        ->modalHeading('Asignación Masiva de Activos')
                        ->modalDescription('Asigna todos los activos seleccionados que se encuentren disponibles a una dependencia y servidor público.')
                        ->modalSubmitActionLabel('Confirmar Asignación')
                        ->modalWidth('2xl')
                        ->form(function (Collection $records) {
                            $noDisponibles = $records->filter(fn ($a) => ! in_array(strtolower((string) $a->status), ['disponible']));
                            $disponibles = $records->filter(fn ($a) => in_array(strtolower((string) $a->status), ['disponible']));

                            $schema = [];

                            if ($noDisponibles->isNotEmpty()) {
                                $codigos = $noDisponibles->pluck('computer_code')->implode(', ');
                                $schema[] = Placeholder::make('warning_status')
                                    ->label('')
                                    ->content(new HtmlString("
                                        <div style='padding: 10px 14px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.35); border-radius: 8px; color: #b91c1c; font-size: 0.85rem;'>
                                            ⚠️ <strong>Atención:</strong> Los siguientes equipos no están en estado Disponible y serán omitidos: <strong>{$codigos}</strong>
                                        </div>
                                    "));
                            }

                            $schema[] = Placeholder::make('resumen_equipos')
                                ->label('Equipos Aptos para Asignar (' . $disponibles->count() . ')')
                                ->content(new HtmlString("
                                    <div style='max-height: 140px; overflow-y: auto; padding: 10px; background: rgba(100, 116, 139, 0.08); border-radius: 6px; font-family: monospace; font-size: 0.8rem; line-height: 1.6;'>
                                        " . ($disponibles->isNotEmpty()
                                            ? $disponibles->map(function ($a) {
                                                $brand = $a->model?->brand?->name ?? '';
                                                $model = $a->model?->name ?? '';
                                                $desc = trim("{$brand} {$model}");
                                                $cat = $a->category?->name ?? 'Activo';
                                                return "• <strong>[{$a->computer_code}]</strong> {$cat}" . ($desc ? " — {$desc}" : '');
                                            })->implode('<br>')
                                            : '<span style=\"color: #ef4444;\">No hay activos con estado Disponible en la selección.</span>'
                                        ) . "
                                    </div>
                                "));

                            $schema[] = Select::make('office_id')
                                ->label('Oficina / Dependencia de Destino')
                                ->options(fn () => \App\Models\Office::orderBy('name')->pluck('name', 'id')->toArray())
                                ->searchable()
                                ->preload()
                                ->required();

                            $schema[] = Select::make('user_id')
                                ->label('Funcionario / Empleado Responsable')
                                ->options(fn () => \App\Models\User::orderBy('name')->pluck('name', 'id')->toArray())
                                ->searchable()
                                ->preload()
                                ->required();

                            $schema[] = DateTimePicker::make('assigned_at')
                                ->label('Fecha y Hora de Asignación')
                                ->default(now())
                                ->required();

                            $schema[] = Textarea::make('notes')
                                ->label('Observaciones / Motivo de Entrega')
                                ->placeholder('Ej: Dotación de equipos para nueva estación de trabajo...')
                                ->rows(3);

                            return $schema;
                        })
                        ->action(function (Collection $records, array $data) {
                            $disponibles = $records->filter(fn ($a) => in_array(strtolower((string) $a->status), ['disponible']));

                            if ($disponibles->isEmpty()) {
                                Notification::make()
                                    ->title('Sin activos disponibles')
                                    ->body('Ninguno de los activos seleccionados tiene estado Disponible.')
                                    ->warning()
                                    ->send();
                                return;
                            }

                            DB::transaction(function () use ($disponibles, $data) {
                                foreach ($disponibles as $asset) {
                                    // Verificar que no se haya asignado previamente (por ejemplo si ya era hijo de otro seleccionado)
                                    $alreadyAssigned = AssetAssignment::where('asset_id', $asset->id)
                                        ->whereNull('returned_at')
                                        ->exists();

                                    if (! $alreadyAssigned) {
                                        AssetAssignment::create([
                                            'asset_id'    => $asset->id,
                                            'user_id'     => $data['user_id'],
                                            'office_id'   => $data['office_id'],
                                            'assigned_at' => $data['assigned_at'],
                                            'notes'       => $data['notes'] ?? 'Asignación masiva de activos.',
                                        ]);
                                    }
                                }
                            });

                            $user = \App\Models\User::find($data['user_id']);
                            $office = \App\Models\Office::find($data['office_id']);
                            $userName = $user?->name ?? 'Usuario';
                            $officeName = $office?->name ?? 'Oficina';

                            Notification::make()
                                ->title('Asignación Masiva Exitosa')
                                ->body("Se asignaron exitosamente {$disponibles->count()} activo(s) a {$userName} ({$officeName}).")
                                ->success()
                                ->send();
                        }),

                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
