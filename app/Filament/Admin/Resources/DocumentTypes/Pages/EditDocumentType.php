<?php

namespace App\Filament\Admin\Resources\DocumentTypes\Pages;

use App\Filament\Admin\Resources\DocumentTypes\DocumentTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDocumentType extends EditRecord
{
    protected static string $resource = DocumentTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
