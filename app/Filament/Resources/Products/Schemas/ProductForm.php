<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Select;
use Filament\Schemas\Components\Textarea;
use Filament\Schemas\Components\TextInput;
use Filament\Schemas\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        Section::make('Informacion Basica')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nombre')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->unique(ignoreRecord: true),
                                Select::make('category_id')
                                    ->label('Categoria')
                                    ->relationship('category', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                TextInput::make('price')
                                    ->label('Precio')
                                    ->numeric()
                                    ->required()
                                    ->prefix('$'),
                                TextInput::make('discount')
                                    ->label('Descuento (%)')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(100),
                                TextInput::make('discount_until')
                                    ->label('Descuento valido hasta')
                                    ->type('date'),
                                Toggle::make('featured')
                                    ->label('Destacado'),
                                Toggle::make('visible')
                                    ->label('Visible')
                                    ->default(true),
                            ])
                            ->columns(2)
                            ->columnSpan(2),
                        Section::make('SEO y Media')
                            ->schema([
                                TextInput::make('code')
                                    ->label('Codigo')
                                    ->maxLength(255),
                                TextInput::make('youtube_link')
                                    ->label('Link de YouTube')
                                    ->url(),
                                TextInput::make('color')
                                    ->label('Color')
                                    ->type('color'),
                            ])
                            ->columnSpan(1),
                    ]),
                Section::make('Descripciones')
                    ->schema([
                        Textarea::make('description')
                            ->label('Descripcion')
                            ->rows(4)
                            ->columnSpanFull(),
                        Textarea::make('sizes_description')
                            ->label('Descripcion de Talles')
                            ->rows(3),
                        Textarea::make('model_reference')
                            ->label('Referencia de Modelo')
                            ->rows(3),
                    ])
                    ->columns(3),
            ]);
    }
}
