<?php

namespace App\Services;


use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;

readonly class MercadoPagoService
{

    public function __construct()
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
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse Returns the created payment preference as a JSON response.
     */
    public function createPreference(Order $order)
    {

        try {

            $client = new PreferenceClient();
            $preference = $client->create([
                "back_urls" => [
                    "success" => url("/payment-success/" . Crypt::encryptString($order->id)),
                    "failure" => url("/payment-failure/" . Crypt::encryptString($order->id)),
                    "pending" => url("/payment-pending/" . Crypt::encryptString($order->id)),
                ],
                "items" => array(
                    array(
                        "id" => $order->id,
                        "title" => "Orden {$order->code} atica.com.ar",
                        "quantity" => 1,
                        "currency_id" => "ARS",
                        "unit_price" => $order->total_amount + $order->shipping_cost
//                        "unit_price" => 10

                    ),
                ),
                "external_reference" => $order->id,
                "notification_url" => route('mercadopago-notification-endpoint'),
            ]);

            $order->preference_id = $preference->id;
            $order->save();

        } catch (\Exception $e) {

            Log::error($e->toString());
            return response()->json(['error' => 'Failed to create preference', 'message' => $e->getMessage()], 500);
        }

        return response()->json($preference);
    }

}
