<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PicturesRelationManager extends RelationManager
{
    protected static string $relationship = 'pictures';

    protected static ?string $title = 'Imagenes';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('path')
                    ->label('Imagen')
                    ->image()
                    ->directory('images')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('order')
                    ->label('Orden')
                    ->numeric()
                    ->default(1),
                Select::make('product_variant_id')
                    ->label('Variante')
                    ->relationship('variant', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->size.' - '.$record->color_name)
                    ->nullable()
                    ->searchable()
                    ->preload(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('path')
            ->columns([
                ImageColumn::make('path')
                    ->label('Imagen'),
                TextColumn::make('order')
                    ->label('Orden')
                    ->sortable(),
                TextColumn::make('variant.size')
                    ->label('Talle')
                    ->formatStateUsing(fn ($record) => $record->variant?->size.' - '.$record->variant?->color_name),
            ])
            ->defaultSort('order')
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
