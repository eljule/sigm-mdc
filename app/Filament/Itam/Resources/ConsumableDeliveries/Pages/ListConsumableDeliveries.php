<?php

namespace App\Filament\Itam\Resources\ConsumableDeliveries\Pages;

use App\Filament\Itam\Resources\ConsumableDeliveries\ConsumableDeliveryResource;
use Filament\Resources\Pages\ListRecords;

class ListConsumableDeliveries extends ListRecords
{
    protected static string $resource = ConsumableDeliveryResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
