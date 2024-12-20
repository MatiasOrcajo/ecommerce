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

        // Retrieves items to be purchased, with final price including discounts
        $items = $this->orderProductsService->mapOrderProductToItem($order->id);

        try {
            $client = new PreferenceClient();
            $preference = $client->create([
                "back_urls" => [
                    "success" => route('pago-exitoso', $order->id),
                    "failure" => env('URL_FAILURE', 'https://default.failure.url'),
                    "pending" => env('URL_PENDING', 'https://default.pending.url'),
                ],
                "items" => $items,
                "external_reference" => $order->id,
            ]);

        } catch (\Exception $e) {

            return response()->json(['error' => 'Failed to create preference', 'message' => $e->getMessage()], 500);
        }


        return response()->json($preference);
    }

}
