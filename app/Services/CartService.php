<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Constants;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Traits\CartTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{

    use CartTrait;


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
            $sessionCart["cart_id"] = $createdCartInstance->id;
            $sessionCart["is_coupon_applied"] = false;
            Session::put('cart', $sessionCart);
        } else {
            $sessionCart = Session::get('cart');
        }


        $productVariant = ProductVariant::where('size', $request->size)
                            ->where('color', $request->color)
                            ->where('product_id', $request->product_id)
                            ->firstOrFail();

        if($productVariant->stock < $request->quantity){
            throw new \Exception("No hay stock suficiente. Por favor seleccione menos cantidad.");
        }

        $isProductAlreadyInCart = false;


        //si el producto ya se encuentra en el carrito
        //aumenta la cantidad
        if (isset($sessionCart['products'])) {
            foreach ($sessionCart['products'] as $index => &$item) {
                if ($item['product_variant_id'] == $productVariant->id) {
                    $item['quantity'] += (int) $request->quantity;
                    $isProductAlreadyInCart = true;
                    break;
                }
            }

            unset($item);
        }

        if (!$isProductAlreadyInCart) {
            $sessionCart['products'][] = [
                'product_variant_id' => $productVariant->id,
                'quantity' => (int) $request->quantity,
            ];
        }

        $this->saveCartInSession($sessionCart);

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
    public function deleteProduct(Request $request)
    {

        $cartInSession = Session::get('cart');
        $productVariantInCartIndex = 0;

        foreach (collect($cartInSession["products"]) as $index => $productVariantInCart){
            if($productVariantInCart["product_variant_id"] == $request->product_variant_id){
                $productVariantInCartIndex = $index;
            }
        }

        unset($cartInSession["products"][$productVariantInCartIndex]);

        if (empty($cartInSession)) {
            $cart = Cart::find($cartInSession["cart_id"]);
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
     * Calculates the total amount for each product in the cart after applying discounts.
     *
     * @param object $customerData An object containing customer-related data, including applied coupons.
     *
     * @return \Illuminate\Support\Collection A collection of product details, including product ID, quantity, unit price,
     *                                         discount, subtotal, and total amount after discounts.
     */
    public function createArrayOfProductsInCart($customerData)
    {

        $cart = $this->getCart();
        $idCartStoredInDatabase = array_key_first($cart);

        return collect($cart[$idCartStoredInDatabase]["products"])->map(function ($query) use ($customerData) {
            $data = [];

            foreach ($query["sizes"] as $size => $product){
                $product = Product::find($query["id"]);
                $coupon_id = $this->getCouponAppliedId();

                $data[] = [
                    "product_id" => $product->id,
                    "size" => $size,
                    "quantity" => $product["quantity"],
                    "unit_price" => $query["price"],
                    "product_discount" => $query["discount"],
                    "total" => $product["total_amount_with_discounts"],
                    "coupon_discount" => Coupon::find($coupon_id)->discount ?? null,
                ];
            }

            return $data;
        });

    }

}
