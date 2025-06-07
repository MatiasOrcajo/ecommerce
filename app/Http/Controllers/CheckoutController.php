<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MercadoPagoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use MercadoPago\Client\Preference\PreferenceClient;

class CheckoutController extends Controller
{
    public function __construct(private readonly MercadoPagoService $mpService)
    {

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pay(Request $request)
    {
        return $this->mpService->createPreference($request);
    }


    /**
     * @param Request $request
     * @return void
     */
    public function success(Request $request, Order $order)
    {
        $order->status = 'Orden recibida';
        $order->save();

    }


    /**
     * @param Request $request
     * @return void
     */
    public function failure(Request $request, Order $order)
    {
        $order->status = 'Pago rechazado';
        $order->save();

    }



    /**
     * @param Request $request
     * @return void
     */
    public function pending(Request $request, Order $order)
    {
        $order->status = 'Pendiente de aprobación';
        $order->save();

    }

    /**
     * Handle the request to retrieve cart information from the session.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCartInfo(): JsonResponse
    {
        $sessionCart = \Illuminate\Support\Facades\Session::get('cart');
        $products = $sessionCart[array_key_first($sessionCart)]["products"];

        $data = [
            "products" => []
        ];

        foreach ($products as $product) {
            foreach($product["sizes"] as $index => $size){
                $data["products"][] = [
                    "name" => $product["name"],
                    "price" => $product["price"],
                    "size" => $index,
                    "quantity" => $size["quantity"],
                    "discount" => $product["discount"],
                    "picture" => $product["picture"],
                    "total" => $size["total_amount_with_discounts"]
                ];
            }

        }

        $data["order_total_amount"] = array_sum(array_column($data["products"], "total"));

        return response()->json($data);
    }



    // public function

}
