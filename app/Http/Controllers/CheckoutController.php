<?php

namespace App\Http\Controllers;

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
        return $this->mpService->crearPreferencia($request);
    }


    /**
     * @param Request $request
     * @return void
     */
    public function success(Request $request)
    {
        dd($request);
    }




    // public function

}
