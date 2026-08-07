<?php

namespace App\Filament\Itam\Resources\Assets\Pages;

use App\Filament\Itam\Resources\Assets\AssetResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAsset extends EditRecord
{
    protected static string $resource = AssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Acción: Dar de Baja Definitiva (solo cuando está En Evaluación)
            Action::make('confirmar_baja')
                ->label('Dar de Baja Definitiva')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Confirmar Baja Definitiva')
                ->modalDescription('¿Estás seguro de que este activo debe darse de baja definitivamente? Esta acción registrará el activo como dado de baja y no podrá asignarse nuevamente.')
                ->modalSubmitActionLabel('Sí, dar de baja')
                ->visible(fn () => $this->record->status === 'En Evaluación')
                ->action(function () {
                    $this->record->update([
                        'status' => 'Baja',
                        'notes' => trim(($this->record->notes ?? '') . "\nBaja definitiva confirmada por dictamen técnico el " . now()->format('d/m/Y H:i') . '.'),
                    ]);
                    $this->refreshFormData(['status', 'notes']);
                    \Filament\Notifications\Notification::make()
                        ->title('Activo dado de baja definitivamente')
                        ->danger()
                        ->send();
                }),

            // Acción: Recuperar Activo (solo cuando está En Evaluación)
            Action::make('recuperar_activo')
                ->label('Recuperar Activo')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Recuperar Activo')
                ->modalDescription(function () {
                    // Buscar la última asignación cerrada para determinar el estado previo
                    $lastAssignment = \App\Models\AssetAssignment::where('asset_id', $this->record->id)
                        ->whereNotNull('returned_at')
                        ->orderByDesc('returned_at')
                        ->first();

                    if ($lastAssignment) {
                        $user = $lastAssignment->user?->name ?? "usuario #{$lastAssignment->user_id}";
                        $office = $lastAssignment->office?->name ?? '';
                        return "El activo fue evaluado positivamente. Se reactivará su asignación anterior a {$user}" . ($office ? " — {$office}" : '') . " y volverá al estado Asignado.";
                    }

                    return 'El activo fue evaluado positivamente. No tenía asignación previa, por lo que pasará al estado Disponible.';
                })
                ->modalSubmitActionLabel('Sí, recuperar activo')
                ->visible(fn () => $this->record->status === 'En Evaluación')
                ->action(function () {
                    $asset = $this->record;

                    // Buscar la última asignación cerrada de este activo
                    $lastAssignment = \App\Models\AssetAssignment::where('asset_id', $asset->id)
                        ->whereNotNull('returned_at')
                        ->orderByDesc('returned_at')
                        ->first();

                    if ($lastAssignment) {
                        // Reactivar la asignación anterior: borrar returned_at
                        $lastAssignment->update([
                            'returned_at' => null,
                            'notes' => trim(($lastAssignment->notes ?? '') . "\nAsignación reactivada por recuperación del activo el " . now()->format('d/m/Y H:i') . '.'),
                        ]);

                        $asset->update([
                            'status' => 'Asignado',
                            'notes' => trim(($asset->notes ?? '') . "\nActivo recuperado por dictamen técnico el " . now()->format('d/m/Y H:i') . '. Asignación reactivada.'),
                        ]);

                        $userName = $lastAssignment->user?->name ?? "usuario #{$lastAssignment->user_id}";

                        \Filament\Notifications\Notification::make()
                            ->title("Activo recuperado — Asignado a {$userName}")
                            ->body('La asignación anterior fue reactivada.')
                            ->success()
                            ->send();
                    } else {
                        // Sin asignación previa: dejar como Disponible
                        $asset->update([
                            'status' => 'Disponible',
                            'notes' => trim(($asset->notes ?? '') . "\nActivo recuperado y marcado como Disponible por dictamen técnico el " . now()->format('d/m/Y H:i') . '.'),
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
