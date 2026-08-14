<?php

namespace App\Filament\Itam\Resources\AssetMaintenances;

use App\Filament\Itam\Resources\AssetMaintenances\Pages\CreateAssetMaintenance;
use App\Filament\Itam\Resources\AssetMaintenances\Pages\EditAssetMaintenance;
use App\Filament\Itam\Resources\AssetMaintenances\Pages\ListAssetMaintenances;
use App\Filament\Itam\Resources\AssetMaintenances\Schemas\AssetMaintenanceForm;
use App\Filament\Itam\Resources\AssetMaintenances\Tables\AssetMaintenancesTable;
use App\Models\AssetMaintenance;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AssetMaintenanceResource extends Resource
{
    protected static ?string $model = AssetMaintenance::class;

    protected static ?string $pluralModelLabel = 'mantenimientos';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationLabel = 'Mantenimientos';

    protected static ?int $navigationSort = 3;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('consultar-mantenimientos') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('insertar-mantenimientos') ?? false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->can('modificar-mantenimientos') ?? false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->can('eliminar-mantenimientos') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return AssetMaintenanceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AssetMaintenancesTable::configure($table);
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
            'index' => ListAssetMaintenances::route('/'),
            'create' => CreateAssetMaintenance::route('/create'),
            'edit' => EditAssetMaintenance::route('/{record}/edit'),
        ];
    }
}
