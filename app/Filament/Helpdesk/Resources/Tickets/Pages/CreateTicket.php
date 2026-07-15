<?php

namespace App\Filament\Helpdesk\Resources\Tickets\Pages;

use App\Filament\Helpdesk\Resources\Tickets\TicketResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;
}
