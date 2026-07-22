<?php

declare(strict_types=1);

namespace App\Filament\Itam\Resources\AssetCategories\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AssetCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detalles de la Categoría')
                    ->description('Defina el nombre y descripción general de la categoría de activos.')
                    ->compact()
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre de Categoría')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Ej. Laptop, Servidor, PC de Escritorio'),
                        Textarea::make('description')
                            ->label('Descripción')
                            ->maxLength(250)
                            ->columnSpanFull()
                            ->placeholder('Ej. Computadores portátiles asignados a personal municipal.'),
                    ]),

                Section::make('Estructura de Bloques y Características')
                    ->description('Configure las secciones y los campos dinámicos que se solicitarán al registrar activos bajo esta categoría.')
                    ->schema([
                        Repeater::make('blocks')
                            ->relationship('blocks')
                            ->label('Bloques / Secciones del Formulario')
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                            ->defaultItems(1)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nombre del Bloque')
                                    ->required()
                                    ->placeholder('Ej. Especificaciones de Hardware'),
                                TextInput::make('sort_order')
                                    ->label('Orden de Bloque')
                                    ->numeric()
                                    ->default(0)
                                    ->required(),
                                
                                Repeater::make('characteristics')
                                    ->relationship('characteristics')
                                    ->label('Características / Campos del Bloque')
                                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                                    ->defaultItems(0)
                                    ->columns(3)
                                    ->grid(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Nombre del Campo')
                                            ->required()
                                            ->placeholder('Ej. Procesador'),
                                        Select::make('type')
                                            ->label('Tipo de Entrada')
                                            ->options([
                                                'text' => 'Texto',
                                                'number' => 'Número',
                                                'date' => 'Fecha',
                                                'select' => 'Lista Desplegable',
                                                'boolean' => 'Booleano (Sí/No)',
                                            ])
                                            ->default('text')
                                            ->required()
                                            ->live(),
                                        TextInput::make('options')
                                            ->label('Opciones (Separadas por comas)')
                                            ->placeholder('Ej. Intel Core i5, Intel Core i7')
                                            ->visible(fn ($get) => $get('type') === 'select'),
                                        Toggle::make('is_required')
                                            ->label('Requerido / Obligatorio')
                                            ->default(false),
                                        TextInput::make('sort_order')
                                            ->label('Orden del Campo')
                                            ->numeric()
                                            ->default(0)
                                            ->required(),
                                    ])
                                    ->columnSpanFull(),
                            ])
                            ->columnSpanFull()
                            ->grid(1),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
