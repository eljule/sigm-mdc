<?php

namespace App\Filament\Itam\Resources\AssetBrands\Pages;

use App\Filament\Itam\Resources\AssetBrands\AssetBrandResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAssetBrand extends EditRecord
{
    protected static string $resource = AssetBrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
