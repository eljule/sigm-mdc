<?php

declare(strict_types=1);

namespace App\Filament\Resources\Offices\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class OfficeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('parent_id')
                    ->relationship('parent', 'name')
                    ->label('Depende de')
                    ->placeholder('Seleccione una oficina padre (opcional)')
                    ->searchable()
                    ->preload(),
                TextInput::make('code')
                    ->label('Código')
                    ->required()
                    ->maxLength(20)
                    ->unique(ignoreRecord: true)
                    ->placeholder('Ej. 01.03.02'),
                TextInput::make('name')
                    ->label('Nombre de la Oficina')
                    ->required()
                    ->maxLength(150)
                    ->placeholder('Ej. Subgerencia de Rentas'),
                TextInput::make('acronym')
                    ->label('Siglas')
                    ->maxLength(15)
                    ->placeholder('Ej. SGR'),
                Toggle::make('is_active')
                    ->label('Activa')
                    ->default(true),
            ]);
    }
}
