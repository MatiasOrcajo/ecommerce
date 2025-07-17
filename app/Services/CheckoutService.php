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
use MercadoPago\MercadoPagoConfig;

class CheckoutService
{

    use CartTrait;

    public function __construct(private OrderService         $orderService,
                                private MercadoPagoService   $mpService,
                                private CartService   $cartService,
                                private EmailService   $emailService,
    )
    {
    }


    /**
     * Processes an order based on the provided request data, including payment and shipping methods.
     *
     * Decodes the incoming JSON data to extract the payment and shipping methods.
     * Creates and saves a new order using the provided data and selected methods.
     * Depending on the selected payment method, it either returns a JSON response with a success status and
     * order route, or initiates a payment process using an external service.
     *
     * @param Request $request The HTTP request containing order data in JSON format.
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response A JSON response or the result of an external payment process.
     */
    public function processOrder(Request $request)
    {

        $paymentMethods = [
            "bank-transfer" => "Transferencia bancaria",
            "mercado-pago" => "Mercado Pago",
            "cash" => "Efectivo"
        ];

        $shippingMethods = [
            "correo-argentino" => "Correo Argentino",
            "andreani" => "Andreani",
            "take-away" => "Retiro en CABA"
        ];


        $request = json_decode($request->data);
        $selectedPaymentMethod = $request->payment_method;

        $order = $this->orderService->create($request, $this->getCartInfo());
        $order->shipping_method = $shippingMethods[$request->shipping_method];
        $order->payment_method = $paymentMethods[$selectedPaymentMethod];
        $order->save();
        Session::put("email_validated_$order->code", true);

        if ($selectedPaymentMethod == "bank-transfer" || $selectedPaymentMethod == "cash") {

            $this->cartService->clearCart();

            $this->emailService->sendOrderSuccess($order);

            return response()->json([
                "success" => true,
                "init_point" => route('order-success', $order->code)
            ]);
        }

        if ($selectedPaymentMethod == "mercado-pago") {

            return $this->mpService->createPreference($order);

        }

    }


    /**
     * Obtiene toda la información del carrito
     *
     * @return array
     */
    public function getCartInfo()
    {
        $cart = $this->getCart();
        $data = [];

        if($cart["is_coupon_applied"]){
            $data["is_coupon_applied"] = $cart["is_coupon_applied"];
            $data["coupon_code"] = $cart["coupon_code"];
            $data["coupon_discount"] = $cart["coupon_discount"];

        }

        $order_total = 0;

        foreach ($cart["products"] as $productInCart) {
            $productVariant = ProductVariant::find($productInCart["product_variant_id"]);
            $product = $productVariant->product;
            $subtotal = $product->price * $productInCart["quantity"];
            $total = $product->discount ? $product->price * $this->getRemainingPercentageInDecimals($product->discount) : $product->price;
            $total = $total * $productInCart["quantity"];

            $productVariantColorPic = ProductVariant::where('color', $productVariant->color)
                ->where('product_id', $product->id)
                ->whereHas('pictures')
                ->first()
                ->pictures
                ->first()
                ->path;


            $data["products"][] = [
                "product_name" => $product->name,
                "quantity" => $productInCart["quantity"],
                "subtotal" => $subtotal,
                "total" => $total,
                "pic" => $productVariantColorPic,
                "color" => $productVariant->color,
                "size" => $productVariant->size,
                "color_name" => $productVariant->color_name,
                "slug" => $product->slug

            ];


            $order_total += $total;
        }

        $data["order_total"] = $order_total;
        $data["order_total_after_coupon_applied"] = $cart["is_coupon_applied"] ? $data["order_total"] * $this->getRemainingPercentageInDecimals(Coupon::where("code", $cart["coupon_code"])->first()->discount) : $data["order_total"];

        return $data;
    }


}
