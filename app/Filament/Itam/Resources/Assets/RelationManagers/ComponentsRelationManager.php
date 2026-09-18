<?php

declare(strict_types=1);

namespace App\Filament\Itam\Resources\Assets\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Actions\AssociateAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ComponentsRelationManager extends RelationManager
{
    protected static string $relationship = 'components';

    protected static ?string $inverseRelationship = 'parent';

    protected static ?string $title = 'Componentes / Periféricos Asociados';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('computer_code')
                    ->label('Código Informático')
                    ->disabled(),
                TextInput::make('model.brand.name')
                    ->label('Marca')
                    ->disabled(),
                TextInput::make('model.name')
                    ->label('Modelo')
                    ->disabled(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->inverseRelationship('parent')
            ->recordTitle(fn ($record) => "[{$record->computer_code}] " . ($record->model?->brand?->name ?? '') . " " . ($record->model?->name ?? '') . " (" . ($record->category->name ?? '') . ")")
            ->columns([
                TextColumn::make('category.name')
                    ->label('Categoría'),
                TextColumn::make('computer_code')
                    ->label('Cód. Informático'),
                TextColumn::make('asset_code')
                    ->label('Cód. Patrimonial'),
                TextColumn::make('model.brand.name')
                    ->label('Marca'),
                TextColumn::make('model.name')
                    ->label('Modelo'),
                TextColumn::make('serial_number')
                    ->label('S/N'),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match (strtolower((string) $state)) {
                        'disponible' => 'DISPONIBLE',
                        'asignado' => 'ASIGNADO',
                        'mantenimiento' => 'MANTENIMIENTO',
                        'en evaluación', 'en evaluacion' => 'EN EVALUACIÓN',
                        'baja' => 'BAJA',
                        default => strtoupper((string) $state),
                    })
                    ->color(fn (?string $state): string => match (strtolower((string) $state)) {
                        'disponible' => 'success',
                        'asignado' => 'info',
                        'mantenimiento' => 'warning',
                        'en evaluación', 'en evaluacion' => 'warning',
                        'baja' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn (?string $state): string => match (strtolower((string) $state)) {
                        'disponible' => 'heroicon-o-check-circle',
                        'asignado' => 'heroicon-o-user',
                        'mantenimiento' => 'heroicon-o-wrench-screwdriver',
                        'en evaluación', 'en evaluacion' => 'heroicon-o-magnifying-glass',
                        'baja' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AssociateAction::make()
                    ->label('Asociar Componente Existente')
                    ->modalHeading('Asociar componente a este activo principal')
                    ->after(function ($record, $livewire) {
                        $parent = $livewire->getOwnerRecord();
                        if ($parent && in_array(strtolower($parent->status ?? ''), ['asignado', 'Asignado'])) {
                            $record->update(['status' => 'asignado']);
                            $parentAssignment = \App\Models\AssetAssignment::where('asset_id', $parent->id)
                                ->whereNull('returned_at')
                                ->first();
                            if ($parentAssignment) {
                                \App\Models\AssetAssignment::firstOrCreate(
                                    ['asset_id' => $record->id, 'returned_at' => null],
                                    [
                                        'user_id'     => $parentAssignment->user_id,
                                        'office_id'   => $parentAssignment->office_id,
                                        'assigned_at' => $parentAssignment->assigned_at,
                                        'notes'       => "Componente vinculado a equipo principal {$parent->computer_code}",
                                    ]
                                );
                            }
                        }
                    }),
            ])
            ->actions([
                DissociateAction::make()
                    ->label('Desasociar')
                    ->modalHeading('Desasociar de este activo principal')
                    ->after(function ($record) {
                        \App\Models\AssetAssignment::where('asset_id', $record->id)
                            ->whereNull('returned_at')
                            ->update(['returned_at' => now()]);
                        if (! in_array(strtolower($record->status ?? ''), ['baja', 'mantenimiento', 'en evaluación', 'en evaluacion'])) {
                            $record->update(['status' => 'disponible']);
                        }
                    }),
                Action::make('print_ficha')
                    ->label('Ficha')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn ($record) => route('fichas.componente', $record->id))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                DissociateBulkAction::make(),
            ]);
    }
}
