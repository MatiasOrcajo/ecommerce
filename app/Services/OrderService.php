<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OrderService{

    public function __construct(private readonly UserService $userService, private readonly CartService $cartService, private readonly CouponService $couponService, private readonly OrderProducts $orderProducts)
    {
    }


    public function create($userData)
    {

        //User data
        $data = [
            "name" => $userData->name.' '. $userData->surname,
            "email" => $userData->email,
            "phone" => $userData->phone,
            "dni_or_cuit" => $userData->dni,
            "coupon" => $userData->coupon
        ];
        //Valida el cupón en caso de haber y aplica descuento

        $coupon = null;

        if($userData->coupon != null){
            $coupon = $this->couponService->validateCoupon($userData->coupon);
        }

        $total = $this->cartService->calculateTotalAmount();

        if($coupon){
            $total = $total - ($total * $coupon->discount) / 100;
        }

        //

        $order                      = new Order();
        $order->user_id             = $this->userService->create($data)->id;
        $order->order_date          = Carbon::now();
        $order->total_amount        = $total;
        $order->shipping_address    = $userData->address.', '. $userData->locality.', '. $userData->province.', '. $userData->zip_code;
        $order->coupon_id           = $coupon->id ?? null;
        $order->save();

        //Asigna los productos del carrito al registro Order
        $this->orderProducts->create($order);


        return $order;



    }


}
