<?php

namespace App\Filament\Itam\Resources\AssetModels;

use App\Filament\Itam\Resources\AssetModels\Pages\CreateAssetModel;
use App\Filament\Itam\Resources\AssetModels\Pages\EditAssetModel;
use App\Filament\Itam\Resources\AssetModels\Pages\ListAssetModels;
use App\Filament\Itam\Resources\AssetModels\Schemas\AssetModelForm;
use App\Filament\Itam\Resources\AssetModels\Tables\AssetModelsTable;
use App\Models\AssetModel;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AssetModelResource extends Resource
{
    protected static ?string $model = AssetModel::class;

    protected static ?string $modelLabel = 'modelo';

    protected static ?string $pluralModelLabel = 'modelos';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|\UnitEnum|null $navigationGroup = 'Catálogos';

    public static function form(Schema $schema): Schema
    {
        return AssetModelForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AssetModelsTable::configure($table);
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
            'index' => ListAssetModels::route('/'),
            'create' => CreateAssetModel::route('/create'),
            'edit' => EditAssetModel::route('/{record}/edit'),
        ];
    }
}
