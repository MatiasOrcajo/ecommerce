<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OrderService
{


    public function __construct(private readonly CustomerService $customerService,
                                private readonly CartService $cartService,
                                private readonly OrderProducts $orderProducts)
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

        // Extract the list of cart products with total amounts
        $cartProducts = $this->cartService->calculateTotalAmount($customerData);

        // Calculate the total cart amount (extract function)
        $cartTotal = $this->calculateCartTotal($cartProducts);

        // Retrieve coupon if available
        $coupon = Coupon::where('code', $customerData->coupon)->first();

        // Extract shipping address (extract variable)
        $shippingAddress = sprintf(
            "%s, %s, %s, %s",
            $customerData->address,
            $customerData->locality,
            $customerData->province,
            $customerData->zip_code
        );

        // Create customer and associate with the order
        $customer = $this->customerService->create($customerData);

        // Create order (introduce variable)
        $order = Order::create([
            'customer_id' => $customer->id,
            'order_date' => Carbon::now(),
            'total_amount' => round($cartTotal, 2),
            'shipping_address' => $shippingAddress,
            'coupon_id' => $coupon->id ?? null,
        ]);

        // Link cart products to the order (extract variable)
        foreach ($cartProducts as $product) {
            $this->orderProducts->create($order, $product);
        }

        return $order;
    }


    // Extracted function for calculating total cart amount
    private function calculateCartTotal($cartProducts): float
    {
        return array_reduce(
            $cartProducts->toArray(),
            fn($total, $product) => $total + $product["total_amount_with_discount"],
            0
        );
    }


}
