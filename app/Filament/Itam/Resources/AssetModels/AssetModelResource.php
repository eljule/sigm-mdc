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

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    protected static string|\UnitEnum|null $navigationGroup = 'Catálogos TI';

    protected static ?string $navigationLabel = 'Modelos';

    protected static ?int $navigationSort = 13;

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
