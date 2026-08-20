<?php

namespace App\Filament\Admin\Resources\LaborConditions;

use App\Filament\Admin\Resources\LaborConditions\Pages\CreateLaborCondition;
use App\Filament\Admin\Resources\LaborConditions\Pages\EditLaborCondition;
use App\Filament\Admin\Resources\LaborConditions\Pages\ListLaborConditions;
use App\Filament\Admin\Resources\LaborConditions\Schemas\LaborConditionForm;
use App\Filament\Admin\Resources\LaborConditions\Tables\LaborConditionsTable;
use App\Models\LaborCondition;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LaborConditionResource extends Resource
{
    protected static ?string $model = LaborCondition::class;

    protected static ?string $modelLabel = 'condición laboral';

    protected static ?string $pluralModelLabel = 'condiciones laborales';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Condiciones Laborales';

    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return LaborConditionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LaborConditionsTable::configure($table);
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
            'index' => ListLaborConditions::route('/'),
            'create' => CreateLaborCondition::route('/create'),
            'edit' => EditLaborCondition::route('/{record}/edit'),
        ];
    }
}
