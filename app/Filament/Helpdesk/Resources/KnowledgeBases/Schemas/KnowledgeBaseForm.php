<?php

declare(strict_types=1);

namespace App\Filament\Helpdesk\Resources\KnowledgeBases\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class KnowledgeBaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Título de la Solución/Falla')
                    ->required()
                    ->maxLength(200)
                    ->placeholder('Ej. Impresora imprime borroso o con líneas'),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Categoría Relacionada')
                    ->required()
                    ->preload(),
                Textarea::make('symptoms')
                    ->label('Síntomas o Falla Reportada')
                    ->required()
                    ->maxLength(1000)
                    ->placeholder('Describa el comportamiento de la falla'),
                Textarea::make('solution')
                    ->label('Solución Aplicada')
                    ->required()
                    ->maxLength(2000)
                    ->placeholder('Paso a paso para resolver la falla'),
                Toggle::make('is_published')
                    ->label('Publicado')
                    ->default(true),
                Select::make('author_id')
                    ->relationship('author', 'name')
                    ->label('Autor')
                    ->default(auth()->id())
                    ->disabled()
                    ->dehydrated(),
            ]);
    }
}
