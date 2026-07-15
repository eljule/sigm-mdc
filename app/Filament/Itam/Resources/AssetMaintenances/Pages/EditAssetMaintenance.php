<?php

namespace App\Filament\Itam\Resources\AssetMaintenances\Pages;

use App\Filament\Itam\Resources\AssetMaintenances\AssetMaintenanceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAssetMaintenance extends EditRecord
{
    protected static string $resource = AssetMaintenanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
