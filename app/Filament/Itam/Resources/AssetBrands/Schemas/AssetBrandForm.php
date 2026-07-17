<?php

declare(strict_types=1);

namespace App\Filament\Itam\Resources\AssetBrands\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AssetBrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre de la Marca')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(100),
                Textarea::make('description')
                    ->label('Descripción')
                    ->maxLength(500)
                    ->columnSpanFull(),
            ]);
    }
}
