<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Personals\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PersonalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('document_number')
                    ->label('N° DNI / Doc.')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('full_name')
                    ->label('Nombres y Apellidos')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('office.name')
                    ->label('Oficina / Dependencia')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('laborCondition.name')
                    ->label('Condición Laboral')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                TextColumn::make('position')
                    ->label('Cargo')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('phone')
                    ->label('Teléfono')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active')
                    ->label('Estado')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                SelectFilter::make('office_id')
                    ->relationship('office', 'name')
                    ->label('Filtrar por Oficina'),
                SelectFilter::make('labor_condition_id')
                    ->relationship('laborCondition', 'name')
                    ->label('Filtrar por Condición Laboral'),
            ]);
    }
}
