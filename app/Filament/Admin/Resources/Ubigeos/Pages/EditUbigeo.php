<?php

namespace App\Filament\Admin\Resources\Ubigeos\Pages;

use App\Filament\Admin\Resources\Ubigeos\UbigeoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUbigeo extends EditRecord
{
    protected static string $resource = UbigeoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
