<?php

namespace App\Filament\Itam\Resources\AssetLoans\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AssetLoanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Préstamo / Reserva de Equipo')
                    ->description('Registre la reserva o préstamo temporal de un activo libre e independiente (ej: cañón multimedia, etc.).')
                    ->columns(2)
                    ->schema([
                        Select::make('asset_id')
                            ->label('Equipo / Activo a Prestar (Solo Equipos Libres Sin Asignación)')
                            ->options(function ($record) {
                                $query = \App\Models\Asset::availableForLoan();

                                if ($record && $record->asset_id) {
                                    $query->orWhere('id', $record->asset_id);
                                }

                                return $query->with(['model.brand', 'category'])
                                    ->get()
                                    ->mapWithKeys(function ($asset) {
                                        $label = ($asset->computer_code ?? $asset->asset_code ?? "ID: {$asset->id}") . " - " .
                                                 ($asset->category?->name ?? 'Equipo') . " " .
                                                 ($asset->model?->brand?->name ?? '') . " " .
                                                 ($asset->model?->name ?? '');
                                        return [$asset->id => $label];
                                    })
                                    ->toArray();
                            })
                            ->searchable()
                            ->required()
                            ->columnSpanFull(),

                        Select::make('office_id')
                            ->label('Oficina / Dependencia Solicitante')
                            ->options(fn () => \App\Models\Office::pluck('name', 'id')->toArray())
                            ->searchable()
                            ->nullable(),

                        TextInput::make('borrower_name')
                            ->label('Nombre del Solicitante / Responsable')
                            ->placeholder('Ej: Lic. Juan Pérez - Jefe de Imagen')
                            ->required(),

                        DateTimePicker::make('start_time')
                            ->label('Fecha y Hora de Inicio')
                            ->default(now())
                            ->required(),

                        DateTimePicker::make('end_time')
                            ->label('Fecha y Hora Fin Estimada')
                            ->default(now()->addHours(2))
                            ->required(),

                        Select::make('status')
                            ->label('Estado del Préstamo')
                            ->options([
                                'pending'   => '🟡 Pendiente (Reserva)',
                                'active'    => '🟢 En Préstamo Activo',
                                'returned'  => '✅ Devuelto a TI',
                                'overdue'   => '🔴 Vencido (No Devuelto)',
                                'cancelled' => '⚪ Cancelado',
                            ])
                            ->default('active')
                            ->required(),

                        DateTimePicker::make('returned_at')
                            ->label('Fecha Real de Devolución')
                            ->nullable()
                            ->helperText('Llenar sólo si el equipo ya fue devuelto.'),

                        Textarea::make('purpose')
                            ->label('Motivo / Propósito del Préstamo')
                            ->placeholder('Ej: Presentación de informe en Sesión de Concejo Municipal')
                            ->columnSpanFull(),

                        Textarea::make('notes')
                            ->label('Observaciones / Accesorios Incluidos')
                            ->placeholder('Ej: Incluye maletín, cable HDMI, control remoto y puntero láser')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
