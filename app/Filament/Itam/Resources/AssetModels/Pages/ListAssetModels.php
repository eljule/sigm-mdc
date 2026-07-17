<?php

namespace App\Filament\Itam\Resources\AssetModels\Pages;

use App\Filament\Itam\Resources\AssetModels\AssetModelResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAssetModels extends ListRecords
{
    protected static string $resource = AssetModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
