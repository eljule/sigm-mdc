<?php

namespace App\Filament\Resources\Subsystems\Pages;

use App\Filament\Resources\Subsystems\SubsystemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSubsystem extends EditRecord
{
    protected static string $resource = SubsystemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
