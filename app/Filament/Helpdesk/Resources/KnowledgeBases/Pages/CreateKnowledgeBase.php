<?php

namespace App\Filament\Helpdesk\Resources\KnowledgeBases\Pages;

use App\Filament\Helpdesk\Resources\KnowledgeBases\KnowledgeBaseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKnowledgeBase extends CreateRecord
{
    protected static string $resource = KnowledgeBaseResource::class;
}
