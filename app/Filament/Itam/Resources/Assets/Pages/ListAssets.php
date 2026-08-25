<?php

namespace App\Filament\Itam\Resources\Assets\Pages;

use App\Filament\Itam\Resources\Assets\AssetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAssets extends ListRecords
{
    protected static string $resource = AssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Crear Activo'),
        ];
    }
}
