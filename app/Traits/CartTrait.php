<?php

namespace App\Traits;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Session;

trait CartTrait
{
    public function __construct()
    {
        //        if($this->getCart() != null){
        //            $this->calculateCartTotalAmount();
        //        }
    }

    /**
     * Retrieves the ID of the applied coupon from the session cart.
     *
     * @return int|string|null The ID of the applied coupon or null if no coupon is applied.
     */
    public function getCouponAppliedId()
    {
        return Session::get('cart')['coupon_id'] ?? null;
    }

    /**
     * Retrieves the total order amount from the session cart.
     *
     * @return float The total order amount stored in the session cart.
     */
    public function getCartTotal()
    {
        return Session::get('cart')['order_total'];
    }

    /**
     * Retrieves the current cart stored in the session.
     *
     * @return mixed The cart data stored in the session, or null if no cart exists.
     */
    public function getCart()
    {
        $cartData = Session::get('cart');

        if (is_null($cartData) || ! isset($cartData['products'])) {
            return ['products' => []];
        }

        $cart = collect($cartData);

        $cart['products'] = collect($cart['products'])
            ->map(function (array $item) {

                $variant = ProductVariant::with('product')
                    ->find($item['product_variant_id']);

                if (! $variant) {
                    return $item;
                }

                return [
                    'product_variant_id' => $variant->id,
                    'quantity' => $item['quantity'],
                    'name' => $variant->product->name,
                    'image' => $variant->findFirstSimilarVariantWithPicture(),
                ];
            })
            ->toArray();

        return $cart;
    }

    /**
     * Calculates the remaining percentage in decimals after applying a discount.
     *
     * This method converts the provided discount percentage into its decimal equivalent
     * representing the remaining percentage.
     *
     * @param  float|int  $discount  The discount percentage to calculate the remaining value.
     * @return float The remaining percentage in decimal form.
     */
    private function getRemainingPercentageInDecimals($discount)
    {
        return 1 - ($discount / 100);
    }

    /**
     * Calculates the new total after applying a coupon discount.
     *
     * @param  array  $sessionCart  The session cart containing order details and coupon discount.
     * @return float The updated total after applying the coupon discount.
     */
    public function calculateNewTotalAfterApplyingCoupon($sessionCart)
    {
        $previousTotal = $sessionCart['order_total'];

        return $previousTotal * $this->getRemainingPercentageInDecimals($sessionCart['coupon_discount']);
    }

    /**
     * Calculates total amount for each item in cart
     *
     * al pedo
     *
     * @return void
     */
    public function calculateTotalForEachItemInCart()
    {
        $sessionCart = Session::get('cart');
        $productsInCart = $sessionCart['products'];

        foreach ($productsInCart as $index => $productInCart) {

            $product = ProductVariant::find($productInCart['product_variant_id'])->product;
            $totalBeforeDiscounts = $product->price * $productInCart['quantity'];

            if ($product->discount != null) {
                $totalAfterDiscounts = $product->price * $productInCart['quantity'] * $this->getRemainingPercentageInDecimals($product->discount);
                $productInCart['totalAfterDiscounts'] = $totalAfterDiscounts;
            }

            $productInCart['total_before_discounts'] = $totalBeforeDiscounts;

            $sessionCart['products'][$index] = $productInCart;

        }

        $this->saveCartInSession($sessionCart);

    }

    /**
     * Calculates the total amount of the cart, including handling coupon discounts if applied.
     *
     * This method iterates over the products in the cart to compute the total amount based on product-specific calculations.
     * It also updates the session cart with the computed totals, applying coupon discounts when necessary.
     *
     * @return array The updated session cart containing the recalculated totals.
     */
    public function calculateCartTotalAmount()
    {
        $sessionCart = Session::get('cart');
        $productsInCart = $sessionCart['products'];
        $total = 0;

        // calcula el total de cada producto sin tener en cuenta los descuentos por cupón
        foreach ($productsInCart as $index => $product) {
            $product = ProductVariant::find($product['product_variant_id'])->product;
            $total += $this->calculateTotalAmountByProductInCart($sessionCart, $product);
        }

        // este dato debe ser siempre el total sin descuentos por cupón
        $sessionCart['old_order_total_before_coupon_was_applied'] = $total;
        // si existe un cupón de descuento aplicado
        if ($sessionCart['is_coupon_applied']) {

            $sessionCart['order_total'] = $total * $this->getRemainingPercentageInDecimals($sessionCart['coupon_discount']);

        } else {
            // si no existe
            $sessionCart['order_total'] = $total;
        }

        $this->saveCartInSession($sessionCart);

        return $sessionCart;

    }

    /**
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
     * @param  Product  $product  The product whose sizes' total amounts need to be calculated.
     * @return array The updated cart with total amounts for each size recalculated.
     */
    private function calculateTotalAmountByProductInCart(&$sessionCart, Product $product)
    {
        $sizes = $sessionCart['products'][$product->id]['sizes'];
        $price = $sessionCart['products'][$product->id]['price'];
        $discount = $sessionCart['products'][$product->id]['discount'];

        $acc = 0;

        foreach ($sizes as $index => $size) {

            $sessionCart['products'][$product->id]['sizes'][$index]['total_amount_with_discounts'] =
                round(($price *
                        $this->getRemainingPercentageInDecimals($discount))
                    * $size['quantity'], 2);

            $sessionCart['products'][$product->id]['sizes'][$index]['subtotal'] = $price * $size['quantity'];

            $acc += $sessionCart['products'][$product->id]['sizes'][$index]['total_amount_with_discounts'];

            $this->saveCartInSession($sessionCart);
        }

        return $acc;
    }
}
