<?php

namespace App\Services;

use App\Jobs\FacebookPurchaseEventJob;
use App\Jobs\SendNewSaleEmail;
use App\Jobs\SendOrderSuccessEmail;
use App\Models\Cart;
use App\Models\Constants;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Traits\CartTrait;
use Carbon\Carbon;
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
            "andreani" => "Andreani",
            "take-away" => "Retiro en CABA",
            "FLEX" => $this->determinesIfFlexShippingAppliesForToday(),
        ];

        $http = $request;
        $request = json_decode($request->data);
        $selectedPaymentMethod = $request->payment_method;

        $order = $this->orderService->create($request, $this->getCartInfo());
        $order->shipping_method = $shippingMethods[$request->shipping_method];
        $order->payment_method = $paymentMethods[$selectedPaymentMethod];
        $order->observations = $request->observations;
        $order->valid_for_arrives_today = $request->shipping_method == "FLEX";

        $order->ctx = [
            'ip'         => $http->ip(),
            'user_agent' => $http->userAgent(),
            'url'        => $http->fullUrl(),
            'fbc'        => $http->cookie('_fbc'),
            'fbp'        => $http->cookie('_fbp'),
            'external_id'=> optional($http->user())->id ?? $http->session()->getId(),
            'email'      => optional($http->user())->email,
            'phone'      => optional($http->user())->phone,
        ];

        $order->save();

        Session::put("email_validated_$order->code", true);


        if ($selectedPaymentMethod == "bank-transfer" || $selectedPaymentMethod == "cash") {

            $discountByPayment = 0.9;

            if ($selectedPaymentMethod == "cash"){
                $discountByPayment = 0.8;

            }
            $this->cartService->clearCart();

            $order->total_amount = $order->total_amount * $discountByPayment;
            $order->save();

            SendOrderSuccessEmail::dispatch($order->id)->delay(now()->addSeconds(5));
            SendNewSaleEmail::dispatch($order->id)->delay(now()->addSeconds(5));

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
     * Modifica la variable $applyTwoForOne (true / false) para
     * activar o desactivar la promoción 2×1 sin tocar otro código.
     *
     * @return array
     */
    public function getCartInfo()
    {
        /*-----------------------------------------------------------
         | Cambia este valor para controlar la promo 2×1
         +----------------------------------------------------------*/
        $applyTwoForOne = true;

        /*------------------  Datos base del carrito ----------------*/
        $cart = Session::get('cart', [
            'products'          => [],
            'is_coupon_applied' => false,
            'coupon_code'       => null,
            'coupon_discount'   => 0,
            'coupon_id'         => null,
        ]);

        $data = [ 'products' => [] ];

        if (!empty($cart['is_coupon_applied'])) {
            $data['is_coupon_applied'] = (bool) $cart['is_coupon_applied'];
            $data['coupon_code']       = $cart['coupon_code']     ?? null;
            $data['coupon_discount']   = $cart['coupon_discount'] ?? 0;
        }

        /* Helper: % → factor restante [0..1] */
        $remainingPercentage = static function ($discount): float {
            $d = is_numeric($discount) ? (float) $discount : 0.0;
            $d = max(0, min(100, $d));
            return 1 - ($d / 100);
        };

        /* Helper: genera clave de grupo (nombre sin última palabra) */
        $groupKeyFromName = static function (?string $name): string {
            $name   = trim(preg_replace('/\s+/u', ' ', (string) $name));
            $tokens = $name !== '' ? preg_split('/\s+/u', $name, -1, PREG_SPLIT_NO_EMPTY) : [];
            $base   = (is_array($tokens) && count($tokens) > 1) ? implode(' ', array_slice($tokens, 0, -1)) : $name;
            return trim(mb_strtolower(\Illuminate\Support\Str::ascii($base)));
        };

        /*------------------- Armado de ítems ----------------------*/
        $items = [];
        $line  = 0;

        foreach ($cart['products'] as $productInCart) {
            $variantId = $productInCart['product_variant_id'] ?? null;
            $qty       = (int) ($productInCart['quantity']   ?? 0);

            if (!$variantId || $qty <= 0) { $line++; continue; }

            $productVariant = \App\Models\ProductVariant::find($variantId);
            if (!$productVariant || !$productVariant->product) { $line++; continue; }

            $product          = $productVariant->product;
            $unitBasePrice    = (float) $product->price;
            $subtotal         = $unitBasePrice * $qty;
            $unitPriceAfterPd = $product->discount
                ? $unitBasePrice * $remainingPercentage($product->discount)
                : $unitBasePrice;

            $data['products'][$line] = [
                'product_variant_id' => $productVariant->id,
                'product_name'       => $product->name,
                'quantity'           => $qty,
                'pic'                => $productVariant->pictures->first()?->path ?? null,
                'subtotal'           => $subtotal,
                'total'              => 0.0,                    // se seteará más abajo
                'color'              => $productVariant->color      ?? null,
                'size'               => $productVariant->size       ?? null,
                'color_name'         => $productVariant->color_name ?? null,
                'slug'               => $product->slug,
            ];

            $items[] = [
                'line'        => $line,
                'group_key'   => $groupKeyFromName($product->name),
                'product_id'  => $product->id,
                'variant_id'  => $productVariant->id,
                'qty'         => $qty,
                'unit_price'  => $unitPriceAfterPd,
            ];

            $line++;
        }

        /*-------------------- Agrupación --------------------------*/
        $groups = [];
        foreach ($items as $it) { $groups[$it['group_key']][] = $it; }

        /*-------------------- Cálculo totales ---------------------*/
        foreach ($groups as $groupItems) {

            // --- Sin 2×1: cobra todo normalmente ---
            if (!$applyTwoForOne) {
                foreach ($groupItems as $gi) {
                    $idx = $gi['line'];
                    $data['products'][$idx]['total'] = $gi['qty'] * $gi['unit_price'];
                }
                continue;
            }

            // --- Con 2×1 ---
            $totalQty = array_sum(array_column($groupItems, 'qty'));
            if ($totalQty <= 0) continue;

            $paidUnits     = intdiv($totalQty, 2) + ($totalQty % 2);
            $paidRemaining = $paidUnits;

            // Prioriza los más caros
            usort($groupItems, fn($a,$b) => $a['unit_price'] <=> $b['unit_price'] ? ($a['unit_price'] > $b['unit_price'] ? -1 : 1) : 0);

            foreach ($groupItems as $gi) {
                $idx = $gi['line'];

                if ($paidRemaining <= 0) {
                    $data['products'][$idx]['total'] = 0.0;   // gratis
                    continue;
                }

                $paidForVariant               = min($gi['qty'], $paidRemaining);
                $data['products'][$idx]['total'] = $paidForVariant * $gi['unit_price'];
                $paidRemaining                -= $paidForVariant;
            }
        }

        /*------------------ Total del pedido ----------------------*/
        $order_total = array_reduce($data['products'], fn($c,$p)=>$c+$p['total'], 0.0);
        $data['order_total'] = $order_total;

        /*------------------ Cupón (opcional) ----------------------*/
        $orderTotalAfterCoupon = $order_total;
        if (!empty($cart['is_coupon_applied'])) {
            $coupon          = !empty($cart['coupon_code'])
                ? \App\Models\Coupon::where('code', $cart['coupon_code'])->first()
                : null;
            $discountPercent = $coupon?->discount ?? ($cart['coupon_discount'] ?? 0);
            $orderTotalAfterCoupon = $order_total * $remainingPercentage($discountPercent);
        }
        $data['order_total_after_coupon_applied'] = $orderTotalAfterCoupon;

        return $data;
    }


    public function determinesIfFlexShippingAppliesForToday()
    {
        $today = Carbon::now()->getTranslatedDayName();
        $hour = Carbon::now()->hour;
        $weekDays = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];
        $weekendDays = ["Saturday", "Sunday"];

        if(in_array($today, $weekDays) && $hour < 12){
            return "Llega hoy entre las 16 y las 22";
        }

        if(in_array($today, $weekDays) && $hour >= 12 && $today != "Friday"){
            return "Llega mañana entre las 16 y las 22";
        }

        if($today == "Friday" && $hour >= 12 || in_array($today, $weekendDays)){
            return "Llega el lunes entre las 16 y las 22";
        }
    }



}
