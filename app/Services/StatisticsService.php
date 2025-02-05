<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatisticsService
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

        return $this->getFilteredSales($request);

    }


    private function filterBySpecificSubTimePeriod()
    {
        $reportingPeriod = Carbon::parse(now())->subDays(14)->daysUntil(Carbon::parse(now())->subDays(8));

        $dates = collect($reportingPeriod)->mapWithKeys(function ($date) {
            return [Carbon::parse($date->toDateString())->format('d-m') => 0];
        });

        $orders = Order::where('status', 'completed')->whereBetween('order_date', [Carbon::parse(now())->subDays(14), Carbon::parse(now())->subDays(8)])
            ->orderBy('order_date')
            ->get()
            ->groupBy(function ($order) {
                return Carbon::parse($order->order_date)->format('d-m');
            })
            ->map(function ($orders) {
                return round(array_sum($orders->pluck('total_amount')->toArray()), 2);
            });

        return $dates->map(fn($value, $day) => $orders[$day] ?? 0)->toJson();
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

        $orders = Order::where('status', 'completed')->whereBetween('order_date', [Carbon::parse(now())->subDays(7), Carbon::now()])
            ->orderBy('order_date')
            ->get()
            ->groupBy(function ($order) {
                return Carbon::parse($order->order_date)->format('d-m');
            })
            ->map(function ($orders) {
                return round(array_sum($orders->pluck('total_amount')->toArray()), 2);
            });

        $primaryInfo = $dates->map(fn($value, $day) => $orders[$day] ?? 0)->toJson();
        $secondaryInfo = $this->filterBySpecificSubTimePeriod();

        return json_encode(['primary' => $primaryInfo, 'secondary' => $secondaryInfo]);
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

        $orders = Order::where('status', 'completed')->whereBetween('order_date', [Carbon::parse(now())->startOfMonth(), Carbon::now()])
            ->orderBy('order_date')
            ->get()
            ->groupBy(function ($order) {
                return Carbon::parse($order->order_date)->format('d-m');
            })
            ->map(function ($orders) {
                return round(array_sum($orders->pluck('total_amount')->toArray()), 2);
            });

        return $dates->map(fn($value, $day) => $orders[$day] ?? 0)->toJson();
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
