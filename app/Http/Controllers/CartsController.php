<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartProducts;
use App\Models\Constants;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartsController extends Controller
{

    public function __construct(private readonly CartService $cartService)
    {
    }

    /**
     * Crea un carrito. Se va a crear una sola vez junto con el registro
     * del usuario
     *
     * @return void
     */
    public function create()
    {
        $cart = new Cart();
        $cart->user_id = Auth::user()->id;
        $cart->status = Constants::EMPTY;

        $cart->save();
    }


    /**
     * Añade un producto al carrito del usuario
     *
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function addProduct(Product $product)
    {
        return $this->cartService->addProduct($product);
    }


    /**
     * Elimina un producto del carrito del usuario
     *
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteProduct(Product $product)
    {
        return CartProducts::deleteProduct($product);
    }

}
