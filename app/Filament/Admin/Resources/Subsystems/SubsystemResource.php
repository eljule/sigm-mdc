<?php

namespace App\Filament\Admin\Resources\Subsystems;

use App\Filament\Admin\Resources\Subsystems\Pages\CreateSubsystem;
use App\Filament\Admin\Resources\Subsystems\Pages\EditSubsystem;
use App\Filament\Admin\Resources\Subsystems\Pages\ListSubsystems;
use App\Filament\Admin\Resources\Subsystems\Schemas\SubsystemForm;
use App\Filament\Admin\Resources\Subsystems\Tables\SubsystemsTable;
use App\Models\Subsystem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SubsystemResource extends Resource
{
    protected static ?string $model = Subsystem::class;

    protected static ?string $modelLabel = 'subsistema';

    protected static ?string $pluralModelLabel = 'subsistemas';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationLabel = 'Subsistemas';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return SubsystemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SubsystemsTable::configure($table);
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
            'index' => ListSubsystems::route('/'),
            'create' => CreateSubsystem::route('/create'),
            'edit' => EditSubsystem::route('/{record}/edit'),
        ];
    }
}
