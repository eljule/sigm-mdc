<?php

namespace App\Filament\Helpdesk\Resources\Tickets;

use App\Filament\Helpdesk\Resources\Tickets\Pages\CreateTicket;
use App\Filament\Helpdesk\Resources\Tickets\Pages\EditTicket;
use App\Filament\Helpdesk\Resources\Tickets\Pages\ListTickets;
use App\Filament\Helpdesk\Resources\Tickets\Schemas\TicketForm;
use App\Filament\Helpdesk\Resources\Tickets\Tables\TicketsTable;
use App\Models\Ticket;
use App\Filament\Helpdesk\Resources\Tickets\RelationManagers\MaintenancesRelationManager;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $pluralModelLabel = 'tickets';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationLabel = 'Tickets de Soporte';

    protected static ?int $navigationSort = 1;

    /**
     * Solo técnicos de soporte y administradores acceden al panel de tickets.
     * Los usuarios reportantes deben usar el portal /soporte.
     */
    public static function canAccess(): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        return $user->can('consultar-tickets') ||
               $user->can('atender-tickets') ||
               $user->allRoles()->whereIn('roles.name', [
                   'Administrador Central',
                   'Administrador de Helpdesk',
                   'Administrador de TI',
                   'Tecnico de Soporte',
                   'Tecnico de soporte',
                   'Admin-Soporte',
                   'admin-soporte',
               ])->exists();
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('insertar-tickets') ?? false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->can('modificar-tickets') ?? false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->can('eliminar-tickets') ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()?->can('eliminar-tickets') ?? false;
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        if (! $user) {
            return parent::getEloquentQuery();
        }

        // Si el usuario es técnico de soporte, administrador o posee permisos de consulta/atención de tickets, ve todos los tickets del sistema
        if ($user->can('consultar-tickets') ||
            $user->can('atender-tickets') ||
            $user->allRoles()->whereIn('roles.name', [
                'Administrador Central',
                'Administrador de Helpdesk',
                'Administrador de TI',
                'Tecnico de Soporte',
                'Tecnico de soporte',
                'Admin-Soporte',
                'admin-soporte',
            ])->exists()) {
            return parent::getEloquentQuery();
        }

        // Si es un usuario cliente/reportante común, solo ve sus propios tickets solicitados
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
            MaintenancesRelationManager::class,
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
