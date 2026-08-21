<?php

namespace App\Filament\Helpdesk\Resources\TicketCategories;

use App\Filament\Helpdesk\Resources\TicketCategories\Pages\CreateTicketCategory;
use App\Filament\Helpdesk\Resources\TicketCategories\Pages\EditTicketCategory;
use App\Filament\Helpdesk\Resources\TicketCategories\Pages\ListTicketCategories;
use App\Filament\Helpdesk\Resources\TicketCategories\Schemas\TicketCategoryForm;
use App\Filament\Helpdesk\Resources\TicketCategories\Tables\TicketCategoriesTable;
use App\Models\TicketCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TicketCategoryResource extends Resource
{
    protected static ?string $model = TicketCategory::class;

    protected static ?string $modelLabel = 'categoría';

    protected static ?string $pluralModelLabel = 'categorías';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationLabel = 'Categorías de Tickets';

    protected static ?int $navigationSort = 3;

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        return $user->can('consultar-categorias-tickets') ||
               $user->can('gestionar-categorias-tickets') ||
               $user->can('consultar-categorias');
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        return $user->can('insertar-categorias') ||
               $user->can('gestionar-categorias-tickets');
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        return $user->can('modificar-categorias') ||
               $user->can('gestionar-categorias-tickets');
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        return $user->can('eliminar-categorias') ||
               $user->can('gestionar-categorias-tickets');
    }

    public static function form(Schema $schema): Schema
    {
        return TicketCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TicketCategoriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListTicketCategories::route('/'),
            'create' => CreateTicketCategory::route('/create'),
            'edit'   => EditTicketCategory::route('/{record}/edit'),
        ];
    }
}
