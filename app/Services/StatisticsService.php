<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\SalesStatisticsService;

class StatisticsService
{


    public function __construct(private readonly SalesStatisticsService $salesStatisticsService, private readonly VisitorsStatisticsService $visitorsStatisticsService)
    {
    }


    /**
     * Handle the request to retrieve filtered sales data.
     *
     * @param Request $request The incoming HTTP request containing filter parameters.
     * @return string The filtered sales data in string format.
     */
    public function getSales(Request $request): string
    {
        return $this->salesStatisticsService->getFilteredSales($request);

    }


    /**
     * Handles the retrieval of filtered visitor statistics.
     *
     * @param Request $request The incoming HTTP request containing filter criteria.
     * @return string The filtered visitor statistics data.
     */
    public function getVisitors(Request $request): string
    {
        return $this->visitorsStatisticsService->getFilteredVisitors($request);
    }


}
