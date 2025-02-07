<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{


    public function __construct(private readonly ProductService $productService, private readonly CouponService $couponService)
    {

    }

    /**
     * Añade un producto al carrito y lo guarda en la sesión.
     *
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public static function addProduct(Product $product)
    {
        // Obtener el carrito actual de la sesión, o un array vacío si no existe
        $cart = Session::get('cart', []);

        // Verificar si el producto ya está en el carrito y actualizar la cantidad
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
        } else {
            // Agregar nuevo producto si no está en el carrito
            $cart[$product->id] = [
                "name" => $product->name,
                "price" => $product->price,
                "quantity" => 1,
                "discount" => $product->discount,
                "id" => $product->id
            ];
        }

        // Guardar el carrito actualizado en la sesión
        Session::put('cart', $cart);

        Session::save();

        return response()->json(Session::get('cart'));
    }

    /**
     *
     * Elimina todos los elementos del carrito de la sesión.
     *
     * @return void
     *
     */
    public function clearCart()
    {
        Session::forget('cart');
    }


    /**
     *
     * Retorna el total de lo que se encuentra en el carrito
     *
     * @param $customerData
     * @return \Illuminate\Support\Collection
     *
     */
    public function calculateTotalAmount($customerData)
    {

        $cart = Session::get('cart');

        return collect($cart)->map(function ($query) use ($customerData) {
            $product = Product::find($query["id"]);
            $subtotal = round(($query["price"] * $query["quantity"]), 2);
            $total = $subtotal;

            if ($query["discount"] != 0) {
                $total = round($subtotal - ($subtotal * $query["discount"]) / 100, 2);
            }


            //Valida el cupón en caso de haber y aplica descuento
            $coupon = Coupon::find($customerData->coupon);

            if ($coupon) {
                $total = $total - ($total * $coupon->discount) / 100;
            }

            return [
                "product_id" => $product->id,
                "quantity" => $query["quantity"],
                "unit_price" => $query["price"],
                "discount" => $query["discount"],
                "subtotal" => $subtotal,
                "total_amount_with_discount" => $total,
            ];
        });

    }

}
