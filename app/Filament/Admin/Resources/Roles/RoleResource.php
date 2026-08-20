<?php

namespace App\Filament\Admin\Resources\Roles;

use App\Filament\Admin\Resources\Roles\Pages\CreateRole;
use App\Filament\Admin\Resources\Roles\Pages\EditRole;
use App\Filament\Admin\Resources\Roles\Pages\ListRoles;
use App\Filament\Admin\Resources\Roles\RelationManagers\PermissionsRelationManager;
use App\Filament\Admin\Resources\Roles\Schemas\RoleForm;
use App\Filament\Admin\Resources\Roles\Tables\RolesTable;
use App\Models\Role;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $modelLabel = 'rol';

    protected static ?string $pluralModelLabel = 'roles';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationLabel = 'Roles';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return RoleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RolesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            PermissionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }
}
