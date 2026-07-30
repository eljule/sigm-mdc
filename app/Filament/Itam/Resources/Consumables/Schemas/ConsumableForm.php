<?php

namespace App\Filament\Itam\Resources\Consumables\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;

class ConsumableForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detalles del Insumo / Consumible')
                    ->description('Registre los insumos y controle su stock en el inventario de TI.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre del Material / Insumo')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->placeholder('Ej. Cable de Red Cat6 2m o Tóner HP 85A')
                            ->columnSpanFull(),
                        TextInput::make('stock')
                            ->label('Stock Inicial / Actual')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->required(),
                        Select::make('unit')
                            ->label('Unidad de Medida')
                            ->options([
                                'Unidades' => 'Unidades',
                                'Metros' => 'Metros',
                                'Litros' => 'Litros',
                                'Packs' => 'Packs',
                                'Pares' => 'Pares',
                            ])
                            ->default('Unidades')
                            ->required(),
                        TextInput::make('min_stock')
                            ->label('Stock Mínimo (Alerta)')
                            ->helperText('Cuando el stock sea menor o igual a este valor, se mostrará una alerta.')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->required(),
                    ]),
            ]);
    }
}
