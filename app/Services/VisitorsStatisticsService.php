<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VisitorsStatisticsService
{
    /**
     * Mapping of filter methods to their corresponding keys.
     */
    private const FILTER_METHOD_MAP = [
        'today' => 'filterVisitorsToday',
        'seven-days' => 'filterVisitorsSevenDays',
        'this-month' => 'filterVisitorsThisMonth',
        'this-year' => 'filterVisitorsThisYear',
        'year-on-year' => 'filterVisitorsYearOnYear',
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
    public function getFilteredVisitors(Request $request): mixed
    {
        $filter = $request->input('filter');

        if (isset(self::FILTER_METHOD_MAP[$filter])) {
            $method = self::FILTER_METHOD_MAP[$filter];
            return $this->$method();
        }

        return response()->json(['error' => 'Invalid filter'], 400); // Handle invalid filter case
    }



    /**
     * Filters and retrieves visitor data for the current day.
     *
     * This method fetches the visitors created within the current day's*/
    private function filterVisitorsToday(): string
    {
        $dates = collect([Carbon::parse(now())->format('d-m') => 0]);

        $visitors = Visitor::
            whereBetween('created_at', [Carbon::parse(now())->startOfDay(), Carbon::now()->endOfDay()])
            ->orderBy('created_at')
            ->get()
            ->groupBy(function ($visitor) {
                return Carbon::parse($visitor->created_at)->format('d-m');
            })
            ->map(function ($visitors) {
                return $visitors->count();
            });

        $primaryInfo = $dates->map(fn($value, $date) => $visitors[$date] ?? null)->toJson();
        $secondaryInfo = $this->filterVisitorsYesterdayPreviousPeriod();

        return json_encode(['primary' => $primaryInfo, 'secondary' => $secondaryInfo]);
    }


    private function filterVisitorsYesterdayPreviousPeriod(): string
    {
        $dates = collect([Carbon::parse(now())->format('d-m') => 0]);

        $visitors = Visitor::
            whereBetween('created_at', [Carbon::parse(now())->subDays(1)->startOfDay(), Carbon::now()->subDays(1)->endOfDay()])
            ->orderBy('created_at')
            ->get()
            ->groupBy(function ($visitor) {
                return Carbon::parse($visitor->created_at)->format('d-m');
            })
            ->map(function ($visitors) {
                return $visitors->count();
            });

        return $dates->map(fn($value, $date) => $visitors[$date] ?? 0)->toJson();
    }


    /**
     * Generates a report of visitors for the last seven days.
     *
     * This method calculates the number of visitors grouped by each day within the past seven days. It initializes
     * a default collection of dates with zero values and retrieves visitor records created within the specified
     * seven-day period. The visitors are grouped by their creation dates and counted, populating the primary
     * dataset. Additionally, it appends data from the previous seven-day period to form a secondary data set.
     *
     * @return string A JSON-encoded array consisting of two datasets:
     *                'primary' (visitor data for the last seven days)
     *                and 'secondary' (historical data for the previous week).
     */
    private function filterVisitorsSevenDays()
    {
        $reportingPeriod = Carbon::parse(now())->subDays(7)->daysUntil(Carbon::parse(now()));

        $dates = collect($reportingPeriod)->mapWithKeys(function ($date) {
            return [Carbon::parse($date->toDateString())->format('d-m') => 0];
        });

        $visitors = Visitor::
        whereBetween('created_at', [Carbon::parse(now())->subDays(7)->startOfDay(), Carbon::now()->endOfDay()])
            ->orderBy('created_at')
            ->get()
            ->groupBy(function ($visitor) {
                return Carbon::parse($visitor->created_at)->format('d-m');
            })
            ->map(function ($visitors) {
                return $visitors->count();
            });

        $primaryInfo = $dates->map(fn($value, $date) => $visitors[$date] ?? null)->toJson();
        $secondaryInfo = $this->filterVisitorsPreviousSevenDays();

        return json_encode(['primary' => $primaryInfo, 'secondary' => $secondaryInfo]);
    }


    private function filterVisitorsPreviousSevenDays(): string
    {
        $reportingPeriod = Carbon::parse(now())->subDays(14)->daysUntil(Carbon::parse(now())->subDays(8));

        $dates = collect($reportingPeriod)->mapWithKeys(function ($date) {
            return [Carbon::parse($date->toDateString())->format('d-m') => 0];
        });

        $visitors = Visitor::
        whereBetween('created_at', [Carbon::parse(now())->subDays(14)->startOfDay(), Carbon::parse(now())->subDays(8)->endOfDay()])
            ->orderBy('created_at')
            ->get()
            ->groupBy(function ($visitor) {
                return Carbon::parse($visitor->created_at)->format('d-m');
            })
            ->map(function ($visitors) {
                return $visitors->count();
            });

        return $dates->map(fn($value, $date) => $visitors[$date] ?? 0)->toJson();
    }


    /**
     * Generates a JSON report of visitor data for the current month.
     *
     * This method calculates the number of visitors for each day of the current month up to the current date.
     * It initializes a date range from the start of the month and prepares a dataset with zero counts for each day.
     * The data is then populated with the actual visitor counts grouped by the day of their creation timestamp.
     * The method also includes a secondary dataset for the previous month's visitor data and combines both datasets
     * into a JSON-encoded response.
     *
     * @return string A JSON-encoded string containing the current month's visitor data as the primary information
     *                and the previous month's data as the secondary information.
     */
    private function filterVisitorsThisMonth(): string
    {
        $reportingPeriod = Carbon::parse(now())->startOfMonth()->daysUntil(now());

        $dates = collect($reportingPeriod)->mapWithKeys(function ($date) {
            return [Carbon::parse($date->toDateString())->format('d-m') => 0];
        });

        $visitors = Visitor::
        whereBetween('created_at', [Carbon::parse(now())->startOfMonth(), Carbon::now()])
            ->orderBy('created_at')
            ->get()
            ->groupBy(function ($visitor) {
                return Carbon::parse($visitor->created_at)->format('d-m');
            })
            ->map(function ($visitors) {
                return $visitors->count();
            });

        $primaryInfo = $dates->map(fn($value, $date) => $visitors[$date] ?? null)->toJson();
        $secondaryInfo = $this->filterVisitorsPreviousMonth();

        return json_encode(['primary' => $primaryInfo, 'secondary' => $secondaryInfo]);
    }


    private function filterVisitorsPreviousMonth(): string
    {
        $reportingPeriod = Carbon::now()->startOfMonth()->subMonth()->daysUntil(Carbon::now()->startOfMonth()->subMonth()->endOfMonth());

        $dates = collect($reportingPeriod)->mapWithKeys(function ($date) {
            return [Carbon::parse($date->toDateString())->format('d-m') => 0];
        });

        $visitors = Visitor::
        whereBetween('created_at', [Carbon::now()->startOfMonth()->subMonth(), Carbon::now()->startOfMonth()->subMonth()->endOfMonth()])
            ->orderBy('created_at')
            ->get()
            ->groupBy(function ($visitor) {
                return Carbon::parse($visitor->created_at)->format('d-m');
            })
            ->map(function ($visitors) {
                return $visitors->count();
            });

        return $dates->map(fn($value, $date) => $visitors[$date] ?? 0)->toJson();
    }


    /**
     * Filters and processes visitor data for the current year to generate a JSON response.
     *
     * This method calculates the reporting period for the current year, organizes the months
     * within that period, and retrieves visitors' data grouped by year and month. It generates
     * a primary dataset for the current year and appends secondary data for the previous year.
     *
     * @return string JSON-encoded response containing visitor statistics for the current year,
     *                including comparisons with the previous year.
     */
    private function filterVisitorsThisYear(): string
    {
        $reportingPeriod = Carbon::parse(now())->startOfYear()->daysUntil(now());

        $months = collect($reportingPeriod)->mapWithKeys(function ($date) {
            return [$date->year . ' ' . $date->monthName => 0];
        });

        $visitors = Visitor::
        whereBetween('created_at', [Carbon::parse(now())->startOfYear(), Carbon::now()->endOfDay()])
            ->orderBy('created_at')
            ->get()
            ->groupBy(function ($visitor) {
                return Carbon::parse($visitor->created_at)->format('Y F');
            })
            ->map(function ($visitors) {
                return $visitors->count();
            });

        $primaryInfo = $months->map(fn($value, $month) => $visitors[$month] ?? null)->toJson();
        $secondaryInfo = $this->filterVisitorsPreviousYear();

        return json_encode(['primary' => $primaryInfo, 'secondary' => $secondaryInfo]);
    }


    private function filterVisitorsPreviousYear(): string
    {
        $reportingPeriod = Carbon::parse(now())->startOfYear()->subYear(1)->daysUntil(now()->startOfYear()->subYear(1)->endOfYear());

        $months = collect($reportingPeriod)->mapWithKeys(function ($date) {
            return [$date->year . ' ' . $date->monthName => 0];
        });

        $visitors = Visitor::
        whereBetween('created_at', [Carbon::parse(now())->startOfYear()->subYear(1), now()->startOfYear()->subYear(1)->endOfYear()])
            ->orderBy('created_at')
            ->get()
            ->groupBy(function ($visitor) {
                return Carbon::parse($visitor->created_at)->format('Y F');
            })
            ->map(function ($visitors) {
                return $visitors->count();
            });

        return $months->map(fn($value, $month) => $visitors[$month] ?? 0)->toJson();
    }


    /**
     * Processes and compares visitor data over the past 12 months to generate a JSON report.
     *
     * This method calculates a reporting period spanning the previous 12 months, organizes
     * monthly data for that timeframe, and retrieves visitor statistics grouped by year and month.
     * It generates a primary dataset for the last 12 months and appends secondary data for comparison.
     *
     * @return string JSON-encoded response containing visitor statistics for the past year,
     *                including comparative data from the corresponding period of the previous year.
     */
    private function filterVisitorsYearOnYear(): string
    {
        $reportingPeriod = Carbon::parse(now())->subMonths(12)->monthsUntil(Carbon::parse(now()));

        $months = collect($reportingPeriod)->mapWithKeys(function ($date) {
            return [$date->year . ' ' . $date->monthName => 0];
        });

        $visitors = Visitor::
        whereBetween('created_at', [Carbon::parse(now())->subMonths(12)->startOfMonth(), Carbon::now()])
            ->orderBy('created_at')
            ->get()
            ->groupBy(function ($visitor) {
                return Carbon::parse($visitor->created_at)->format('Y F');
            })
            ->map(function ($visitors) {
                return $visitors->count();
            });

        $primaryInfo = $months->map(fn($value, $month) => $visitors[$month] ?? null)->toJson();
        $secondaryInfo = $this->filterVisitorsPreviousYearOnYearPeriod();

        return json_encode(['primary' => $primaryInfo, 'secondary' => $secondaryInfo]);
    }


    private function filterVisitorsPreviousYearOnYearPeriod(): string
    {
        $reportingPeriod = Carbon::parse(now())->startOfMonth()->subYear(2)->daysUntil(now()->startOfMonth()->subYear(1)->endOfMonth());

        $months = collect($reportingPeriod)->mapWithKeys(function ($date) {
            return [$date->year . ' ' . $date->monthName => 0];
        });

        $visitors = Visitor::
        whereBetween('created_at', [Carbon::parse(now())->startOfMonth()->subYear(2), now()->startOfMonth()->subYear(1)->endOfMonth()])
            ->orderBy('created_at')
            ->get()
            ->groupBy(function ($visitor) {
                return Carbon::parse($visitor->created_at)->format('Y F');
            })
            ->map(function ($visitors) {
                return $visitors->count();
            });

        return $months->map(fn($value, $month) => $visitors[$month] ?? 0)->toJson();
    }



}
