<?php

namespace App\Filament\Itam\Resources\Software;

use App\Filament\Itam\Resources\Software\Pages\CreateSoftware;
use App\Filament\Itam\Resources\Software\Pages\EditSoftware;
use App\Filament\Itam\Resources\Software\Pages\ListSoftware;
use App\Filament\Itam\Resources\Software\Schemas\SoftwareForm;
use App\Filament\Itam\Resources\Software\Tables\SoftwareTable;
use App\Models\Software;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SoftwareResource extends Resource
{
    protected static ?string $model = Software::class;

    protected static ?string $modelLabel = 'software';

    protected static ?string $pluralModelLabel = 'software';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return SoftwareForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SoftwareTable::configure($table);
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
            'index' => ListSoftware::route('/'),
            'create' => CreateSoftware::route('/create'),
            'edit' => EditSoftware::route('/{record}/edit'),
        ];
    }
}
