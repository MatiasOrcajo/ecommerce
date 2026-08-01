<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\HtmlString;

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
                ToggleColumn::make('is_packaged')
                    ->label('Empaquetado')
                    ->onColor('success')
                    ->offColor('gray')
                    ->afterStateUpdated(function ($record, $state) {
                        $record->packaged_at = $state ? now() : null;
                        $record->save();
                    }),
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
                TernaryFilter::make('is_packaged')
                    ->label('Empaquetado'),
            ])
            ->recordActions([
                Action::make('viewItems')
                    ->label('Ver items')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->modalWidth('2xl')
                    ->modalHeading(fn ($record) => 'Items del pedido #'.$record->code)
                    ->modalContent(fn ($record) => new HtmlString(view('filament.orders.items-modal', [
                        'products' => $record->products,
                    ])->render())),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('markPackaged')
                        ->label('Marcar empaquetado')
                        ->icon('heroicon-o-archive-box')
                        ->color('success')
                        ->action(function (Collection $records) {
                            $records->each(function ($record) {
                                $record->update([
                                    'is_packaged' => true,
                                    'packaged_at' => now(),
                                ]);
                            });
                        })
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
