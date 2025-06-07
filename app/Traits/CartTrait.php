<?php

namespace App\Traits;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

trait CartTrait
{

    /**
     * Calculates the remaining percentage in decimals after applying a discount.
     *
     * This method converts the provided discount percentage into its decimal equivalent
     * representing the remaining percentage.
     *
     * @param float|int $discount The discount percentage to calculate the remaining value.
     * @return float The remaining percentage in decimal form.
     */
    private function getRemainingPercentageInDecimals($discount)
    {
        return 1 - ($discount / 100);
    }


    /**
     * Calculates the new total after applying a coupon discount.
     *
     * @param array $sessionCart The session cart containing order details and coupon discount.
     * @return float The updated total after applying the coupon discount.
     */
    public function calculateNewTotalAfterApplyingCoupon($sessionCart)
    {
        $previousTotal = $sessionCart[array_key_first($sessionCart)]["order_total"];

        return $previousTotal * $this->getRemainingPercentageInDecimals($sessionCart[array_key_first($sessionCart)]["coupon_discount"]);
    }



    public function calculateCartTotalAmount()
    {
        $sessionCart = Session::get('cart');
        $productsInCart = $sessionCart[array_key_first($sessionCart)]["products"];
        $total = 0;

        foreach ($productsInCart as $index => $product) {
            $product = Product::find($index);
            $total += $this->calculateTotalAmountByProductInCart($sessionCart, $product);
        }

        $sessionCart[array_key_first($sessionCart)]["order_total"] = $total;

        $this->saveCartInSession($sessionCart);

        return $sessionCart;

    }


    /**
     * @param $cart
     * @return void
     */
    private function saveCartInSession($cart)
    {
        Session::put('cart', $cart);
        Session::save();
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
    private function calculateTotalAmountByProductInCart($sessionCart, Product $product)
    {
        $sizes = $sessionCart[array_key_first($sessionCart)]["products"][$product->id]["sizes"];
        $price = $sessionCart[array_key_first($sessionCart)]["products"][$product->id]["price"];
        $discount = $sessionCart[array_key_first($sessionCart)]["products"][$product->id]["discount"];

        $acc = 0;

        foreach ($sizes as $index => $size) {

            $sessionCart[array_key_first($sessionCart)]["products"][$product->id]["sizes"][$index]["total_amount_with_discounts"] =
                round(( $price *
                        $this->getRemainingPercentageInDecimals($discount))
                    * $size["quantity"], 2);

            $acc += $sessionCart[array_key_first($sessionCart)]["products"][$product->id]["sizes"][$index]["total_amount_with_discounts"];
        }


        return $acc;
    }


}
