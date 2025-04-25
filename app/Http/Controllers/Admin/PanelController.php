<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        return DataTables::of(Order::all()->map(function ($order){
            return [
                "id" => $order->id,
                "customer_name" => $order->customer->name." ".$order->customer->surname.", ". $order->customer->phone.", ". $order->customer->email,
                "total" => "$".$order->total_amount,
                "status" => $order->status,
                "created_at" => Carbon::parse($order->created_at)->format('d/m/Y h:i A'),
                "shipping_address" => $order->shipping_address,
                "order" => $order->products->map(function ($orderProduct) use ($order){
                    return "x".$orderProduct->quantity." ".$orderProduct->product->name." ";
                })
            ];
        }))->make(true);
    }


    public function updateOrderStatus(Order $order, Request $request)
    {
        $order->status = $request->status;
        $order->save();
    }

}
