<?php

namespace App\Filament\Itam\Resources\Consumables;

use App\Filament\Itam\Resources\Consumables\Pages\CreateConsumable;
use App\Filament\Itam\Resources\Consumables\Pages\EditConsumable;
use App\Filament\Itam\Resources\Consumables\Pages\ListConsumables;
use App\Filament\Itam\Resources\Consumables\Schemas\ConsumableForm;
use App\Filament\Itam\Resources\Consumables\Tables\ConsumablesTable;
use App\Models\Consumable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ConsumableResource extends Resource
{
    protected static ?string $model = Consumable::class;

    protected static ?string $pluralModelLabel = 'insumos y consumibles';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-archive-box';

    protected static string|\UnitEnum|null $navigationGroup = 'Catálogos TI';

    protected static ?string $navigationLabel = 'Insumos y Consumibles';

    protected static ?int $navigationSort = 10;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('consultar-catalogos-ti') || auth()->user()?->can('gestionar-catalogos-ti');
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('gestionar-catalogos-ti') ?? false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->can('gestionar-catalogos-ti') ?? false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->can('gestionar-catalogos-ti') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return ConsumableForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ConsumablesTable::configure($table);
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
            'index' => ListConsumables::route('/'),
            'create' => CreateConsumable::route('/create'),
            'edit' => EditConsumable::route('/{record}/edit'),
        ];
    }
}
