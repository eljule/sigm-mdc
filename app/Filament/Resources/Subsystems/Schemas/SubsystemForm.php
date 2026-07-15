<?php

declare(strict_types=1);

namespace App\Filament\Resources\Subsystems\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SubsystemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Código del Subsistema')
                    ->required()
                    ->maxLength(30)
                    ->unique(ignoreRecord: true)
                    ->placeholder('Ej. rentas'),
                TextInput::make('name')
                    ->label('Nombre del Subsistema')
                    ->required()
                    ->maxLength(100)
                    ->placeholder('Ej. Rentas y Administración Tributaria'),
                Textarea::make('description')
                    ->label('Descripción')
                    ->maxLength(500)
                    ->placeholder('Breve descripción del propósito del módulo'),
                TextInput::make('icon')
                    ->label('Icono (Heroicon)')
                    ->maxLength(50)
                    ->placeholder('Ej. heroicon-o-currency-dollar'),
                TextInput::make('url_path')
                    ->label('Ruta URL')
                    ->required()
                    ->maxLength(50)
                    ->placeholder('Ej. /rentas'),
                Toggle::make('is_active')
                    ->label('Activo')
                    ->default(true),
            ]);
    }
}
