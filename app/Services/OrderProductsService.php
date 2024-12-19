<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class OrderProductsService
{


    public function __construct()
    {
    }


    /**
     * Create a new order product record in the database using mass assignment.
     *
     * @param Order $order The order associated with the product.
     * @param array $product An associative array containing product details
     *
     * @return void
     */
    public function create(Order $order, array $product)
    {
        // Crear el registro utilizando asignación masiva.
        \App\Models\OrderProducts::create([
            'product_id'    => $product['product_id'],
            'order_id'      => $order->id,
            'quantity'      => $product['quantity'],
            'unit_price'    => $product['unit_price'],
            'discount'      => $product['discount'],
            'subtotal'      => $product['subtotal'],
            'total_amount'  => $product['total_amount_with_discount'],
        ]);
    }


    /**
     * Maps order products associated with a given order to an array of item details.
     *
     * @param int $orderId The ID of the order whose products are to be mapped.
     *
     * @return array An array of items containing product-related details such as
     *               ID, title, description, picture URL, category ID, quantity,
     *               currency, and unit price.
     */
    public function mapOrderProductToItem($orderId)
    {
        $order = Order::with(['products.product.pictures'])->find($orderId);

        return $order->products->reduce(function(array $acc, \App\Models\OrderProducts $orderProduct){
            $acc[] = [
                "id" => $orderProduct->product->id,
                "title" => $orderProduct->product->name,
                "description" => $orderProduct->product->description,
                "picture_url" => $orderProduct->product->pictures->first()->path,
                "category_id" => $orderProduct->product->category_id,
                "quantity" => $orderProduct->quantity,
                "currency_id" => "ARS",
                "unit_price" => $orderProduct->total_amount,
            ];

            return $acc;
        }, []);
    }



}
