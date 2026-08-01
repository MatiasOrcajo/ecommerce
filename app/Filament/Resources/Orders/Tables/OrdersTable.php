<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('code')
                    ->label('Codigo')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('customer.name')
                    ->label('Cliente')
                    ->formatStateUsing(fn ($record) => $record->customer?->name.' '.$record->customer?->surname)
                    ->searchable(['customer.name', 'customer.surname']),
                TextColumn::make('products')
                    ->label('Pedido')
                    ->formatStateUsing(function ($record) {
                        return $record->products->map(function ($orderProduct) {
                            return $orderProduct->quantity.'x '
                                .$orderProduct->productVariant?->product?->name.' '
                                .'Talle '.$orderProduct->productVariant?->size.' '
                                .$orderProduct->productVariant?->color_name;
                        })->implode(' | ');
                    })
                    ->wrap(),
                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('ARS')
                    ->sortable(),
                TextColumn::make('shipping_address')
                    ->label('Direccion')
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('observations')
                    ->label('Observaciones')
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('shipping_method')
                    ->label('Modo de Envio')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pago recibido', 'Retiro realizado', 'Envio realizado' => 'success',
                        'Pago fallido', 'Expirado' => 'danger',
                        'Pago pendiente de aprobacion', 'No pago' => 'warning',
                        'En proceso', 'Esperando que el cliente retire' => 'info',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'Pago recibido' => 'Pago recibido',
                        'Pago fallido' => 'Pago fallido',
                        'Pago pendiente de aprobacion' => 'Pago pendiente de aprobacion',
                        'No pago' => 'No pago',
                        'En proceso' => 'En proceso',
                        'Envio realizado' => 'Envio realizado',
                        'Esperando que el cliente retire' => 'Esperando que el cliente retire',
                        'Retiro realizado' => 'Retiro realizado',
                        'Expirado' => 'Expirado',
                    ]),
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
