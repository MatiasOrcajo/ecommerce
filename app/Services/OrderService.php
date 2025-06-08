<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Order;
use App\Traits\CartTrait;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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

        // Calculates total amount to be paid for every product
        $cartProducts = $this->cartService->createArrayOfProductsInCart($customerData);

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
            'shipping_address' => $shippingAddress,
            'coupon_id' => $this->getCouponAppliedId(),
        ]);

        // Link cart products to the order (extract variable)
        foreach ($cartProducts as $product) {
            $this->orderProducts->create($order, $product);
        }

        return $order;
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


}
