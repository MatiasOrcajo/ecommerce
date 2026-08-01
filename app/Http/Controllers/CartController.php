<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use App\Traits\CartTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    use CartTrait;

    public function __construct(private readonly CartService $cartService)
    {
        //        if($this->getCart() != null){
        //            $this->calculateCartTotalAmount();
        //        }
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
     * @return JsonResponse
     */
    public function addProduct(Product $product, Request $request)
    {

        return $this->cartService->addProduct($product, $request);
    }

    /**
     * Elimina un producto del carrito del usuario
     *
     * @param  Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteProduct(Request $request)
    {
        return $this->cartService->deleteProduct($request);

    }

    /**
     * Calcula el número total de elementos en el carrito
     *
     * @return int
     */
    public function calculateCartTotalItems()
    {

        return $this->getCart();
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

    public function updateProductQuantity(Request $request)
    {

        $cart = Session::get('cart');

        foreach ($cart['products'] as $index => $productVariantInCart) {
            if ($productVariantInCart['product_variant_id'] == $request->productVariantId) {

                if ($request->action == 'plus') {
                    $cart['products'][$index]['quantity']++;
                } else {
                    $cart['products'][$index]['quantity']--;
                }

                if ($cart['products'][$index]['quantity'] == 0) {
                    unset($cart['products'][$index]);
                }
            }
        }

        Session::put('cart', $cart);

        return response()->json(Session::get('cart'));

    }
}
