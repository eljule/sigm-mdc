<?php

declare(strict_types=1);

namespace App\Filament\Itam\Resources\Software\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SoftwareForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre del Software')
                    ->required()
                    ->maxLength(150)
                    ->placeholder('Ej. Windows 11 Enterprise'),
                TextInput::make('version')
                    ->label('Versión')
                    ->maxLength(50)
                    ->placeholder('Ej. 23H2'),
                Select::make('license_type')
                    ->label('Tipo de Licencia')
                    ->options([
                        'oem' => 'OEM (De fábrica)',
                        'volumen' => 'Volumen (KMS/MAK)',
                        'suscripción' => 'Suscripción (Anual/Mensual)',
                        'libre' => 'Libre / Código Abierto',
                        'propietaria' => 'Propietaria Perpetua',
                    ])
                    ->formatStateUsing(fn ($state) => $state ? mb_strtolower((string) $state, 'UTF-8') : null)
                    ->native(false)
                    ->live()
                    ->required(),
                TextInput::make('max_activations')
                    ->label('Máx. Activaciones')
                    ->numeric()
                    ->default(1)
                    ->required(),
                DatePicker::make('expiration_date')
                    ->label('Fecha de Vencimiento')
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->placeholder('N/A (Sin vencimiento)')
                    ->helperText(fn ($get) => in_array(mb_strtolower((string) $get('license_type'), 'UTF-8'), ['oem', 'libre', 'propietaria'])
                        ? 'No aplica (N/A): Esta modalidad de licencia es perpetua.'
                        : (in_array(mb_strtolower((string) $get('license_type'), 'UTF-8'), ['suscripción', 'volumen'])
                            ? 'Fecha límite de vigencia de la suscripción o contrato.'
                            : null)),
                Textarea::make('license_key')
                    ->label('Clave de Licencia / Serial')
                    ->maxLength(500)
                    ->columnSpanFull(),
            ]);
    }
}
