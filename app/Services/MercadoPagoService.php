<?php

namespace App\Services;


use App\Models\Product;
use Illuminate\Http\Request;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;

class MercadoPagoService{

    public function __construct(private readonly OrderService $orderService)
    {
        MercadoPagoConfig::setAccessToken(config('mercadopago.access_token'));
    }


    public function crearPreferencia(Request $request)
    {

        $order = $this->orderService->create(json_decode($request->data));

        $items = $order->products->reduce(function (array $acc, \App\Models\OrderProducts $product){
           dd($product);
        }, []);

        $client = new PreferenceClient();
        $preference = $client->create([
            "back_urls"=>array(
                "success" => asset(route('pago-exitoso')),
                "failure" => "https://test.com/failure",
                "pending" => "https://test.com/pending"
            ),
            "items" => array(
                array(
                    "id" => "1234",
                    "title" => "Cortina",
                    "description" => "Dummy description",
                    "picture_url" => "https://imgs.search.brave.com/FlJFOyosp_fhyvvBS4wwD4XB0H66-Z7ir-LN_P7jQHI/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9yZXNp/emVyLmdsYW5hY2lv/bi5jb20vcmVzaXpl/ci92Mi9qYXZpZXIt/bWlsZWktYXAtcGhv/dG9zZXRoLXdlbmln/LVlNNEY3QkxPWTVE/NURGUlRCRktQWUZS/WkxBLkpQRz9hdXRo/PWEwYThhMmRlNGVk/YzU5ZDc3Nzg4MjJl/YWRiZWY2NzMwYjY5/MGU1Mzc3NmVlMjY0/OGM0YmViODNiMjlm/YTE5ZGYmd2lkdGg9/NDIwJmhlaWdodD0y/ODAmcXVhbGl0eT03/MCZzbWFydD10cnVl",
                    "category_id" => "car_electronics",
                    "quantity" => 1,
                    "currency_id" => "ARS",
                    "unit_price" => 1,

                )
            ),
            "external_reference" => $order->id,
        ]);

        dd($preference);


        return response()->json($preference);
    }

}
