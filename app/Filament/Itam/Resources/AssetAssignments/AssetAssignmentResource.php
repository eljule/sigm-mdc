<?php

namespace App\Filament\Itam\Resources\AssetAssignments;

use App\Filament\Itam\Resources\AssetAssignments\Pages\CreateAssetAssignment;
use App\Filament\Itam\Resources\AssetAssignments\Pages\EditAssetAssignment;
use App\Filament\Itam\Resources\AssetAssignments\Pages\ListAssetAssignments;
use App\Filament\Itam\Resources\AssetAssignments\Schemas\AssetAssignmentForm;
use App\Filament\Itam\Resources\AssetAssignments\Tables\AssetAssignmentsTable;
use App\Models\AssetAssignment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AssetAssignmentResource extends Resource
{
    protected static ?string $model = AssetAssignment::class;

    protected static ?string $pluralModelLabel = 'asignaciones';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Asignaciones';

    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('consultar-asignaciones') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('insertar-asignaciones') ?? false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->can('modificar-asignaciones') ?? false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->can('eliminar-asignaciones') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return AssetAssignmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AssetAssignmentsTable::configure($table);
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
            'index' => ListAssetAssignments::route('/'),
            'create' => CreateAssetAssignment::route('/create'),
            'edit' => EditAssetAssignment::route('/{record}/edit'),
        ];
    }
}
