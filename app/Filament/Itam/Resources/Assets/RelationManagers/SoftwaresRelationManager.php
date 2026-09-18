<?php

declare(strict_types=1);

namespace App\Filament\Itam\Resources\Assets\RelationManagers;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SoftwaresRelationManager extends RelationManager
{
    protected static string $relationship = 'softwares';

    protected static ?string $title = 'Software Autorizado / Instalado';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre del Software')
                    ->disabled(),
                TextInput::make('version')
                    ->label('Versión')
                    ->disabled(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitle(function ($record): string {
                $licenseType = match (mb_strtolower((string) $record->license_type, 'UTF-8')) {
                    'oem' => 'OEM',
                    'volumen' => 'Volumen',
                    'suscripción' => 'Suscripción',
                    'libre' => 'Libre',
                    'propietaria' => 'Propietaria',
                    default => $record->license_type ? ucfirst($record->license_type) : '',
                };

                $version = filled($record->version) ? " (v{$record->version})" : '';
                $license = filled($licenseType) ? " - [{$licenseType}]" : '';

                return "{$record->name}{$version}{$license}";
            })
            ->columns([
                TextColumn::make('name')
                    ->label('Software')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('version')
                    ->label('Versión')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('license_type')
                    ->label('Tipo de Licencia')
                    ->formatStateUsing(fn (?string $state): string => match (mb_strtolower((string) $state, 'UTF-8')) {
                        'oem' => 'OEM',
                        'volumen' => 'Volumen',
                        'suscripción' => 'Suscripción',
                        'libre' => 'Libre',
                        'propietaria' => 'Propietaria',
                        default => (string) $state,
                    })
                    ->sortable(),
                TextColumn::make('pivot.installed_at')
                    ->label('Fecha de Instalación')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Asociar/Instalar Software')
                    ->modalHeading('Asociar licencia de software a este activo')
                    ->recordSelectSearchColumns(['name', 'version', 'license_type'])
                    ->preloadRecordSelect()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        DatePicker::make('installed_at')
                            ->label('Fecha de Instalación')
                            ->default(now())
                            ->native(false)
                            ->required(),
                    ]),
            ])
            ->actions([
                DetachAction::make()
                    ->label('Desasociar')
                    ->modalHeading('Desasociar/Desinstalar software de este activo'),
            ])
            ->bulkActions([
                DetachBulkAction::make(),
            ]);
    }
}
