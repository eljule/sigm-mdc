<?php

namespace App\Filament\Helpdesk\Resources\TicketCategories\Pages;

use App\Filament\Helpdesk\Resources\TicketCategories\TicketCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTicketCategory extends CreateRecord
{
    protected static string $resource = TicketCategoryResource::class;
}
