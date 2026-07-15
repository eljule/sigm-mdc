<?php

namespace App\Filament\Resources\Subsystems\Pages;

use App\Filament\Resources\Subsystems\SubsystemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSubsystems extends ListRecords
{
    protected static string $resource = SubsystemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
