<?php

namespace App\Filament\Itam\Resources\Consumables\Pages;

use App\Filament\Itam\Resources\Consumables\ConsumableResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditConsumable extends EditRecord
{
    protected static string $resource = ConsumableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
