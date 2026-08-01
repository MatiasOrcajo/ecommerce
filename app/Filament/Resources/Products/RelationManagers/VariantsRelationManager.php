<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ColorPicker;
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
                TextInput::make('size')
                    ->label('Talle')
                    ->required()
                    ->maxLength(255),
                ColorPicker::make('color')
                    ->label('Color')
                    ->required(),
                TextInput::make('color_name')
                    ->label('Nombre del Color')
                    ->required()
                    ->maxLength(255),
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
            ->recordTitleAttribute('size')
            ->columns([
                TextColumn::make('size')
                    ->label('Talle')
                    ->searchable()
                    ->sortable(),
                ColorColumn::make('color')
                    ->label('Color'),
                TextColumn::make('color_name')
                    ->label('Nombre del Color')
                    ->searchable(),
                TextColumn::make('stock')
                    ->label('Stock')
                    ->sortable(),
            ])
            ->defaultSort('size')
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
