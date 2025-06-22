<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Order;
use App\Traits\CartTrait;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

/**
 * Handles the creation and management of customer orders, including cart processing,
 * discount application, and order product association.
 */
class OrderService
{

    use CartTrait;

    public function __construct(private readonly CustomerService      $customerService,
                                private readonly CartService          $cartService,
                                private readonly OrderProductsService $orderProducts)
    {
    }


    /**
     * Creates a new order based on provided customer data, calculates the total amount including discounts,
     * applies a coupon if available, and associates the products in the cart to the order.
     *
     * @param object $customerData The data provided by the customer, including address, coupon code, and other details.
     *
     * @return \App\Models\Order The newly created order instance.
     */
    public function create($customerData)
    {
        // Calculate the total cart amount
        // receives array of products

        // Extract shipping address (extract variable)
        $shippingAddress = sprintf(
            "%s, %s, %s, %s, %s",
            $customerData->street.' '.$customerData->number,
            $customerData->apartment ? 'Departamento '.$customerData->apartment : '',
            'C.P.: '.$customerData->zip_code,
            $customerData->locality,
            $customerData->province,
        );

        // Create customer and associate with the order
        $customer = $this->customerService->create($customerData);

        // Create order (introduce variable)
        $order = Order::create([
            'customer_id' => $customer->id,
            'order_date' => Carbon::now(),
            'total_amount' => $this->getCartTotal(),
            'code' => $this->generateOrderCode(),
            'status' => 'No pago',
            'shipping_address' => $shippingAddress,
            'expiration_date' => Carbon::now()->addDays(3),
            'coupon_id' => $this->getCouponAppliedId(),
        ]);

        $this->orderProducts->create($order);

        return $order;
    }


    /**
     * Generates a unique order code with a specified length.
     *
     * The generated code consists of a prefix ('ORD-') followed by randomly
     * selected alphanumeric characters that exclude ambiguous ones such as
     * '0', 'O', 'I', and '1'. The method ensures uniqueness of the code
     * by recursively regenerating it in case of a duplication found in
     * the database.
     *
     * @param int $length The length of the random part of the order code. Default is 4.
     * @return string The generated unique order code.
     */
    private function generateOrderCode($length = 4) {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $code = '';

        for ($i = 0; $i < $length; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }

        if(Order::where("code", $code)->first()){
            $this->generateOrderCode();
        }

        return $code;
    }



    /**
     * Calculates the total amount for the cart, including discounts applied to each product.
     *
     * @param \Illuminate\Support\Collection $cartProducts A collection of cart products, each containing its discounted total amount.
     *
     * @return float The total cart amount after applying discounts.
     */
    private function calculateCartTotal($cartProducts): float
    {
        return array_reduce(
            $cartProducts->toArray(),
            fn($total, $product) => $total + $product["total_amount_with_discount"],
            0
        );
    }


    /**
     *
     */
    public static function liberateStockFromExpiredOrders()
    {
        // obtengo todas las ordenes expiradas y sin pagar
        $orders = Order::where('expiration_date', '<', Carbon::now())
            ->whereNotIn('status', ['Pago recibido', 'Envío realizado', 'En proceso', 'Expirado'])
            ->get();

        //todo hacerlo a mano para testearlo
        //mapeo todas las ordenes
        foreach ($orders as $order) {

            //por cada orden, mapeo todos los productos asociados
            foreach ($order->products as $orderProduct) {
                $product = $orderProduct->product;

                $productSizeRecordToUpdateStock = \App\Models\ProductVariant::where('product_id', $product->id)
                    ->where('size', $orderProduct->size)
                    ->first();

                $productSizeRecordToUpdateStock->stock += $orderProduct->quantity;
                $productSizeRecordToUpdateStock->save();

            }

            $order->status = 'Expirado';
            $order->save();
        }
    }


}
