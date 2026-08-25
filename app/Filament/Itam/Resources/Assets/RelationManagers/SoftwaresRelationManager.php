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
            ->recordTitle(fn ($record) => "{$record->name} (v{$record->version})")
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
                AttachAction::make()->label('Vincular Software')
                    ->label('Asociar/Instalar Software')
                    ->modalHeading('Asociar licencia de software a este activo')
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        DatePicker::make('installed_at')
                            ->label('Fecha de Instalación')
                            ->default(now())
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
