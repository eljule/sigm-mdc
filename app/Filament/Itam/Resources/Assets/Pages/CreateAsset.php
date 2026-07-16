<?php

namespace App\Filament\Itam\Resources\Assets\Pages;

use App\Filament\Itam\Resources\Assets\AssetResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAsset extends CreateRecord
{
    protected static string $resource = AssetResource::class;

    protected function afterCreate(): void
    {
        $asset = $this->record;
        $categoryId = $asset->asset_category_id;

        // Get all characteristic IDs for this category
        $validCharIds = \App\Models\AssetCharacteristic::whereHas('block', function ($q) use ($categoryId) {
            $q->where('asset_category_id', $categoryId);
        })->pluck('id')->toArray();

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
