<?php

declare(strict_types=1);

namespace App\Filament\Itam\Resources\AssetModels\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AssetModelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('asset_brand_id')
                    ->relationship('brand', 'name')
                    ->label('Marca')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('name')
                    ->label('Nombre del Modelo')
                    ->required()
                    ->maxLength(100),
                Textarea::make('description')
                    ->label('Descripción')
                    ->maxLength(500)
                    ->columnSpanFull(),
            ]);
    }
}
