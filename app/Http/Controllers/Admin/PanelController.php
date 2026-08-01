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
        return DataTables::of(Order::orderBy('id', 'DESC')->get()->map(function ($order) {
            return [
                'id' => $order->id,
                'code' => $order->code,
                'customer_name' => $order->customer->name.' '.$order->customer->surname.', '.$order->customer->phone.', '.$order->customer->email,
                'total' => '$'.$order->total_amount,
                'status' => $order->status,
                'created_at' => Carbon::parse($order->created_at)->format('d/m/Y h:i A'),
                'shipping_address' => $order->shipping_address,
                'shipping_method' => $order->shipping_method,
                'observations' => $order->observations,
                'order' => $order->products->map(function ($orderProduct) {
                    return '<div class="d-flex align-items-center gap-2 mb-2 pb-2 border-bottom">'
                        .'<div>'
                        .'<strong>'.$orderProduct->quantity.'x '.$orderProduct->productVariant->product->name.'</strong><br>'
                        .'<small class="text-muted">Talle: '.$orderProduct->productVariant->size.' | '.$orderProduct->productVariant->color_name.'</small>'
                        .'</div>'
                        .'<span class="ms-auto" style="display:inline-block;width:18px;height:18px;border-radius:50%;background:'.$orderProduct->productVariant->color.';border:1px solid #ddd;"></span>'
                        .'</div>';
                })->implode(''),
                'order_summary' => $order->products->take(2)->map(function ($orderProduct) {
                    return $orderProduct->quantity.'x '.$orderProduct->productVariant->product->name;
                })->implode(' | ').($order->products->count() > 2 ? ' +'.($order->products->count() - 2).' mas' : ''),
            ];
        }))->make(true);
    }

    public function updateOrderStatus(Order $order, Request $request)
    {
        $order->status = $request->status;
        $order->save();

        if ($order->status == 'En proceso') {
            SendOrderInProcessEmail::dispatch($order->id)->delay(now()->addSeconds(5));
        }

        if ($order->status == 'Envío realizado') {
            SendOrderSentEmail::dispatch($order->id)->delay(now()->addSeconds(5));
        }

    }
}
