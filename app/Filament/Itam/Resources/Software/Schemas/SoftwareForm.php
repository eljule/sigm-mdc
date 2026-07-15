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
                        'OEM' => 'OEM (De fábrica)',
                        'Volumen' => 'Volumen (KMS/MAK)',
                        'Suscripción' => 'Suscripción (Anual/Mensual)',
                        'Libre' => 'Libre / Código Abierto',
                        'Propietaria' => 'Propietaria Perpetua',
                    ])
                    ->required(),
                TextInput::make('max_activations')
                    ->label('Máx. Activaciones')
                    ->numeric()
                    ->default(1)
                    ->required(),
                DatePicker::make('expiration_date')
                    ->label('Fecha de Vencimiento'),
                Textarea::make('license_key')
                    ->label('Clave de Licencia / Serial')
                    ->maxLength(500)
                    ->columnSpanFull(),
            ]);
    }
}
