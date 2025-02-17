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
     * Adds a product to the cart stored in the session. If the cart does not exist in the session,
     * it creates a new cart instance and stores it in the session. If the product already exists
     * in the cart, it increments the quantity. Otherwise, it adds the product as a new item
     * in the cart.
     *
     * Updates the product's total amount with the applied discount, calculates the discounted
     * price, and saves the updated cart back to the session.
     *
     * @param Product $product The product to be added to the cart.
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with the updated cart.
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
            // Adds product if it isn't stored in session
            $sessionCart[array_key_first($sessionCart)][$product->id] = [
                "name" => $product->name,
                "price" => $product->price,
                "quantity" => 1,
                "discount" => $product->discount,
                "id" => $product->id,
                "picture" => $product->pictures->first()->path,
            ];
        }

        //Multiplies (price * 0,discount) * quantity
        //To be shown in frontend checkout
        $sessionCart[array_key_first($sessionCart)][$product->id]["total_amount_with_discount_to_be_shown"] =
            round(($sessionCart[array_key_first($sessionCart)][$product->id]["price"] *
                    $this->getRemainingPercentageInDecimals($sessionCart[array_key_first($sessionCart)][$product->id]["discount"]))
                * $sessionCart[array_key_first($sessionCart)][$product->id]["quantity"], 2);


        Session::put('cart', $sessionCart);

        Session::save();

        return response()->json(Session::get('cart'));
    }



    /**
     * Elimina un producto del registro del carrito en la sesión actual
     *
     * Si el carrito queda vacío tras la eliminación del producto, se actualiza
     * el estado del carrito a EMPTY_BY_CUSTOMER.
     *
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function deleteProduct(Product $product)
    {

        $cart = Cart::find(array_key_first(Session::get('cart')));
        $cartInSession = Session::get('cart');

        unset($cartInSession[$cart->id][$product->id]);

        if (empty($cartInSession[$cart->id])) {
            $cart->status = Constants::EMPTY_BY_CUSTOMER;
            $cart->save();
        }

        Session::put('cart', $cartInSession);
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
    public function calculateCartItemsTotalAmountForEachOne($customerData)
    {

        $cart = Session::get('cart');
        $idCartStoredInDatabase = array_key_first($cart);


        return collect($cart[$idCartStoredInDatabase])->map(function ($query) use ($customerData) {
            $product = Product::find($query["id"]);
            $subtotal = round(($query["price"] * $query["quantity"]), 2);

            $totalDiscount = $query["discount"];

            $coupon = Coupon::find($customerData->coupon_id);

            $total = round($subtotal * $this->getRemainingPercentageInDecimals($totalDiscount), 2);

            return [
                "product_id" => $product->id,
                "quantity" => $query["quantity"],
                "unit_price" => $query["price"],
                "product_discount" => $query["discount"],
                "subtotal" => $subtotal,
                "coupon_discount" => $coupon->discount ?? 0,
                "total_amount_with_discount" => $total,
            ];
        });

    }

}
