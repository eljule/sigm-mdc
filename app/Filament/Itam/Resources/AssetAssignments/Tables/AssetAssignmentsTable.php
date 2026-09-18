<?php

declare(strict_types=1);

namespace App\Filament\Itam\Resources\AssetAssignments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class AssetAssignmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                $query->leftJoin('assets as a', 'asset_assignments.asset_id', '=', 'a.id')
                    ->leftJoin('assets as p', 'a.parent_id', '=', 'p.id')
                    ->select('asset_assignments.*')
                    ->with([
                        'asset.model.brand',
                        'asset.category',
                        'asset.parent',
                        'user',
                        'office',
                    ]);
            })
            ->defaultSort('asset.computer_code', 'asc')
            ->columns([
                TextColumn::make('asset.computer_code')
                    ->label('Cód. Informático')
                    ->html()
                    ->formatStateUsing(function ($state, $record) {
                        if ($record->asset?->parent_id) {
                            return '<span class="inline-flex items-center gap-1.5"><span class="text-gray-400 dark:text-gray-500 font-bold">↳</span><span class="font-mono font-bold">' . e($state) . '</span></span>';
                        }
                        return '<span class="font-mono font-bold">' . e($state) . '</span>';
                    })
                    ->extraCellAttributes(function ($record) {
                        if ($record->asset?->parent_id) {
                            return [
                                'style' => 'padding-left: 2.25rem !important;',
                            ];
                        }
                        return [];
                    })
                    ->description(function ($record) {
                        $cat = $record->asset?->category?->name ? strtoupper($record->asset->category->name) : '';
                        $parentCode = $record->asset?->parent?->computer_code;
                        if ($parentCode) {
                            return ($cat ? "{$cat} • " : '') . "VINCULADO A {$parentCode}";
                        }
                        return $cat ?: null;
                    })
                    ->searchable()
                    ->sortable(query: function ($query, string $direction) {
                        $query->orderByRaw("COALESCE(p.computer_code, a.computer_code) {$direction}")
                              ->orderByRaw('CASE WHEN a.parent_id IS NULL THEN 0 ELSE 1 END ASC')
                              ->orderBy('a.computer_code', 'asc');
                    })
                    ->toggleable(),
                TextColumn::make('asset.asset_code')
                    ->label('Cód. Patrimonial')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('asset.category.name')
                    ->label('Categoría')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => strtoupper((string) $state))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('asset_brand')
                    ->label('Marca')
                    ->state(fn ($record) => $record->asset?->model?->brand?->name)
                    ->placeholder('-')
                    ->toggleable(),
                TextColumn::make('asset_model')
                    ->label('Modelo')
                    ->state(fn ($record) => $record->asset?->model?->name)
                    ->placeholder('-')
                    ->toggleable(),
                TextColumn::make('user.name')
                    ->label('Asignado a')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('office.name')
                    ->label('Oficina')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('assigned_at')
                    ->label('Fecha Entrega')
                    ->dateTime()
                    ->sortable(query: function ($query, string $direction) {
                        $query->orderBy('asset_assignments.assigned_at', $direction)
                              ->orderByRaw('CASE WHEN a.parent_id IS NULL THEN 0 ELSE 1 END ASC')
                              ->orderBy('a.computer_code', 'asc');
                    })
                    ->toggleable(),
                TextColumn::make('returned_at')
                    ->label('Fecha Devolución')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Activo actualmente')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('office_id')
                    ->label('Oficina')
                    ->relationship('office', 'name')
                    ->preload(),
                SelectFilter::make('user_id')
                    ->label('Empleado')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                TernaryFilter::make('is_active')
                    ->label('Estado de Asignación')
                    ->placeholder('Todas')
                    ->trueLabel('Activa (No devuelta)')
                    ->falseLabel('Devuelta')
                    ->queries(
                        true: fn ($query) => $query->whereNull('asset_assignments.returned_at'),
                        false: fn ($query) => $query->whereNotNull('asset_assignments.returned_at'),
                    )
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('print_ficha')
                    ->label('Acta de Asignación')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn ($record) => route('fichas.asignacion', $record->id))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
