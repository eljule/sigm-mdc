<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Ubigeos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UbigeoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Código INEI')
                    ->required()
                    ->length(6)
                    ->unique(ignoreRecord: true)
                    ->placeholder('Ej. 150101'),
                TextInput::make('department')
                    ->label('Departamento')
                    ->required()
                    ->maxLength(100)
                    ->placeholder('Ej. Lima'),
                TextInput::make('province')
                    ->label('Provincia')
                    ->required()
                    ->maxLength(100)
                    ->placeholder('Ej. Lima'),
                TextInput::make('district')
                    ->label('Distrito')
                    ->required()
                    ->maxLength(100)
                    ->placeholder('Ej. San Isidro'),
                Toggle::make('is_active')
                    ->label('Activo')
                    ->default(true),
            ]);
    }
}
