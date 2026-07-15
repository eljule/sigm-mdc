<?php

namespace App\Filament\Itam\Resources\AssetAssignments\Pages;

use App\Filament\Itam\Resources\AssetAssignments\AssetAssignmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAssetAssignments extends ListRecords
{
    protected static string $resource = AssetAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
