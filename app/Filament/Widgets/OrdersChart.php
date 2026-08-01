<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class OrdersChart extends ChartWidget
{
    protected static ?int $sort = 2;

    public function getHeading(): \Illuminate\Contracts\Support\Htmlable|string|null
    {
        return 'Pedidos por Mes';
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $data = $this->getOrdersPerMonth();

        return [
            'datasets' => [
                [
                    'label' => 'Pedidos',
                    'data' => $data['orders'],
                    'backgroundColor' => '#36A2EB',
                ],
                [
                    'label' => 'Facturacion (ARS)',
                    'data' => $data['revenue'],
                    'backgroundColor' => '#4BC0C0',
                ],
            ],
            'labels' => $data['months'],
        ];
    }

    private function getOrdersPerMonth(): array
    {
        $months = collect();
        $orders = collect();
        $revenue = collect();

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->translatedFormat('M Y');
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();

            $monthOrders = Order::whereBetween('created_at', [$monthStart, $monthEnd]);
            $months->push($monthName);
            $orders->push($monthOrders->count());
            $revenue->push((int) $monthOrders->sum('total_amount'));
        }

        return [
            'months' => $months->toArray(),
            'orders' => $orders->toArray(),
            'revenue' => $revenue->toArray(),
        ];
    }
}
