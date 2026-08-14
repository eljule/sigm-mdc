<?php

namespace App\Filament\Itam\Resources\AssetCategories;

use App\Filament\Itam\Resources\AssetCategories\Pages\CreateAssetCategory;
use App\Filament\Itam\Resources\AssetCategories\Pages\EditAssetCategory;
use App\Filament\Itam\Resources\AssetCategories\Pages\ListAssetCategories;
use App\Filament\Itam\Resources\AssetCategories\Schemas\AssetCategoryForm;
use App\Filament\Itam\Resources\AssetCategories\Tables\AssetCategoriesTable;
use App\Models\AssetCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AssetCategoryResource extends Resource
{
    protected static ?string $model = AssetCategory::class;

    protected static ?string $modelLabel = 'categoría de activo';

    protected static ?string $pluralModelLabel = 'categorías de activos';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-folder-open';

    protected static string|\UnitEnum|null $navigationGroup = 'Catálogos TI';

    protected static ?string $navigationLabel = 'Categorías de Activos';

    protected static ?int $navigationSort = 14;

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
        return AssetCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AssetCategoriesTable::configure($table);
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
            'index' => ListAssetCategories::route('/'),
            'create' => CreateAssetCategory::route('/create'),
            'edit' => EditAssetCategory::route('/{record}/edit'),
        ];
    }
}
