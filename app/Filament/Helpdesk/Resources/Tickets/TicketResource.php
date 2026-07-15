<?php

namespace App\Filament\Helpdesk\Resources\Tickets;

use App\Filament\Helpdesk\Resources\Tickets\Pages\CreateTicket;
use App\Filament\Helpdesk\Resources\Tickets\Pages\EditTicket;
use App\Filament\Helpdesk\Resources\Tickets\Pages\ListTickets;
use App\Filament\Helpdesk\Resources\Tickets\Schemas\TicketForm;
use App\Filament\Helpdesk\Resources\Tickets\Tables\TicketsTable;
use App\Models\Ticket;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        if (! $user) {
            return parent::getEloquentQuery();
        }

        // Si es Técnico de Soporte o Administrador Central, ve todo
        if ($user->hasRole('Técnico de Soporte') || $user->hasRole('Administrador Central')) {
            return parent::getEloquentQuery();
        }

        // De lo contrario, solo ve sus propios tickets
        return parent::getEloquentQuery()->where('requester_id', $user->id);
    }

    public static function form(Schema $schema): Schema
    {
        return TicketForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TicketsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTickets::route('/'),
            'create' => CreateTicket::route('/create'),
            'edit' => EditTicket::route('/{record}/edit'),
        ];
    }
}
