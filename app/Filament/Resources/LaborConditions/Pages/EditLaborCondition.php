<?php

namespace App\Filament\Resources\LaborConditions\Pages;

use App\Filament\Resources\LaborConditions\LaborConditionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLaborCondition extends EditRecord
{
    protected static string $resource = LaborConditionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
