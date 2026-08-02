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

            $productVariant = ProductVariant::findOrFail($product['product_variant_id']);

            $productVariant->decrement('stock', $product['quantity']);

            if ($productVariant->stock < 0) {

                throw new \Exception('No hay stock suficiente del producto '.($productVariant->product->name ?? 'desconocido').' El stock actual es: '.$productVariant->stock);
            }

            \App\Models\OrderProducts::create([
                'product_variants_id' => $productVariant->id,
                'order_id' => $order->id,
                'quantity' => $product['quantity'],
                'unit_price' => $productVariant->product->price,
                'discount' => $productVariant->product->discount,
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
        $order = Order::with(['products.productVariant.product.productPictures'])->find($orderId);

        return $order->products->reduce(function (array $acc, \App\Models\OrderProducts $orderProduct) {
            $product = $orderProduct->productVariant?->product;
            if (! $product) {
                return $acc;
            }

            $acc[] = [
                'id' => $product->id,
                'title' => $product->name,
                'description' => $product->description,
                'picture_url' => $product->productPictures->first()?->path ?? $orderProduct->productVariant->pictures->first()?->path ?? '',
                'category_id' => $product->category_id,
                'quantity' => $orderProduct->quantity,
                'currency_id' => 'ARS',
                'unit_price' => $orderProduct->unit_price,
            ];

            return $acc;
        }, []);
    }
}
