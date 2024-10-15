<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use mysql_xdevapi\Exception;

class CartProducts extends Model
{
    use HasFactory;

    protected $table = 'cart_products';
    protected $guarded;


    /**
     * Añade un producto al registro del usuario en la tabla cart_products
     *
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse|void
     *
     */
    public static function addProduct(Product $product)
    {

        if (Auth::guest()) {
            self::addGuestProduct($product);
            return;
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

    public static function addGuestProduct(Product $product)
    {
        $arr = [];
        session()->put('cart', $arr);

        $cart = session()->get('cart');
        $cart[$product->id] = [
            "name" => $product->name,
            "price" => $product->price,
            "quantity" => 1
        ];

        dd(session()->all());
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
