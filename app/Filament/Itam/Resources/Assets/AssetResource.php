<?php

namespace App\Filament\Itam\Resources\Assets;

use App\Filament\Itam\Resources\Assets\Pages\CreateAsset;
use App\Filament\Itam\Resources\Assets\Pages\EditAsset;
use App\Filament\Itam\Resources\Assets\Pages\ListAssets;
use App\Filament\Itam\Resources\Assets\Schemas\AssetForm;
use App\Filament\Itam\Resources\Assets\Tables\AssetsTable;
use App\Models\Asset;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

use App\Filament\Itam\Resources\Assets\RelationManagers\ComponentsRelationManager;
use App\Filament\Itam\Resources\Assets\RelationManagers\SoftwaresRelationManager;

class AssetResource extends Resource
{
    protected static ?string $model = Asset::class;

    protected static ?string $pluralModelLabel = 'activos';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-computer-desktop';

    protected static ?string $navigationLabel = 'Activos Tecnológicos';

    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('consultar-activos') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('insertar-activos') ?? false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->can('modificar-activos') ?? false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->can('eliminar-activos') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return AssetForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AssetsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ComponentsRelationManager::class,
            SoftwaresRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAssets::route('/'),
            'create' => CreateAsset::route('/create'),
            'edit' => EditAsset::route('/{record}/edit'),
        ];
    }
}
