<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use mysql_xdevapi\Exception;

class CartProducts extends Model
{
    use HasFactory;

    protected $table = 'cart_products';
    protected $guarded;


    /**
     * Añade un producto al registro del usuario en la tabla cart_products
     *
     * Si el usuario no está logueado, solo se ejecuta la funcion addGuestProduct()
     *
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse|void
     *
     */
    public static function addProduct(Product $product)
    {

        if (Auth::guest()) {
            return self::addGuestProduct($product);
        }

        $productInCart = Auth::user()->cart->products->where('product_id', $product->id);

        if ($productInCart) {
            throw new Exception("Ya tenés este producto en el carrito");
        }

        $cartProducts = new CartProducts();
        $cartProducts->product_id = $product->id;
        $cartProducts->cart_id = Auth::user()->cart->id;

        $cartProducts->save();

        return response()->json([
            'message' => 'Producto añadido!'
        ], 200);

    }

    /**
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public static function deleteProduct(Product $product)
    {
        $productInCart = Auth::user()->cart->products->where('product_id', $product->id);
        $productInCart->delete();

        if (empty(Auth::user()->cart->products)) {
            $cart = Auth::user()->cart;
            $cart->status = Constants::EMPTY_BY_CUSTOMER;
            $cart->save();
        }

        return response()->json([
            'message' => 'Producto eliminado!'
        ], 200);

    }
}
