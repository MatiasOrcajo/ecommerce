<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(private readonly MercadoPagoService $mpService)
    {

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pagar(Request $request)
    {
        return $this->mpService->createPreference($request);
    }


    /**
     * @param Request $request
     * @return void
     */
    public function success(Request $request, Order $order)
    {
        dd($order);
    }




    // public function

}
