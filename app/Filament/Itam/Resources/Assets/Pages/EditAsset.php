<?php

namespace App\Filament\Itam\Resources\Assets\Pages;

use App\Filament\Itam\Resources\Assets\AssetResource;
use App\Models\AssetDecommission;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
                ->requiresConfirmation()
                ->modalHeading('Recuperar Activo')
                ->modalDescription(function () {
                    $lastAssignment = \App\Models\AssetAssignment::where('asset_id', $this->record->id)
                        ->whereNotNull('returned_at')
                        ->orderByDesc('returned_at')
                        ->first();

                    if ($lastAssignment) {
                        $user   = $lastAssignment->user?->name ?? "usuario #{$lastAssignment->user_id}";
                        $office = $lastAssignment->office?->name ?? '';
                        return "El activo fue evaluado positivamente. Se reactivará su asignación anterior a {$user}" . ($office ? " — {$office}" : '') . " y volverá al estado Asignado.";
                    }

                    return 'El activo fue evaluado positivamente. No tenía asignación previa, por lo que pasará al estado Disponible.';
                })
                ->modalSubmitActionLabel('Sí, recuperar activo')
                ->visible(fn () => in_array(strtolower($this->record->status ?? ''), ['en evaluación', 'en evaluacion']) && (auth()->user()?->isTiStaff() ?? false))
                ->action(function () {
                    $asset = $this->record;

                    $lastAssignment = \App\Models\AssetAssignment::where('asset_id', $asset->id)
                        ->whereNotNull('returned_at')
                        ->orderByDesc('returned_at')
                        ->first();

                    if ($lastAssignment) {
                        $lastAssignment->update([
                            'returned_at' => null,
                            'notes'       => trim(($lastAssignment->notes ?? '') . "\nAsignación reactivada por recuperación del activo el " . now()->format('d/m/Y H:i') . '.'),
                        ]);
                        $asset->update([
                            'status' => 'asignado',
                            'notes'  => trim(($asset->notes ?? '') . "\nActivo recuperado por dictamen técnico el " . now()->format('d/m/Y H:i') . '. Asignación reactivada.'),
                        ]);
                        $userName = $lastAssignment->user?->name ?? "usuario #{$lastAssignment->user_id}";
                        \Filament\Notifications\Notification::make()
                            ->title("Activo recuperado — Asignado a {$userName}")
                            ->body('La asignación anterior fue reactivada.')
                            ->success()
                            ->send();
                    } else {
                        $asset->update([
                            'status' => 'disponible',
                            'notes'  => trim(($asset->notes ?? '') . "\nActivo recuperado y marcado como Disponible por dictamen técnico el " . now()->format('d/m/Y H:i') . '.'),
                        ]);
                        \Filament\Notifications\Notification::make()
                            ->title('Activo recuperado y disponible')
                            ->body('El activo no tenía asignación previa. Marcado como Disponible.')
                            ->success()
                            ->send();
                    }

                    $this->refreshFormData(['status', 'notes']);
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
