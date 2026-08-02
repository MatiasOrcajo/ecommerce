<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                ImageColumn::make('pictures_first_path')
                    ->label('Imagen')
                    ->getStateUsing(fn ($record) => $record->productPictures->first()?->path),
                TextColumn::make('name')
                    ->label('Nombre')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label('Categoria')
                    ->sortable(),
                TextColumn::make('price')
                    ->label('Precio')
                    ->money('ARS')
                    ->sortable(),
                TextColumn::make('discount')
                    ->label('Descuento %')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('sales')
                    ->label('Ventas')
                    ->sortable(),
                TextColumn::make('total_stock')
                    ->label('Stock')
                    ->getStateUsing(fn ($record) => $record->variants()->sum('stock'))
                    ->sortable(query: fn ($query, $direction) => $query->withSum('variants as total_stock_sum', 'stock')->orderBy('total_stock_sum', $direction)),
                TextColumn::make('visits')
                    ->label('Visitas')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('featured')
                    ->label('Destacado')
                    ->boolean(),
                IconColumn::make('visible')
                    ->label('Visible')
                    ->boolean(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->label('Categoria'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
