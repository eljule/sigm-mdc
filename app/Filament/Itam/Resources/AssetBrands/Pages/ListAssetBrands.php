<?php

namespace App\Filament\Itam\Resources\AssetBrands\Pages;

use App\Filament\Itam\Resources\AssetBrands\AssetBrandResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAssetBrands extends ListRecords
{
    protected static string $resource = AssetBrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
