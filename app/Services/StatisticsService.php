<?php

namespace App\Services;

use App\Models\Order;
use Carbon\Carbon;

class StatisticsService{

    public function getSales()
    {
        $months = collect(range(1, 12))->mapWithKeys(function ($month) {
            return [Carbon::createFromDate(null, $month, 1)->format('F') => 0];
        });

        $orders = Order::get()
            ->groupBy(function ($order) {
                return Carbon::parse($order->order_date)->format('F'); // Obtiene el nombre del mes
            })
            ->map(function ($orders) {
                return $orders->count(); // Cuenta el número por mes
            });

        $months = $months->merge($orders);

        return $months->toJson();


    }

}
