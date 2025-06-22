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
     * Creates entries in the OrderProducts table by iterating through the products and their sizes
     * obtained from the session cart. Assigns necessary details such as product ID, size, order ID,
     * quantity, unit price, discount, and the final total amount with discounts while considering
     * any applied coupons.
     *
     * @param \App\Models\Order $order The order model instance for which the products are being created.
     * @return void
     */
    public function create(Order $order)
    {
        $cart = Session::get('cart')[array_key_first(Session::get('cart'))];
        $products = $cart["products"];
        foreach ($products as $index => $product) {
            foreach($product["sizes"] as $size => $value){

                //descuento el stock
                $productInDb = Product::find($product["id"]);
                $productInDb->sizes->where('size', $size)->first()->decrement('stock', $value['quantity']);

                if($productInDb->sizes->where('size', $size)->first()->stock < 0){

                    throw new \Exception("No hay stock suficiente del producto ".$productInDb->name. "El stock actual es: " . $productInDb->sizes->where('size', $size)->first()->stock);
                }

                // Crear el registro utilizando asignación masiva.
                \App\Models\OrderProducts::create([
                    'product_id'    => $product["id"],
                    'size'          => $size,
                    'order_id'      => $order->id,
                    'quantity'      => $value['quantity'],
                    'unit_price'    => $value['subtotal'],
                    'discount'      => $product['discount'],
                    //el total de la orden puede ser menor
                    //dependiendo de si está aplicado un cupón o no
                    //el precio final real del producto en ese caso
                    //deberá evaluarse haciendo el descuento de cupón de la orden
                    'total'  => $value['total_amount_with_discounts'],
                ]);
            }

        }

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
                "unit_price" => $orderProduct->total_amount / $orderProduct->quantity,
            ];

            return $acc;
        }, []);
    }



}
