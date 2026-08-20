<?php

namespace App\Filament\Admin\Resources\LaborConditions\Pages;

use App\Filament\Admin\Resources\LaborConditions\LaborConditionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLaborConditions extends ListRecords
{
    protected static string $resource = LaborConditionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
