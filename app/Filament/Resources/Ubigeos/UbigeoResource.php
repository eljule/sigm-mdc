<?php

namespace App\Filament\Resources\Ubigeos;

use App\Filament\Resources\Ubigeos\Pages\CreateUbigeo;
use App\Filament\Resources\Ubigeos\Pages\EditUbigeo;
use App\Filament\Resources\Ubigeos\Pages\ListUbigeos;
use App\Filament\Resources\Ubigeos\Schemas\UbigeoForm;
use App\Filament\Resources\Ubigeos\Tables\UbigeosTable;
use App\Models\Ubigeo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UbigeoResource extends Resource
{
    protected static ?string $model = Ubigeo::class;

    protected static ?string $modelLabel = 'ubigeo';

    protected static ?string $pluralModelLabel = 'ubigeos';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return UbigeoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UbigeosTable::configure($table);
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
            'index' => ListUbigeos::route('/'),
            'create' => CreateUbigeo::route('/create'),
            'edit' => EditUbigeo::route('/{record}/edit'),
        ];
    }
}
