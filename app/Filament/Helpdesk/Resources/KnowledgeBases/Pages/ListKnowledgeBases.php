<?php

namespace App\Filament\Helpdesk\Resources\KnowledgeBases\Pages;

use App\Filament\Helpdesk\Resources\KnowledgeBases\KnowledgeBaseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKnowledgeBases extends ListRecords
{
    protected static string $resource = KnowledgeBaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
