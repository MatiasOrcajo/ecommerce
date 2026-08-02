<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';

    protected static ?string $title = 'Talles y Colores';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('size_id')
                    ->label('Talle')
                    ->relationship('size', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('color_id')
                    ->label('Color')
                    ->relationship('color', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('sku')
                    ->label('SKU')
                    ->maxLength(50)
                    ->unique(ignoreRecord: true),
                TextInput::make('stock')
                    ->label('Stock')
                    ->required()
                    ->numeric()
                    ->minValue(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('sku')
            ->columns([
                TextColumn::make('size.name')
                    ->label('Talle')
                    ->searchable()
                    ->sortable(),
                ColorColumn::make('color.hex')
                    ->label('Color'),
                TextColumn::make('color.name')
                    ->label('Nombre del Color')
                    ->searchable(),
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                TextColumn::make('stock')
                    ->label('Stock')
                    ->sortable(),
            ])
            ->defaultSort('size_id')
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
