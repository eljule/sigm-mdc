<?php

namespace App\Filament\Itam\Resources\Assets\Pages;

use App\Filament\Itam\Resources\Assets\AssetResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAsset extends EditRecord
{
    protected static string $resource = AssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
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
