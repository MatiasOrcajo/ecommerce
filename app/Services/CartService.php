<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService{


    public function __construct(private readonly ProductService $productService)
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
     * Retorna el total de lo que se encuentra en el carrito
     *
     * @return float|int
     */
    public function calculateTotalAmount()
    {
        $cart = Session::get('cart');
        $total = 0;

        foreach (collect($cart) as $q){
            $product = Product::find($q["id"]);
            $total += (($product->price - $this->productService->calculateProductPriceWithDiscount($product))  * $q["quantity"]);
        }



        return round($total, 2);

    }

}
