<?php

namespace App\Filament\Itam\Resources\AssetLoans;

use App\Filament\Itam\Resources\AssetLoans\Pages\CreateAssetLoan;
use App\Filament\Itam\Resources\AssetLoans\Pages\EditAssetLoan;
use App\Filament\Itam\Resources\AssetLoans\Pages\ListAssetLoans;
use App\Filament\Itam\Resources\AssetLoans\Schemas\AssetLoanForm;
use App\Filament\Itam\Resources\AssetLoans\Tables\AssetLoansTable;
use App\Models\AssetLoan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class AssetLoanResource extends Resource
{
    protected static ?string $model = AssetLoan::class;

    protected static ?string $pluralModelLabel = 'préstamos y reservas';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static string|\UnitEnum|null $navigationGroup = 'Gestión TI';

    protected static ?string $navigationLabel = 'Préstamos y Reservas';

    protected static ?int $navigationSort = 12;

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        return $user->can('consultar-prestamos') || 
               $user->can('consultar-activos') || 
               $user->can('gestionar-inventario');
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        return $user->can('insertar-prestamos') || 
               $user->can('insertar-activos') || 
               $user->can('gestionar-inventario');
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        return $user->can('modificar-prestamos') || 
               $user->can('modificar-activos') || 
               $user->can('gestionar-inventario');
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        return $user->can('eliminar-prestamos') || 
               $user->can('eliminar-activos') || 
               $user->can('gestionar-inventario');
    }

    public static function form(Schema $schema): Schema
    {
        return AssetLoanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AssetLoansTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListAssetLoans::route('/'),
            'create' => CreateAssetLoan::route('/create'),
            'edit'   => EditAssetLoan::route('/{record}/edit'),
        ];
    }
}
