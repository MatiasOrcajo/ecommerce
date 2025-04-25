<?php

namespace App\Services;


use App\Models\Product;
use Illuminate\Http\Request;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;

readonly class MercadoPagoService
{

    public function __construct(private OrderService $orderService,
                                private OrderProductsService $orderProductsService
    )
    {
        MercadoPagoConfig::setAccessToken(config('mercadopago.access_token'));
    }


    /**
     * Creates a preference for an order.
     *
     * This method creates an order using the provided request data,
     * instantiates a client to create a payment preference, maps the order's
     * products to prepare the necessary items array, and returns the created
     * preference as a JSON response.
     *
     * @param Request $request The HTTP request containing data for order creation.
     * @return \Illuminate\Http\JsonResponse Returns the created payment preference as a JSON response.
     */
    public function createPreference(Request $request)
    {

        // Creates service record
        $order = $this->orderService->create(json_decode($request->data));
        $order->status = "Orden no paga por el cliente";
        $order->save();

        // Retrieves items to be purchased, with final price including discounts
        $items = $this->orderProductsService->mapOrderProductToItem($order->id);

//        dd($items);

        try {
            $client = new PreferenceClient();
            $preference = $client->create(request: [
                "back_urls" => [
                    "success" => route('payment-success', $order->id),
                    "failure" => route('payment-failure', $order->id),
                    "pending" => route('payment-pending', $order->id),
                ],
                "items" => array(
                    array(
                        "id" => "1234",
                        "title" => "Orden #{$order->id} atica.com.ar",
                        "quantity" => 1,
                        "currency_id" => "ARS",
//                        "unit_price" => $order->total_amount
                        "unit_price" => 10
                    )
                ),
                "external_reference" => $order->id,
                "notification_url" => "https://notificationurl.com",
            ]);

            $order->preference_id = $preference->id;
            $order->save();

        } catch (\Exception $e) {
            dd($e);
            return response()->json(['error' => 'Failed to create preference', 'message' => $e->getMessage()], 500);
        }


        return response()->json($preference);
    }

}
