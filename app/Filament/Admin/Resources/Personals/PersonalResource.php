<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Personals;

use App\Filament\Admin\Resources\Personals\Pages\CreatePersonal;
use App\Filament\Admin\Resources\Personals\Pages\EditPersonal;
use App\Filament\Admin\Resources\Personals\Pages\ListPersonals;
use App\Filament\Admin\Resources\Personals\Schemas\PersonalForm;
use App\Filament\Admin\Resources\Personals\Tables\PersonalsTable;
use App\Models\Personal;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use BackedEnum;
use UnitEnum;

class PersonalResource extends Resource
{
    protected static ?string $model = Personal::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static string|UnitEnum|null $navigationGroup = 'Gestión Municipal (RRHH)';

    protected static ?string $navigationLabel = 'Plantilla de Personal';

    protected static ?string $modelLabel = 'Personal Municipal';

    protected static ?string $pluralModelLabel = 'Personal Municipal';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return PersonalForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PersonalsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPersonals::route('/'),
            'create' => CreatePersonal::route('/create'),
            'edit' => EditPersonal::route('/{record}/edit'),
        ];
    }
}
