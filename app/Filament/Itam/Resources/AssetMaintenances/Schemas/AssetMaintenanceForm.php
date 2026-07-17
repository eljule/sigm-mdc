<?php

declare(strict_types=1);

namespace App\Filament\Itam\Resources\AssetMaintenances\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AssetMaintenanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('asset_id')
                    ->relationship('asset', 'computer_code')
                    ->label('Activo a Mantenimiento')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "[{$record->computer_code}] " . ($record->model?->brand?->name ?? '') . " " . ($record->model?->name ?? '') . ($record->asset_code ? " (Patrimonial: {$record->asset_code})" : ''))
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('type')
                    ->label('Tipo de Mantenimiento')
                    ->options([
                        'Preventivo' => 'Preventivo',
                        'Correctivo' => 'Correctivo',
                    ])
                    ->required(),
                DatePicker::make('scheduled_date')
                    ->label('Fecha Programada')
                    ->default(now())
                    ->required(),
                DatePicker::make('performed_date')
                    ->label('Fecha Realizado'),
                TextInput::make('cost')
                    ->label('Costo (S/.)')
                    ->numeric()
                    ->default(0.00),
                Textarea::make('description')
                    ->label('Descripción / Trabajo Solicitado')
                    ->required()
                    ->maxLength(1000)
                    ->columnSpanFull(),
                Textarea::make('technician_notes')
                    ->label('Notas del Técnico / Diagnóstico')
                    ->maxLength(1000)
                    ->columnSpanFull(),
            ]);
    }
}
