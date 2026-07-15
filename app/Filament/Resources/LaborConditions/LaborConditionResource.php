<?php

namespace App\Filament\Resources\LaborConditions;

use App\Filament\Resources\LaborConditions\Pages\CreateLaborCondition;
use App\Filament\Resources\LaborConditions\Pages\EditLaborCondition;
use App\Filament\Resources\LaborConditions\Pages\ListLaborConditions;
use App\Filament\Resources\LaborConditions\Schemas\LaborConditionForm;
use App\Filament\Resources\LaborConditions\Tables\LaborConditionsTable;
use App\Models\LaborCondition;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LaborConditionResource extends Resource
{
    protected static ?string $model = LaborCondition::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

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
