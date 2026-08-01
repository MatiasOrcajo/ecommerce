<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(['default' => 1, 'lg' => 3])
                    ->schema([
                        // COLUMNA IZQUIERDA: Detalles del Pedido
                        Group::make()
                            ->schema([
                                Section::make('Detalles del Pedido')
                                    ->description('Información de la orden')
                                    ->icon('heroicon-o-document-text')
                                    ->columns(['default' => 1, 'sm' => 2, 'xl' => 3]) // 3 columnas internamente
                                    ->schema([
                                        Placeholder::make('code')
                                            ->label('Código')
                                            ->content(fn ($record) => new HtmlString('<span class="font-medium text-gray-950 dark:text-white">'.($record?->code ?? '—').'</span>')),

                                        Placeholder::make('order_date')
                                            ->label('Fecha del pedido')
                                            ->content(fn ($record) => $record?->created_at
                                                ? Carbon::parse($record->created_at)->setTimezone('America/Argentina/Buenos_Aires')->format('d/m/Y H:i')
                                                : '—'),

                                        Placeholder::make('expiration_date')
                                            ->label('Vencimiento')
                                            ->content(fn ($record) => $record?->expiration_date
                                                ? Carbon::parse($record->expiration_date)->setTimezone('America/Argentina/Buenos_Aires')->format('d/m/Y H:i')
                                                : '—'),

                                        Placeholder::make('payment_method')
                                            ->label('Método de pago')
                                            ->content(fn ($record) => $record?->payment_method ?: '—'),

                                        Placeholder::make('preference_id')
                                            ->label('Preference ID')
                                            ->content(fn ($record) => $record?->preference_id ?: '—'),

                                        Placeholder::make('shipping_method')
                                            ->label('Modo de envío')
                                            ->content(fn ($record) => $record?->shipping_method ?: '—'),

                                        Placeholder::make('shipping_cost')
                                            ->label('Costo de envío')
                                            ->content(fn ($record) => $record?->shipping_cost
                                                ? '$'.number_format($record->shipping_cost, 0, ',', '.')
                                                : '—'),

                                        Placeholder::make('coupon_code')
                                            ->label('Cupón utilizado')
                                            ->content(fn ($record) => new HtmlString('<span class="inline-flex items-center rounded-md bg-primary-50 px-2 py-1 text-xs font-medium text-primary-700 ring-1 ring-inset ring-primary-600/20 dark:bg-primary-400/10 dark:text-primary-400">'.($record?->coupon?->code).'</span>'))
                                            ->hidden(fn ($record) => ! $record?->coupon),

                                        Placeholder::make('total_amount')
                                            ->label('Total abonado')
                                            ->content(fn ($record) => new HtmlString('<span class="text-lg font-bold text-primary-600 dark:text-primary-400">$'.number_format($record?->total_amount ?? 0, 0, ',', '.').'</span>')),

                                        Placeholder::make('shipping_address')
                                            ->label('Dirección de envío')
                                            ->content(fn ($record) => $record?->shipping_address ?: '—')
                                            ->columnSpanFull(),
                                    ]),
                            ])
                            ->columnSpan(['default' => 1, 'lg' => 2]),

                        // COLUMNA DERECHA: Cliente y Estado
                        Group::make()
                            ->schema([
                                Section::make('Cliente')
                                    ->description('Datos de contacto del comprador')
                                    ->icon('heroicon-o-user')
                                    ->schema([
                                        Placeholder::make('customer_name')
                                            ->label('Nombre completo')
                                            ->content(fn ($record) => new HtmlString('<span class="font-medium text-gray-950 dark:text-white">'.trim(($record?->customer?->name ?? '').' '.($record?->customer?->surname ?? '')).'</span>')),
                                        Placeholder::make('customer_email')
                                            ->label('Email')
                                            ->content(fn ($record) => new HtmlString('<a href="mailto:'.($record?->customer?->email).'" class="text-primary-600 dark:text-primary-400 hover:underline">'.($record?->customer?->email ?? '—').'</a>')),
                                        Placeholder::make('customer_phone')
                                            ->label('Teléfono')
                                            ->content(fn ($record) => $record?->customer?->phone ?: '—'),
                                    ]),

                                Section::make('Estado')
                                    ->description('Actualizar estado del pedido')
                                    ->icon('heroicon-o-cog-6-tooth')
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
                                            ->native(false) // Mejor UI que el select nativo
                                            ->required(),
                                        Toggle::make('is_packaged')
                                            ->label('Empaquetado')
                                            ->onColor('success')
                                            ->offColor('gray'),
                                        Placeholder::make('packaged_date')
                                            ->label('Fecha de empaquetado')
                                            ->content(fn ($record) => $record?->packaged_at
                                                ? Carbon::parse($record->packaged_at)->setTimezone('America/Argentina/Buenos_Aires')->format('d/m/Y H:i')
                                                : '—')
                                            ->hidden(fn ($record) => ! $record?->packaged_at),
                                        Textarea::make('observations')
                                            ->label('Observaciones')
                                            ->rows(3)
                                            ->maxLength(500)
                                            ->placeholder('Notas internas sobre el pedido...')
                                            ->autosize(),
                                    ]),
                            ])
                            ->columnSpan(['default' => 1, 'lg' => 1]),

                        // SECCIÓN INFERIOR: Productos (Ocupa todo el ancho)
                        Section::make('Productos del Pedido')
                            ->description(fn ($record) => $record?->products?->sum('quantity').' items en total')
                            ->icon('heroicon-o-shopping-bag')
                            ->collapsible()
                            ->columnSpanFull() // <-- Esto soluciona el problema de que la tabla se aplaste
                            ->schema([
                                View::make('filament.orders.products-table')
                                    ->viewData(fn ($record) => [
                                        'products' => $record->products,
                                        'totalAmount' => $record->total_amount,
                                    ]),
                            ]),
                    ]),

            ]);
    }
}
