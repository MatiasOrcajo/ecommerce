<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\TextInput;
use Filament\Schemas\Components\Select;
use Filament\Schemas\Components\Placeholder;
use Filament\Schemas\Components\Grid;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        Section::make('Informacion del Pedido')
                            ->schema([
                                Placeholder::make('code')
                                    ->label('Codigo')
                                    ->content(fn ($record) => $record?->code),
                                Placeholder::make('customer')
                                    ->label('Cliente')
                                    ->content(fn ($record) => $record?->customer?->name.' '.$record?->customer?->surname),
                                Placeholder::make('customer_email')
                                    ->label('Email')
                                    ->content(fn ($record) => $record?->customer?->email),
                                Placeholder::make('customer_phone')
                                    ->label('Telefono')
                                    ->content(fn ($record) => $record?->customer?->phone),
                                Placeholder::make('total_amount')
                                    ->label('Total')
                                    ->content(fn ($record) => '$'.($record?->total_amount ?? '0')),
                                Placeholder::make('shipping_address')
                                    ->label('Direccion de Envio')
                                    ->content(fn ($record) => $record?->shipping_address)
                                    ->columnSpanFull(),
                                Placeholder::make('shipping_method')
                                    ->label('Modo de Envio')
                                    ->content(fn ($record) => $record?->shipping_method),
                                Placeholder::make('shipping_cost')
                                    ->label('Costo de Envio')
                                    ->content(fn ($record) => '$'.($record?->shipping_cost ?? '0')),
                                Placeholder::make('order_date')
                                    ->label('Fecha')
                                    ->content(fn ($record) => $record?->order_date?->format('d/m/Y H:i')),
                            ])
                            ->columns(2)
                            ->columnSpan(1),
                        Section::make('Actualizar Estado')
                            ->schema([
                                Select::make('status')
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
                                    ])
                                    ->required(),
                                TextInput::make('observations')
                                    ->label('Observaciones'),
                            ])
                            ->columnSpan(1),
                    ]),
            ]);
    }
}
