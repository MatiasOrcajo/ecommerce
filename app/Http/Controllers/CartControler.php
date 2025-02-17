<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartProducts;
use App\Models\Constants;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartControler extends Controller
{

    public function __construct(private readonly CartService $cartService)
    {
    }



    public function create()
    {
        $cart = $this->cartService->create();
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
        return $this->cartService->deleteProduct($product);

    }


    /**
     * Vacía el carrito del usuario
     *
     * @return void
     */
    public function clearCart()
    {
        $this->cartService->clearCart();
    }

}
