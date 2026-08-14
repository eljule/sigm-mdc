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

    /**
     * Solo técnicos de soporte y administradores pueden gestionar categorías.
     */
    public static function canViewAny(): bool
    {
        return auth()->user()?->can('consultar-categorias') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('insertar-categorias') ?? false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->can('modificar-categorias') ?? false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->can('eliminar-categorias') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return TicketCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TicketCategoriesTable::configure($table);
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
            'index' => ListTicketCategories::route('/'),
            'create' => CreateTicketCategory::route('/create'),
            'edit' => EditTicketCategory::route('/{record}/edit'),
        ];
    }
}
