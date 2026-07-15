<?php

declare(strict_types=1);

namespace App\Filament\Itam\Resources\Assets\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AssetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('asset_code')
                    ->label('Código de Activo / Patrimonial')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->placeholder('Ej. PAT-2026-0001'),
                Select::make('category')
                    ->label('Categoría')
                    ->options([
                        'Servidor' => 'Servidor',
                        'PC' => 'PC de Escritorio',
                        'Laptop' => 'Laptop / Portátil',
                        'Impresora' => 'Impresora / Multifuncional',
                        'Switch' => 'Switch de Red',
                        'Router' => 'Router',
                        'Access Point' => 'Access Point (Wi-Fi)',
                        'Firewall' => 'Firewall',
                    ])
                    ->required()
                    ->searchable(),
                TextInput::make('brand')
                    ->label('Marca')
                    ->required()
                    ->placeholder('Ej. HP, Dell, Lenovo'),
                TextInput::make('model')
                    ->label('Modelo')
                    ->required()
                    ->placeholder('Ej. ThinkCentre M70q'),
                TextInput::make('serial_number')
                    ->label('Número de Serie')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->placeholder('Ej. SGH1234567'),
                TextInput::make('processor')
                    ->label('Procesador')
                    ->placeholder('Ej. Intel Core i5-12400T'),
                TextInput::make('ram')
                    ->label('Memoria RAM')
                    ->placeholder('Ej. 16 GB DDR4'),
                TextInput::make('storage')
                    ->label('Almacenamiento')
                    ->placeholder('Ej. 512 GB NVMe SSD'),
                TextInput::make('ip_address')
                    ->label('Dirección IP')
                    ->ip()
                    ->placeholder('Ej. 192.168.10.150'),
                TextInput::make('mac_address')
                    ->label('Dirección MAC')
                    ->regex('/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/')
                    ->placeholder('Ej. AA:BB:CC:DD:EE:FF'),
                Select::make('status')
                    ->label('Estado')
                    ->options([
                        'Disponible' => 'Disponible',
                        'Asignado' => 'Asignado',
                        'Mantenimiento' => 'Mantenimiento',
                        'Baja' => 'Dado de Baja',
                    ])
                    ->default('Disponible')
                    ->required(),
                DatePicker::make('purchase_date')
                    ->label('Fecha de Adquisición'),
                DatePicker::make('warranty_expiration')
                    ->label('Fin de Garantía'),
                Textarea::make('notes')
                    ->label('Observaciones')
                    ->maxLength(500)
                    ->columnSpanFull(),
            ]);
    }
}
