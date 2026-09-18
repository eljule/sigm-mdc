<?php

declare(strict_types=1);

namespace App\Filament\Helpdesk\Resources\Tickets\RelationManagers;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

class MaintenancesRelationManager extends RelationManager
{
    protected static string $relationship = 'maintenances';

    protected static ?string $title = 'Mantenimientos Correctivos / Preventivos Asociados';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('asset_id')
                    ->label('Activo Intervenido')
                    ->relationship(
                        'asset',
                        'computer_code',
                        fn ($query) => $query->with(['model.brand', 'category'])
                    )
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->select_option_label)
                    ->getSearchResultsUsing(function (string $search) {
                        return \App\Models\Asset::query()
                            ->searchTerms($search)
                            ->with(['model.brand', 'category'])
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(fn ($asset) => [$asset->id => $asset->select_option_label])
                            ->toArray();
                    })
                    ->default(fn ($livewire) => $livewire->ownerRecord->affected_asset_id)
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('type')
                    ->label('Tipo de Mantenimiento')
                    ->options([
                        'Correctivo' => 'Correctivo',
                        'Preventivo' => 'Preventivo',
                    ])
                    ->default('Correctivo')
                    ->required(),
                DatePicker::make('scheduled_date')
                    ->label('Fecha Programada')
                    ->default(now())
                    ->required(),
                DatePicker::make('performed_date')
                    ->label('Fecha de Realización')
                    ->default(now()),
                TextInput::make('cost')
                    ->label('Costo (S/.)')
                    ->numeric()
                    ->default(0.00)
                    ->required(),
                Textarea::make('description')
                    ->label('Descripción de la falla / tareas')
                    ->default(fn ($livewire) => "Mantenimiento correctivo para solucionar Ticket: " . $livewire->ownerRecord->ticket_code)
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('technician_notes')
                    ->label('Notas del Técnico')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('asset.computer_code')
                    ->label('Activo')
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Correctivo' => 'danger',
                        'Preventivo' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('performed_date')
                    ->label('F. Realizado')
                    ->date()
                    ->placeholder('Pendiente'),
                TextColumn::make('cost')
                    ->label('Costo (S/.)')
                    ->money('PEN'),
                TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(50),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Registrar Mantenimiento')
                    ->modalHeading('Registrar mantenimiento correctivo/preventivo'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
