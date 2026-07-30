<?php

namespace App\Filament\Itam\Resources\Consumables\Pages;

use App\Filament\Itam\Resources\Consumables\ConsumableResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListConsumables extends ListRecords
{
    protected static string $resource = ConsumableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
