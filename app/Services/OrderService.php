<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OrderService{


    public function __construct(private readonly CustomerService $customerService, private readonly CartService $cartService, private readonly OrderProducts $orderProducts)
    {
    }


    /**
     * Crea una orden de compra y la retorna
     *
     * @param $customerData
     * @return Order
     * @throws \Exception
     */
    public function create($customerData)
    {

        $productsBagWithTotal = $this->cartService->calculateTotalAmount($customerData);
        $total = 0;

        foreach ($productsBagWithTotal as $product){
            $total += $product["total_amount_with_discount"];

        }

        $coupon = Coupon::where('code', $customerData->coupon)->first();

        $order                      = new Order();
        $order->customer_id         = $this->customerService->create($customerData)->id;
        $order->order_date          = Carbon::now();
        $order->total_amount        = round($total, 2);
        $order->shipping_address    = $customerData->address.', '. $customerData->locality.', '. $customerData->province.', '. $customerData->zip_code;
        $order->coupon_id           = $coupon->id ?? null;
        $order->save();

        //Vincula los productos del carrito al registro Order
        //Este dato luego se levanta en MercadoPagoService
        foreach ($productsBagWithTotal as $product)
            $this->orderProducts->create($order, $product);

        return $order;



    }


}
