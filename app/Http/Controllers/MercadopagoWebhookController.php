<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MercadopagoWebhookController extends Controller
{

    /**
     * Handles a webhook request from Mercado Pago.
     *
     * Logs the received webhook request data and processes the merchant order
     * if the provided type and data ID match the expected criteria.
     * Returns a JSON response indicating a successful operation.
     *
     * @param Request $request The incoming HTTP request object containing webhook data.
     * @return \Illuminate\Http\JsonResponse|array The JSON response indicating the processing result.
     */
    public function handle(Request $request)
    {
        Log::info('Webhook Mercado Pago recibido', $request->all());

        $type = $request->input('type');
        $dataId = $request->input('data_id'); // Este es el merchant_order_id

        if ($type === 'topic_merchant_order_wh' && $dataId) {
            return $this->processMerchantOrder($dataId);
        }

        return response()->json(['message' => 'OK'], 200);

    }


    /**
     * Processes a merchant order using the provided data ID.
     *
     * Retrieves the merchant order details from MercadoPago's API using the access token
     * and updates the status of the corresponding order in the database.
     *
     * Updates the order status based on the payment status received from the API:
     * - "paid" or "partially_paid": Sets status to "Pago recibido".
     * - "payment_in_process": Sets status to "Pago pendiente de aprobación".
     * - Any other status: Sets status to "Pago fallido".
     *
     * Returns the response data as a JSON object.
     *
     * @param mixed $dataId The identifier of the merchant order to process.
     * @return array The API response in JSON format.
     */
    public function processMerchantOrder($dataId)
    {
        $accessToken = config('mercadopago.access_token');

        $response = Http::withToken($accessToken)
            ->get("https://api.mercadopago.com/merchant_orders/{$dataId}");

        $order = Order::find($response["external_reference"]);

        if($response["order_status"] == "paid" || $response["order_status"] == "partially_paid" ){
            $order->status = "Pago recibido";
        }

        else if($response["order_status"] == "payment_in_process"){
            $order->status = "Pago pendiente de aprobación";
        }

        else{
            $order->status = "Pago fallido";
        }

        $order->save();

        return $response->json();
    }


}
