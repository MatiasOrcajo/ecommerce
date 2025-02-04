<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatisticsService
{


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

        $reportingPeriod = Carbon::parse(now())->subMonths($monthsToGoBack)->monthsUntil(Carbon::parse(now()));

        $months = collect($reportingPeriod)->mapWithKeys(function ($date) {
            return [$date->year . ' ' . $date->monthName => 0];
        });

        $orders = Order::where('status', 'completed')
            ->get()
            ->groupBy(function ($order) {
                return Carbon::parse($order->order_date)->format('Y F');
            })
            ->map(function ($orders) {
                return round(array_sum($orders->pluck('total_amount')->toArray()), 2);
            });

        return $months->map(fn($value, $month) => $orders[$month] ?? null)->toJson();

    }


    /**
     * Retrieves the visitor count grouped by year and month for a specified reporting period.
     *
     * The reporting period is determined by the number of months to go back
     * from the current date, which defaults to 12 months if not specified.
     * Processes visitor data, groups by their creation date, and maps it to a
     * predefined structure of months within the reporting period.
     *
     * @param Request $request The incoming HTTP request containing data, optionally including 'sub' to determine the reporting period.
     *
     * @return string A JSON representation of visitor counts for each month in the specified reporting period.
     */
    public function getVisitors(Request $request): string
    {
        $monthsToGoBack = $request->sub ?? 12;

        $reportingPeriod = Carbon::parse(now())->subMonths($monthsToGoBack)->monthsUntil(Carbon::parse(now()));

        $months = collect($reportingPeriod)->mapWithKeys(function ($date) {
            return [$date->year . ' ' . $date->monthName => 0];
        });

        $visitors = Visitor::all()
            ->groupBy(function ($visitor) {
                return Carbon::parse($visitor->created_at)->format('Y F');
            })
            ->map(function ($visitors) {
                return $visitors->count();
            });

        return $months->map(fn($value, $month) => $visitors[$month] ?? null)->toJson();
    }

}
