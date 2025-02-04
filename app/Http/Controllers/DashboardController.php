<?php

namespace App\Http\Controllers;

use App\Services\StatisticsService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function __construct(private readonly StatisticsService $statisticsService)
    {
    }

    /**
     * Handle the retrieval of sales data based on the given request.
     *
     * @param Request $request The request instance containing necessary inputs.
     * @return string The sales data retrieved by the statistics service.
     */
    public function getSales(Request $request): string
    {
        return $this->statisticsService->getSales($request);
    }


    /**
     * Handle the request to retrieve visitor data using the statistics service.
     *
     * @param Request $request The incoming HTTP request instance containing input data.
     * @return string The result of the visitor data retrieval process.
     */
    public function getVisitors(Request $request): string
    {
        return $this->statisticsService->getVisitors($request);
    }

}
