<?php

namespace App\Filament\Helpdesk\Resources\KnowledgeBases;

use App\Filament\Helpdesk\Resources\KnowledgeBases\Pages\CreateKnowledgeBase;
use App\Filament\Helpdesk\Resources\KnowledgeBases\Pages\EditKnowledgeBase;
use App\Filament\Helpdesk\Resources\KnowledgeBases\Pages\ListKnowledgeBases;
use App\Filament\Helpdesk\Resources\KnowledgeBases\Schemas\KnowledgeBaseForm;
use App\Filament\Helpdesk\Resources\KnowledgeBases\Tables\KnowledgeBasesTable;
use App\Models\KnowledgeBase;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KnowledgeBaseResource extends Resource
{
    protected static ?string $model = KnowledgeBase::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return KnowledgeBaseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KnowledgeBasesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKnowledgeBases::route('/'),
            'create' => CreateKnowledgeBase::route('/create'),
            'edit' => EditKnowledgeBase::route('/{record}/edit'),
        ];
    }
}
