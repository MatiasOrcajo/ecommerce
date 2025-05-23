<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SalesStatisticsService
{

    /**
     * Mapping of filter methods to their corresponding keys.
     */
    private const FILTER_METHOD_MAP = [
        'today' => 'filterSalesToday',
        'seven-days' => 'filterSevenDays',
        'this-month' => 'filterSalesThisMonth',
        'this-year' => 'filterSalesThisYear',
        'year-on-year' => 'filterSalesYearOnYear',
    ];


    /**
     * Retrieves filtered sales data based on the specified filter criteria.
     *
     * This method checks the provided filter parameter and determines the appropriate method to execute
     * from a predefined mapping. If the filter exists in the mapping, the corresponding method is called
     * to process and return the filtered sales data. Otherwise, it responds with an error for an invalid filter.
     *
     * @param Request $request The incoming HTTP request containing the filter parameter.
     * @return mixed The filtered sales data returned by the corresponding method or a JSON error response
     *               with a 400 status code for invalid filters.
     */
    public function getFilteredSales(Request $request): mixed
    {
        $filter = $request->input('filter');

        if (isset(self::FILTER_METHOD_MAP[$filter])) {
            $method = self::FILTER_METHOD_MAP[$filter];
            return $this->$method();
        }

        return response()->json(['error' => 'Invalid filter'], 400); // Handle invalid filter case
    }

    /**
     * Filters and calculates today's sales data.
     *
     * This method initializes today's date with a default sales value of 0. It retrieves all completed orders,
     * groups them by their order date, and calculates the total sales amount for each day. For the current day's
     * date, it checks if there are sales and includes the aggregated amount or assigns null if no sales exist.
     *
     * @return string A JSON representation of today's sales data, where the key is today's date in "d-m" format and
     *                the value is the total sales amount or null if there were no sales.
     */
    private function filterSalesToday(): string
    {
        $dates = collect([Carbon::parse(now())->format('d-m') => 0]);

        $orders = Order::where('status', 'completed')
            ->whereBetween('order_date', [Carbon::parse(now())->startOfDay(), Carbon::now()->endOfDay()])
            ->get()
            ->groupBy(function ($order) {
                return Carbon::parse($order->order_date)->format('d-m');
            })
            ->map(function ($orders) {
                return round(array_sum($orders->pluck('total_amount')->toArray()), 2);
            });

        $primaryInfo = $dates->map(fn($value, $date) => $orders[$date] ?? null)->toJson();
        $secondaryInfo = $this->filterSalesYesterdayPreviousPeriod();

        return json_encode(['primary' => $primaryInfo, 'secondary' => $secondaryInfo]);
    }

    private function filterSalesYesterdayPreviousPeriod(): string
    {
        $dates = collect([Carbon::parse(now())->subDays(1)->format('d-m') => 0]);

        $orders = Order::where('status', 'completed')
            ->whereBetween('order_date', [Carbon::parse(now())->subDays(1)->startOfDay(), Carbon::now()->endOfDay()])
            ->get()
            ->groupBy(function ($order) {
                return Carbon::parse($order->order_date)->format('d-m');
            })
            ->map(function ($orders) {
                return round(array_sum($orders->pluck('total_amount')->toArray()), 2);
            });

        return $dates->map(fn($value, $date) => $orders[$date] ?? null)->toJson();
    }



    /**
     * Filters and retrieves the total sales amounts for completed orders over the past seven days.
     *
     * This method calculates sales data grouped by day within the seven-day reporting period,
     * starting from seven days ago up to and including the current day. It initializes a structure
     * of dates within the period and maps it with calculated total sales amounts for each day.
     *
     * @return string A JSON representation of sales amounts, grouped by day, for the past seven days.
     */
    private function filterSevenDays(): string
    {
        $reportingPeriod = Carbon::parse(now())->subDays(7)->daysUntil(Carbon::parse(now()));

        $dates = collect($reportingPeriod)->mapWithKeys(function ($date) {
            return [Carbon::parse($date->toDateString())->format('d-m') => 0];
        });

        $orders = Order::where('status', 'completed')
            ->whereBetween('order_date', [Carbon::parse(now())->subDays(7)->startOfDay(), Carbon::now()->endOfDay()])
            ->orderBy('order_date')
            ->get()
            ->groupBy(function ($order) {
                return Carbon::parse($order->order_date)->format('d-m');
            })
            ->map(function ($orders) {
                return round(array_sum($orders->pluck('total_amount')->toArray()), 2);
            });

        $primaryInfo = $dates->map(fn($value, $date) => $orders[$date] ?? 0)->toJson();
        $secondaryInfo = $this->filterPreviousSevenDaysPeriod();

        return json_encode(['primary' => $primaryInfo, 'secondary' => $secondaryInfo]);
    }


    private function filterPreviousSevenDaysPeriod(): string
    {
        $reportingPeriod = Carbon::parse(now())->subDays(14)->daysUntil(Carbon::parse(now())->subDays(8));

        $dates = collect($reportingPeriod)->mapWithKeys(function ($date) {
            return [Carbon::parse($date->toDateString())->format('d-m') => 0];
        });

        $orders = Order::where('status', 'completed')
            ->whereBetween('order_date', [Carbon::parse(now())->subDays(14)->startOfDay(), Carbon::parse(now())->subDays(8)->endOfDay()])
            ->orderBy('order_date')
            ->get()
            ->groupBy(function ($order) {
                return Carbon::parse($order->order_date)->format('d-m');
            })
            ->map(function ($orders) {
                return round(array_sum($orders->pluck('total_amount')->toArray()), 2);
            });

        return $dates->map(fn($value, $date) => $orders[$date] ?? 0)->toJson();
    }


    /**
     * Filters and calculates the sales data for the current month.
     *
     * This method determines the reporting period from the start of the current month to the current date.
     * It then groups and aggregates sales data for completed orders by day, calculating the total amount
     * for each day's sales. Missing days within the reporting period are initialized with a value of 0.
     *
     * @return string A JSON representation of sales data aggregated by day for the current month,
     *                where each key is the day in "d-m" format and the value is the total sales amount.
     */
    private function filterSalesThisMonth(): string
    {
        $reportingPeriod = Carbon::parse(now())->startOfMonth()->daysUntil(now());

        $dates = collect($reportingPeriod)->mapWithKeys(function ($date) {
            return [Carbon::parse($date->toDateString())->format('d-m') => 0];
        });

        $orders = Order::where('status', 'completed')
            ->whereBetween('order_date', [Carbon::parse(now())->startOfMonth(), Carbon::now()])
            ->orderBy('order_date')
            ->get()
            ->groupBy(function ($order) {
                return Carbon::parse($order->order_date)->format('d-m');
            })
            ->map(function ($orders) {
                return round(array_sum($orders->pluck('total_amount')->toArray()), 2);
            });

        $primaryInfo = $dates->map(fn($value, $date) => $orders[$date] ?? 0)->toJson();
        $secondaryInfo = $this->filterSalesPreviousMonthPeriod();

        return json_encode(['primary' => $primaryInfo, 'secondary' => $secondaryInfo]);

    }

    private function filterSalesPreviousMonthPeriod(): string
    {
        $reportingPeriod = Carbon::now()->startOfMonth()->subMonth()->daysUntil(Carbon::now()->startOfMonth()->subMonth()->endOfMonth());

        $dates = collect($reportingPeriod)->mapWithKeys(function ($date) {
            return [Carbon::parse($date->toDateString())->format('d-m') => 0];
        });

        $orders = Order::where('status', 'completed')
            ->whereBetween('order_date', [Carbon::now()->startOfMonth()->subMonth(), Carbon::now()->startOfMonth()->subMonth()->endOfMonth()])
            ->orderBy('order_date')
            ->get()
            ->groupBy(function ($order) {
                return Carbon::parse($order->order_date)->format('d-m');
            })
            ->map(function ($orders) {
                return round(array_sum($orders->pluck('total_amount')->toArray()), 2);
            });

        return $dates->map(fn($value, $date) => $orders[$date] ?? 0)->toJson();

    }


    /**
     * Filters and calculates the sales data for the current year.
     *
     * This method defines the reporting period as the start of the current year through to the current date.
     * It groups and aggregates sales data for completed orders by month, calculating the total amount
     * for each month's sales. Missing months within the reporting period are initialized with a value of 0.
     *
     * @return string A JSON representation of sales data aggregated by month for the current year,
     *                where each key is a string in the format "Year MonthName" and the value is the total sales amount.
     */
    private function filterSalesThisYear(): string
    {
        $reportingPeriod = Carbon::parse(now())->startOfYear()->daysUntil(now());

        $months = collect($reportingPeriod)->mapWithKeys(function ($date) {
            return [$date->year . ' ' . $date->monthName => 0];
        });

        $orders = Order::where('status', 'completed')
            ->whereBetween('order_date', [Carbon::parse(now())->startOfYear(), Carbon::now()->endOfDay()])
            ->get()
            ->groupBy(function ($order) {
                return Carbon::parse($order->order_date)->format('Y F');
            })
            ->map(function ($orders) {
                return round(array_sum($orders->pluck('total_amount')->toArray()), 2);
            });

        $primaryInfo = $months->map(fn($value, $month) => $orders[$month] ?? 0)->toJson();
        $secondaryInfo = $this->filterSalesPreviousYearPeriod();

        return json_encode(['primary' => $primaryInfo, 'secondary' => $secondaryInfo]);
    }


    private function filterSalesPreviousYearPeriod(): string
    {
        $reportingPeriod = Carbon::parse(now())->startOfYear()->subYear(1)->daysUntil(now()->startOfYear()->subYear(1)->endOfYear());

        $months = collect($reportingPeriod)->mapWithKeys(function ($date) {
            return [$date->year . ' ' . $date->monthName => 0];
        });

        $orders = Order::where('status', 'completed')
            ->whereBetween('order_date', [Carbon::parse(now())->startOfYear()->subYear(1), now()->startOfYear()->subYear(1)->endOfYear()])
            ->get()
            ->groupBy(function ($order) {
                return Carbon::parse($order->order_date)->format('Y F');
            })
            ->map(function ($orders) {
                return round(array_sum($orders->pluck('total_amount')->toArray()), 2);
            });

        return $months->map(fn($value, $month) => $orders[$month] ?? 0)->toJson();

    }


    /**
     * Filters and calculates the sales data for the past 12 months year-on-year.
     *
     * This method determines the reporting period spanning the previous 12 months up to the current month.
     * It initializes each month within the period with a default value of 0 and groups completed order data
     * by year and month. The total sales amount is calculated for each month, and months with no sales data
     * are assigned a null value.
     *
     * @return string A JSON representation of sales data aggregated by month for the past year,
     *                where each key is a month in "Year MonthName" format and the value is the total
     *                sales amount or null if no sales occurred.
     */
    private function filterSalesYearOnYear(): string
    {

        $reportingPeriod = Carbon::parse(now())->subMonths(12)->monthsUntil(Carbon::parse(now()));

        $months = collect($reportingPeriod)->mapWithKeys(function ($date) {
            return [$date->year . ' ' . $date->monthName => 0];
        });

        $orders = Order::where('status', 'completed')
            ->whereBetween('order_date', [Carbon::parse(now())->subMonths(12)->startOfMonth(), Carbon::now()])
            ->get()
            ->groupBy(function ($order) {
                return Carbon::parse($order->order_date)->format('Y F');
            })
            ->map(function ($orders) {
                return round(array_sum($orders->pluck('total_amount')->toArray()), 2);
            });

        $primaryInfo = $months->map(fn($value, $month) => $orders[$month] ?? null)->toJson();
        $secondaryInfo = $this->filterSalesPreviousYearOnYearPeriod();

        return json_encode(['primary' => $primaryInfo, 'secondary' => $secondaryInfo]);
    }


    private function filterSalesPreviousYearOnYearPeriod(): string
    {
        $reportingPeriod = Carbon::parse(now())->startOfMonth()->subYear(2)->daysUntil(now()->startOfMonth()->subYear(1)->endOfMonth());

        $months = collect($reportingPeriod)->mapWithKeys(function ($date) {
            return [$date->year . ' ' . $date->monthName => 0];
        });

        $orders = Order::where('status', 'completed')
            ->whereBetween('order_date', [Carbon::parse(now())->startOfMonth()->subYear(2), now()->startOfMonth()->subYear(1)->endOfMonth()])
            ->get()
            ->groupBy(function ($order) {
                return Carbon::parse($order->order_date)->format('Y F');
            })
            ->map(function ($orders) {
                return round(array_sum($orders->pluck('total_amount')->toArray()), 2);
            });

        return $months->map(fn($value, $month) => $orders[$month] ?? 0)->toJson();

    }


}
