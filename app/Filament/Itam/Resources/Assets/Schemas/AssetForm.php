<?php

declare(strict_types=1);

namespace App\Filament\Itam\Resources\Assets\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class AssetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identificación y QR')
                    ->description('Códigos de registro y etiqueta QR del activo.')
                    ->compact()
                    ->columns(3)
                    ->schema([
                        TextInput::make('computer_code')
                            ->label('Código de TI / Informático')
                            ->required()
                            ->default(fn () => \App\Models\Asset::generateNextComputerCode())
                            ->unique(ignoreRecord: true)
                            ->placeholder('Ej. COD-TI-0001')
                            ->helperText('Autogenerado secuencialmente (ej. COD-TI-0001).'),
                        TextInput::make('asset_code')
                            ->label('Código Patrimonial')
                            ->unique(ignoreRecord: true)
                            ->placeholder('Ej. PAT-2026-0001'),
                        Select::make('asset_category_id')
                            ->label('Categoría')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live(),
                        Placeholder::make('qr_code')
                            ->label('Etiqueta QR de Escaneo')
                            ->columnSpanFull()
                            ->content(fn ($record) => $record && $record->computer_code ? new HtmlString('
                                <div class="flex items-center gap-6 p-4 bg-slate-50 dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl max-w-md shadow-sm">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode(url('/scan/activo/' . $record->computer_code)) . '" alt="QR Code" class="w-28 h-28 border border-slate-100 dark:border-zinc-800 rounded-lg bg-white p-1">
                                    <div>
                                        <span class="text-xs font-bold text-slate-800 dark:text-zinc-100 uppercase tracking-wider block">' . $record->computer_code . '</span>
                                        <span class="text-[11px] text-slate-500 dark:text-zinc-400 block mt-1">Este QR vincula directamente a la ficha móvil técnica del activo.</span>
                                        <a href="https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=' . urlencode(url('/scan/activo/' . $record->computer_code)) . '" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-600 dark:text-emerald-400 mt-2 hover:underline">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="display:inline-block; width:0.75rem; height:0.75rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                            Imprimir / Ampliar QR
                                        </a>
                                    </div>
                                </div>
                            ') : new HtmlString('<span class="text-xs text-slate-500 dark:text-zinc-400 font-light">Se autogenerará la etiqueta QR después de guardar el activo con su Código Informático.</span>')),
                    ]),

                Section::make('Detalles y Relaciones')
                    ->description('Información de fabricante y dependencias físicas.')
                    ->compact()
                    ->columns(3)
                    ->schema([
                        Select::make('brand_id')
                            ->label('Marca')
                            ->options(\App\Models\AssetBrand::pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->dehydrated(false)
                            ->afterStateHydrated(fn (Select $component, $record) => $component->state($record?->model?->asset_brand_id))
                            ->afterStateUpdated(fn (callable $set) => $set('asset_model_id', null)),
                        Select::make('asset_model_id')
                            ->label('Modelo')
                            ->options(fn (Get $get) => \App\Models\AssetModel::where('asset_brand_id', $get('brand_id'))->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(fn (Get $get) => !$get('brand_id')),
                        TextInput::make('serial_number')
                            ->label('Número de Serie')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->placeholder('Ej. SGH1234567'),
                        TextInput::make('color')
                            ->label('Color')
                            ->placeholder('Ej. Negro / Blanco / Gris'),
                        Select::make('estado')
                            ->label('Estado')
                            ->options([
                                'muy bueno' => 'MUY BUENO',
                                'bueno' => 'BUENO',
                                'regular' => 'REGULAR',
                                'malo' => 'MALO',
                            ])
                            ->default('bueno')
                            ->required(),
                        Select::make('parent_id')
                            ->label('Activo Principal (Padre)')
                            ->relationship(
                                'parent',
                                'computer_code',
                                fn ($query, $record) => $query
                                    ->with(['model.brand', 'category'])
                                    ->when($record, fn ($q) => $q->where('id', '!=', $record->id))
                            )
                            ->placeholder('Seleccione el equipo principal (ej. CPU)')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->select_option_label)
                            ->getSearchResultsUsing(function (string $search, $record) {
                                return \App\Models\Asset::query()
                                    ->when($record, fn ($q) => $q->where('id', '!=', $record->id))
                                    ->searchTerms($search)
                                    ->with(['model.brand', 'category'])
                                    ->limit(50)
                                    ->get()
                                    ->mapWithKeys(fn ($asset) => [$asset->id => $asset->select_option_label])
                                    ->toArray();
                            })
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),
                    ]),

                Group::make()
                    ->schema(fn (Get $get) => self::getDynamicFields(
                        $get('asset_category_id') ? (int) $get('asset_category_id') : null
                    )),

                Section::make('Garantía y Adquisición')
                    ->compact()
                    ->columns(2)
                    ->schema([
                        DatePicker::make('purchase_date')
                            ->label('Fecha de Adquisición'),
                        DatePicker::make('warranty_expiration')
                            ->label('Fin de Garantía'),
                    ]),

                Textarea::make('notes')
                    ->label('Observaciones')
                    ->maxLength(500)
                    ->columnSpanFull(),
            ]);
    }

    public static function getDynamicFields(?int $categoryId): array
    {
        if (! $categoryId) {
            return [];
        }

        $blocks = \App\Models\AssetBlock::with('characteristics')
            ->where('asset_category_id', $categoryId)
            ->orderBy('sort_order')
            ->get();

        $components = [];

        foreach ($blocks as $block) {
            $fields = [];
            foreach ($block->characteristics as $char) {
                $field = match ($char->type) {
                    'number' => TextInput::make('char_' . $char->id)->numeric(),
                    'date' => DatePicker::make('char_' . $char->id),
                    'select' => Select::make('char_' . $char->id)
                        ->options(self::parseOptions($char->options)),
                    'boolean' => Toggle::make('char_' . $char->id),
                    default => TextInput::make('char_' . $char->id),
                };

                $field->label(\Illuminate\Support\Str::title(mb_strtolower($char->name, 'UTF-8')));
                if ($char->is_required) {
                    $field->required();
                }

                $field->extraInputAttributes([
                    'class' => 'asset-dynamic-field-input',
                    'style' => 'text-transform: uppercase !important;',
                ]);

                $fields[] = $field;
            }

            if (count($fields) > 0) {
                $components[] = Section::make(\Illuminate\Support\Str::title(mb_strtolower($block->name, 'UTF-8')))
                    ->compact()
                    ->columns(3)
                    ->extraAttributes(['class' => 'asset-dynamic-block-section'])
                    ->schema($fields);
            }
        }

        return $components;
    }

    private static function parseOptions(?string $options): array
    {
        if (! $options) {
            return [];
        }

        $parsed = [];
        $parts = explode(',', $options);
        foreach ($parts as $part) {
            $trimmed = trim($part);
            $parsed[$trimmed] = $trimmed;
        }

        return $parsed;
    }
}
