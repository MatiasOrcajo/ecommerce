<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Session;

class OrderProductsService
{
    public function __construct() {}

    /**
     * Creates entries in the OrderProducts table by iterating through the products and their sizes
     * obtained from the session cart. Assigns necessary details such as product ID, size, order ID,
     * quantity, unit price, discount, and the final total amount with discounts while considering
     * any applied coupons.
     *
     * @param  \App\Models\Order  $order  The order model instance for which the products are being created.
     * @return void
     */
    public function create(Order $order, array $cartInfo)
    {
        foreach ($cartInfo['products'] as $index => $product) {

            // descuento el stock
            $productInDb = Product::where('name', $product['product_name'])->first();
            $productVariant = ProductVariant::where('product_id', $productInDb->id)
                ->where('color', $product['color'])
                ->where('size', $product['size'])
                ->first();

            $productVariant->decrement('stock', $product['quantity']);

            if ($productVariant->stock < 0) {

                throw new \Exception('No hay stock suficiente del producto '.$productInDb->name.'El stock actual es: '.$productVariant->stock);
            }

            // Crear el registro utilizando asignación masiva.
            \App\Models\OrderProducts::create([
                'product_variants_id' => $productVariant->id,
                'order_id' => $order->id,
                'quantity' => $product['quantity'],
                'unit_price' => $productInDb->price,
                'discount' => $productInDb->discount,
                // el total de la orden puede ser menor
                // dependiendo de si está aplicado un cupón o no
                // el precio final real del producto en ese caso
                // deberá evaluarse haciendo el descuento de cupón de la orden
                'total' => $product['total'],
            ]);

        }

    }

    /**
     * Maps order products associated with a given order to an array of item details.
     *
     * @param  int  $orderId  The ID of the order whose products are to be mapped.
     * @return array An array of items containing product-related details such as
     *               ID, title, description, picture URL, category ID, quantity,
     *               currency, and unit price.
     */
    public function mapOrderProductToItem($orderId)
    {
        $order = Order::with(['products.product.pictures'])->find($orderId);

        return $order->products->reduce(function (array $acc, \App\Models\OrderProducts $orderProduct) {
            $acc[] = [
                'id' => $orderProduct->product->id,
                'title' => $orderProduct->product->name,
                'description' => $orderProduct->product->description,
                'picture_url' => $orderProduct->product->pictures->first()->path,
                'category_id' => $orderProduct->product->category_id,
                'quantity' => $orderProduct->quantity,
                'currency_id' => 'ARS',
                'unit_price' => $orderProduct->total_amount / $orderProduct->quantity,
            ];

            return $acc;
        }, []);
    }
}
