<?php

namespace App\Services;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatisticsService{


    /**
     * Retrieves sales data for completed orders, grouped and counted by month.
     *
     * This method calculates the sales data based on a requested sub-period of months
     * or defaults to a 12-month period. It generates a collection of months within
     * the specified period and maps completed order counts to each month. The result
     * is returned as a JSON string.
     *
     * @param Request $request The HTTP request instance containing the `sub` parameter
     *                         used to determine the number of months for the report.
     *
     * @return string A JSON-encoded string representing sales data with months as keys
     *                and the corresponding number of completed orders as values.
     */
    public function getSales(Request $request): string
    {
        $monthsToGoBack = $request->sub ?? 12;

        $reportingPeriod = Carbon::parse('2024-4-3')->subMonths($monthsToGoBack)->monthsUntil(Carbon::parse('2024-4-3'));

        $months = collect($reportingPeriod)->mapWithKeys(function($date){
            return [$date->year . ' '.  $date->monthName => 0];
        });

        $orders = Order::where('status', 'completed')
            ->get()
            ->groupBy(function ($order) {
                return Carbon::parse($order->order_date)->format('Y F');
            })
            ->map(function ($orders) {
                return round(array_sum($orders->pluck('total_amount')->toArray()), 2);
            });;

        return $months->map(fn($value, $month) => $orders[$month] ?? null)->toJson();

    }

}
