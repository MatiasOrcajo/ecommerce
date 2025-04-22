<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MercadopagoWebhookController extends Controller
{

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


    public function processMerchantOrder($dataId)
    {
        $accessToken = config('mercadopago.access_token');

        $response = Http::withToken($accessToken)
            ->get("https://api.mercadopago.com/merchant_orders/{$dataId}");

        $order = Order::find($response["external_reference"]);

        if($response["order_status"] == "paid" || $response["order_status"] == "partially_paid" ){
            $order->status = "Orden recibida";
        }

        else if($response["order_status"] == "payment_in_process"){
            $order->status = "Pago pendiente";
        }

        else{
            $order->status = "Orden cancelada";
        }

        $order->save();

        return $response->json();
    }


}
