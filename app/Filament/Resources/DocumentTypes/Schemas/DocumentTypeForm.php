<?php

declare(strict_types=1);

namespace App\Filament\Resources\DocumentTypes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DocumentTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Código')
                    ->required()
                    ->maxLength(5)
                    ->unique(ignoreRecord: true)
                    ->placeholder('Ej. DNI'),
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(50)
                    ->placeholder('Ej. Documento Nacional de Identidad'),
                TextInput::make('length')
                    ->label('Longitud')
                    ->numeric()
                    ->required()
                    ->default(8),
                Toggle::make('is_active')
                    ->label('Activo')
                    ->default(true),
            ]);
    }
}
