<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Constants;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;
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
    public function addProduct(Product $product, Request $request)
    {


        $sessionCart = null;

        //If cart isn't stored in session
        //Create the Cart record and assign its id to the cart to be stored in session for reference
        if (!Session::has('cart')) {
            $createdCartInstance = $this->create();
            $createdCartInstance->status = Constants::ACTIVE;
            $createdCartInstance->save();
            $sessionCart[$createdCartInstance->id] = [];
            Session::put('cart', $sessionCart);
        } else {
            $sessionCart = Session::get('cart');
        }


        if( isset($sessionCart[array_key_first($sessionCart)][$product->id]["sizes"])){

            $newSize = [
                $request->size =>
                    [
                    "quantity" => $request->quantity,
                ]
            ];

            $key = array_key_first($sessionCart);
            $sizes = &$sessionCart[$key][$product->id]["sizes"];

            $sizes = array_merge($sizes, $newSize);

            $sessionCart[array_key_first($sessionCart)][$product->id]["sizes"] = $sizes;
        }
        else{

            $sessionCart[array_key_first($sessionCart)][$product->id] = [
                "id" => $product->id,
                "name" => $product->name,
                "price" => $product->price,
                "sizes" => [
                    $request->size => [
                        "quantity" => $request->quantity,
                    ]
                ],
                "discount" => $product->discount,
                "picture" => $product->pictures->first()->path,
            ];
        }

        Session::put('cart', $sessionCart);

        Session::save();

        $sessionCart = $this->calculateTotalAmountByProductInCart($product);

        Session::put('cart', $sessionCart);

        Session::save();

        return response()->json(Session::get('cart'));
    }


    /**
     * Calculates the total amount for each size of a product in the cart, applying discounts
     * and storing the calculated amount back into the cart for each size.
     *
     * It retrieves the current cart from the session, iterates through the sizes of the specified
     * product, applies the applicable discount, and calculates the total amount for each size based
     * on its quantity and discounted price. The updated cart is then returned.
     *
     * @param Product $product The product whose sizes' total amounts need to be calculated.
     * @return array The updated cart with total amounts for each size recalculated.
     */
    private function calculateTotalAmountByProductInCart(Product $product)
    {
        $sessionCart = Session::get('cart');
        $sizes = $sessionCart[array_key_first($sessionCart)][$product->id]["sizes"];
        $price = $sessionCart[array_key_first($sessionCart)][$product->id]["price"];
        $discount = $sessionCart[array_key_first($sessionCart)][$product->id]["discount"];

        foreach ($sizes as $index => $size) {

            $sessionCart[array_key_first($sessionCart)][$product->id]["sizes"][$index]["total_amount_with_discounts"] =
                round(( $price *
                        $this->getRemainingPercentageInDecimals($discount))
                    * $size["quantity"], 2);

        }

        return $sessionCart;
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
