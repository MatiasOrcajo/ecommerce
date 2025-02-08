<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Constants;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{


    public function __construct(private readonly ProductService $productService, private readonly CouponService $couponService)
    {

    }


    /**
     *
     * Creates a new cart associated with the current authenticated user.
     *
     * @return \Illuminate\Database\Eloquent\Model
     *
     */
    public function create()
    {
        return Cart::create([
            "status" => Constants::EMPTY
        ]);
    }

    /**
     * Añade un producto al carrito y lo guarda en la sesión.
     *
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function addProduct(Product $product)
    {

        $sessionCart = null;

        //If cart isn't stored in session
        //Create the Cart record and assign its id to the cart to be stored in session for reference
        if(!Session::has('cart')){
            $createdCartInstance = $this->create();
            $createdCartInstance->status = Constants::ACTIVE;
            $createdCartInstance->save();
            $sessionCart[$createdCartInstance->id] = [];
            Session::put('cart', $sessionCart);
        }
        else{
            $sessionCart = Session::get('cart');
        }

        // Verificar si el producto ya está en el carrito y actualizar la cantidad
        if (isset($sessionCart[array_key_first($sessionCart)][$product->id])) {
            $sessionCart[array_key_first($sessionCart)][$product->id]['quantity']++;
        } else {
            // Agregar nuevo producto si no está en el carrito
            $sessionCart[array_key_first($sessionCart)][$product->id] = [
                "name" => $product->name,
                "price" => $product->price,
                "quantity" => 1,
                "discount" => $product->discount,
                "id" => $product->id
            ];
        }

        // Guardar el carrito actualizado en la sesión
        Session::put('cart', $sessionCart);

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
     * Calculates and returns the remaining percentage in decimal form given a discount.
     *
     * @param float|int $discount
     * @return float
     *
     */
    private function getRemainingPercentageInDecimals($discount)
    {
        return 1 - ($discount / 100);
    }


    /**
     * Calculates the total amount for each product in the cart after applying discounts.
     *
     * @param object $customerData An object containing customer-related data, including applied coupons.
     *
     * @return \Illuminate\Support\Collection A collection of product details, including product ID, quantity, unit price,
     *                                         discount, subtotal, and total amount after discounts.
     */
    public function calculateTotalAmount($customerData)
    {

        $cart = Session::get('cart');
        $idCartStoredInDatabase = array_key_first($cart);


        return collect($cart[$idCartStoredInDatabase])->map(function ($query) use ($customerData) {
            $product = Product::find($query["id"]);
            $subtotal = round(($query["price"] * $query["quantity"]), 2);

            $totalDiscount = $query["discount"];

            $coupon = Coupon::find($customerData->coupon);

            if ($coupon) {
                $totalDiscount += $coupon->discount;
            }

            $total = round($subtotal * $this->getRemainingPercentageInDecimals($totalDiscount), 2);

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
