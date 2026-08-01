<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pedidos Totales', Order::count())
                ->description('Total de pedidos')
                ->descriptionIcon('heroicon-o-shopping-bag')
                ->color('primary'),
            Stat::make('Facturacion Total', '$'.number_format(Order::sum('total_amount'), 0, ',', '.'))
                ->description('Ingresos totales')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success'),
            Stat::make('Productos', Product::count())
                ->description('En catalogo')
                ->descriptionIcon('heroicon-o-tag')
                ->color('warning'),
            Stat::make('Clientes', Customer::count())
                ->description('Registrados')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('info'),
        ];
    }
}
