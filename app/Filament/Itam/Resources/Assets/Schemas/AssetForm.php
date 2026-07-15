<?php

declare(strict_types=1);

namespace App\Filament\Itam\Resources\Assets\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class AssetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identificación y QR')
                    ->description('Códigos de registro y etiqueta QR del activo.')
                    ->compact()
                    ->columns(3)
                    ->schema([
                        TextInput::make('asset_code')
                            ->label('Código Patrimonial')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->placeholder('Ej. PAT-2026-0001'),
                        TextInput::make('computer_code')
                            ->label('Código de TI / Informático')
                            ->unique(ignoreRecord: true)
                            ->placeholder('Ej. COD-TI-0001'),
                        Select::make('category')
                            ->label('Categoría')
                            ->options([
                                'Servidor' => 'Servidor',
                                'PC' => 'PC de Escritorio',
                                'Laptop' => 'Laptop / Portátil',
                                'Teclado' => 'Teclado',
                                'Mouse' => 'Mouse',
                                'Monitor' => 'Monitor',
                                'Impresora' => 'Impresora / Multifuncional',
                                'Switch' => 'Switch de Red',
                                'Router' => 'Router',
                                'Access Point' => 'Access Point (Wi-Fi)',
                                'Firewall' => 'Firewall',
                                'Otros' => 'Otros',
                            ])
                            ->required()
                            ->searchable(),
                        Placeholder::make('qr_code')
                            ->label('Etiqueta QR de Escaneo')
                            ->columnSpanFull()
                            ->content(fn ($record) => $record && $record->computer_code ? new HtmlString('
                                <div class="flex items-center gap-6 p-4 bg-slate-50 dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl max-w-md shadow-sm">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode(url('/scan/activo/' . $record->computer_code)) . '" alt="QR Code" class="w-28 h-28 border border-slate-100 dark:border-zinc-800 rounded-lg bg-white p-1">
                                    <div>
                                        <span class="text-xs font-bold text-slate-800 dark:text-zinc-100 uppercase tracking-wider block">' . $record->computer_code . '</span>
                                        <span class="text-[11px] text-slate-500 dark:text-zinc-400 block mt-1">Este QR vincula directamente a la ficha móvil técnica del activo.</span>
                                        <a href="https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=' . urlencode(url('/scan/activo/' . $record->computer_code)) . '" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-600 dark:text-emerald-400 mt-2 hover:underline">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="display:inline-block; width:0.75rem; height:0.75rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                            Imprimir / Ampliar QR
                                        </a>
                                    </div>
                                </div>
                            ') : new HtmlString('<span class="text-xs text-slate-500 dark:text-zinc-400 font-light">Se autogenerará la etiqueta QR después de guardar el activo con su Código Informático.</span>')),
                    ]),

                Section::make('Detalles y Relaciones')
                    ->description('Información de fabricante y dependencias físicas.')
                    ->compact()
                    ->columns(3)
                    ->schema([
                        TextInput::make('brand')
                            ->label('Marca')
                            ->required()
                            ->placeholder('Ej. HP, Dell, Lenovo'),
                        TextInput::make('model')
                            ->label('Modelo')
                            ->required()
                            ->placeholder('Ej. ProDesk 400'),
                        TextInput::make('serial_number')
                            ->label('Número de Serie')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->placeholder('Ej. SGH1234567'),
                        Select::make('parent_id')
                            ->label('Activo Principal (Padre)')
                            ->relationship('parent', 'model')
                            ->placeholder('Seleccione el equipo principal (ej. CPU)')
                            ->searchable()
                            ->preload()
                            ->getOptionLabelFromRecordUsing(fn ($record) => "[{$record->computer_code}] {$record->brand} {$record->model} ({$record->category})")
                            ->columnSpanFull(),
                    ]),

                Section::make('Especificaciones Técnicas')
                    ->description('Características de hardware interno del equipo (si aplica).')
                    ->compact()
                    ->columns(3)
                    ->schema([
                        TextInput::make('processor')
                            ->label('Procesador')
                            ->placeholder('Ej. Intel Core i5-12500'),
                        TextInput::make('ram')
                            ->label('Memoria RAM')
                            ->placeholder('Ej. 16 GB DDR4'),
                        TextInput::make('storage')
                            ->label('Almacenamiento')
                            ->placeholder('Ej. 512 GB SSD'),
                    ]),

                Section::make('Red y Estado')
                    ->description('Parámetros de red y estado operativo del activo.')
                    ->compact()
                    ->columns(3)
                    ->schema([
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
                    ]),

                Section::make('Garantía y Adquisición')
                    ->compact()
                    ->columns(2)
                    ->schema([
                        DatePicker::make('purchase_date')
                            ->label('Fecha de Adquisición'),
                        DatePicker::make('warranty_expiration')
                            ->label('Fin de Garantía'),
                    ]),

                Textarea::make('notes')
                    ->label('Observaciones')
                    ->maxLength(500)
                    ->columnSpanFull(),
            ]);
    }
}
