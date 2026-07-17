<?php

namespace App\Filament\Itam\Resources\AssetBrands;

use App\Filament\Itam\Resources\AssetBrands\Pages\CreateAssetBrand;
use App\Filament\Itam\Resources\AssetBrands\Pages\EditAssetBrand;
use App\Filament\Itam\Resources\AssetBrands\Pages\ListAssetBrands;
use App\Filament\Itam\Resources\AssetBrands\Schemas\AssetBrandForm;
use App\Filament\Itam\Resources\AssetBrands\Tables\AssetBrandsTable;
use App\Models\AssetBrand;
use BackedEnum;
use App\Filament\Itam\Resources\AssetBrands\RelationManagers;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AssetBrandResource extends Resource
{
    protected static ?string $model = AssetBrand::class;

    protected static ?string $modelLabel = 'marca';

    protected static ?string $pluralModelLabel = 'marcas';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookmark;

    protected static string|\UnitEnum|null $navigationGroup = 'Catálogos';

    public static function form(Schema $schema): Schema
    {
        return AssetBrandForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AssetBrandsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ModelsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAssetBrands::route('/'),
            'create' => CreateAssetBrand::route('/create'),
            'edit' => EditAssetBrand::route('/{record}/edit'),
        ];
    }
}
