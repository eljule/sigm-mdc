<?php

namespace App\Filament\Itam\Resources\ConsumableDeliveries;

use App\Filament\Itam\Resources\ConsumableDeliveries\Pages\ListConsumableDeliveries;
use App\Filament\Itam\Resources\ConsumableDeliveries\Tables\ConsumableDeliveriesTable;
use App\Models\ConsumableDelivery;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ConsumableDeliveryResource extends Resource
{
    protected static ?string $model = ConsumableDelivery::class;

    protected static ?string $pluralModelLabel = 'actas de entrega';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static string|\UnitEnum|null $navigationGroup = 'Catálogos TI';

    protected static ?string $navigationLabel = 'Actas de Entrega';

    protected static ?int $navigationSort = 15;

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('consultar-catalogos-ti') || auth()->user()?->can('gestionar-catalogos-ti');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return ConsumableDeliveriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListConsumableDeliveries::route('/'),
        ];
    }
}
