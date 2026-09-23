<?php

namespace App\Filament\Itam\Resources\Assets\Pages;

use App\Filament\Itam\Resources\Assets\AssetResource;
use App\Models\AssetDecommission;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;
use App\Models\AssetAssignment;
use App\Models\Ticket;
use Filament\Resources\Pages\EditRecord;

class EditAsset extends EditRecord
{
    protected static string $resource = AssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // ─── ACCIÓN: Ver Ficha de Baja (cuando ya existe registro de baja) ───
            Action::make('ver_ficha_baja')
                ->label('Ver Ficha de Baja')
                ->icon('heroicon-o-document-text')
                ->color('gray')
                ->visible(function () {
                    if (strtolower($this->record->status ?? '') !== 'baja') return false;
                    return AssetDecommission::where('asset_id', $this->record->id)->exists();
                })
                ->url(function () {
                    $decommission = AssetDecommission::where('asset_id', $this->record->id)
                        ->latest()
                        ->first();
                    return $decommission
                        ? route('fichas.baja', ['id' => $decommission->id])
                        : null;
                })
                ->openUrlInNewTab(),

            // ─── ACCIÓN: Dar de Baja Definitiva (solo En Evaluación y Personal TI) ───
            Action::make('confirmar_baja')
                ->label('Dar de Baja Definitiva')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->modalHeading('Registro de Baja Definitiva')
                ->modalSubmitActionLabel('Confirmar Baja')
                ->modalWidth('2xl')
                ->visible(fn () => in_array(strtolower($this->record->status ?? ''), ['en evaluación', 'en evaluacion']) && (auth()->user()?->isTiStaff() ?? false))
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
                ->action(function (array $data) {
                    $asset = $this->record;

                    // Crear el registro formal de baja
                    $decommission = AssetDecommission::create([
                        'asset_id'           => $asset->id,
                        'ticket_id'          => $data['ticket_id'] ?? null,
                        'decommissioned_by'  => $data['decommissioned_by'] ?? auth()->id(),
                        'authorized_by'      => $data['authorized_by'] ?? null,
                        'decommissioned_at'  => now(),
                        'reason'             => $data['reason'],
                        'resolution_type'    => $data['resolution_type'],
                        'evaluation_summary' => $data['evaluation_summary'] ?? null,
                        'notes'              => null,
                    ]);

                    // Actualizar el estado del activo
                    $asset->update([
                        'status' => 'baja',
                        'notes'  => trim(($asset->notes ?? '') . "\nBaja definitiva registrada ({$decommission->ficha_number}) el " . now()->format('d/m/Y H:i') . ". Motivo: {$data['reason']}. Resolución: {$data['resolution_type']}."),
                    ]);

                    $this->refreshFormData(['status', 'notes']);

                    \Filament\Notifications\Notification::make()
                        ->title("Baja registrada — {$decommission->ficha_number}")
                        ->body('El activo fue dado de baja definitivamente. Puede imprimir la ficha desde el botón "Ver Ficha de Baja".')
                        ->danger()
                        ->send();
                }),

            // ─── ACCIÓN: Recuperar Activo (solo En Evaluación y Personal TI) ───
            Action::make('recuperar_activo')
                ->label('Recuperar Activo')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->modalHeading('Recuperar Activo')
                ->modalWidth('xl')
                ->modalSubmitActionLabel('Confirmar Recuperación')
                ->visible(fn () => in_array(strtolower((string) ($this->record->status ?? '')), ['en evaluación', 'en evaluacion']) && (auth()->user()?->isTiStaff() ?? false))
                ->form(function () {
                    $record = $this->record;
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
                ->action(function (array $data) {
                    $record = $this->record;
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

                        $this->refreshFormData(['status', 'notes', 'parent_id']);

                        \Filament\Notifications\Notification::make()
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

                        $this->refreshFormData(['status', 'notes', 'parent_id']);

                        \Filament\Notifications\Notification::make()
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
                            \Filament\Notifications\Notification::make()
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

                        $this->refreshFormData(['status', 'notes', 'parent_id']);
                    }
                }),

            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $values = \App\Models\AssetCharacteristicValue::where('asset_id', $this->record->id)->get();
        foreach ($values as $val) {
            $data['char_' . $val->asset_characteristic_id] = $val->value;
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $asset = $this->record;
        $categoryId = $asset->asset_category_id;

        // Get all characteristic IDs for this category
        $validCharIds = \App\Models\AssetCharacteristic::whereHas('block', function ($q) use ($categoryId) {
            $q->where('asset_category_id', $categoryId);
        })->pluck('id')->toArray();

        // Delete values for characteristics that are no longer part of this category
        \App\Models\AssetCharacteristicValue::where('asset_id', $asset->id)
            ->whereNotIn('asset_characteristic_id', $validCharIds)
            ->delete();

        // Save the dynamic fields
        foreach ($this->data as $key => $value) {
            if (str_starts_with($key, 'char_')) {
                $charId = (int) str_replace('char_', '', $key);
                if (in_array($charId, $validCharIds)) {
                    \App\Models\AssetCharacteristicValue::updateOrCreate(
                        ['asset_id' => $asset->id, 'asset_characteristic_id' => $charId],
                        ['value' => $value !== null ? (string)$value : null]
                    );
                }
            }
        }
    }
}
