<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendOrderInProcessEmail;
use App\Jobs\SendOrderSentEmail;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PanelController extends Controller
{

    public function index()
    {

        return view('admin.panel');
    }


    public function listOrderList()
    {
        return DataTables::of(Order::orderBy('id', 'DESC')->get()->map(function ($order){
            return [
                "id" => $order->id,
                "code" => $order->code,
                "customer_name" => $order->customer->name." ".$order->customer->surname.", ". $order->customer->phone.", ". $order->customer->email,
                "total" => "$".$order->total_amount,
                "status" => $order->status,
                "created_at" => Carbon::parse($order->created_at)->format('d/m/Y h:i A'),
                "shipping_address" => $order->shipping_address,
                "order" => $order->products->map(function ($orderProduct) use ($order){
                    return $orderProduct->quantity . "x " .
                        $orderProduct->productVariant->product->name . '<br/>' .
                        'Talle '.$orderProduct->productVariant->size . '<br/>' .
                        $orderProduct->productVariant->color_name .
                        '<div style="height: 15px; width: 15px; border: 1px solid black; background: ' . $orderProduct->productVariant->color . ';"></div>' .
                        '<br/>';

                })
            ];
        }))->make(true);
    }


    public function updateOrderStatus(Order $order, Request $request)
    {
        $order->status = $request->status;
        $order->save();

        if($order->status == "En proceso"){
            SendOrderInProcessEmail::dispatch($order->id)->delay(now()->addSeconds(5));
        }

        if($order->status == "Envío realizado"){
            SendOrderSentEmail::dispatch($order->id)->delay(now()->addSeconds(5));
        }

    }

}
