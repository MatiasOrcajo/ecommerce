<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartProducts;
use App\Models\Constants;
use App\Models\Product;
use App\Services\CartService;
use App\Traits\CartTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{

    use CartTrait;

    public function __construct(private readonly CartService $cartService)
    {
        $this->calculateCartTotalAmount();
    }

    public function seeCart()
    {
        dd(Session::get('cart'));
    }

    public function create()
    {
        $cart = $this->cartService->create();
    }


    /**
     * Añade un producto al carrito del usuario
     *
     * @param Product $product
     * @param Request $request
     * @return JsonResponse
     */
    public function addProduct(Product $product, Request $request)
    {

        return $this->cartService->addProduct($product, $request);
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
