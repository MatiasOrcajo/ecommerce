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

        $http = $request;
        $request = json_decode($request->data);
        $selectedPaymentMethod = $request->payment_method;

        $order = $this->orderService->create($request, $this->getCartInfo());
        $order->shipping_method = $shippingMethods[$request->shipping_method];
        $order->payment_method = $paymentMethods[$selectedPaymentMethod];
        $order->observations = $request->observations;
        $order->valid_for_arrives_today = $request->valid_for_arrives_today;
        $order->save();
        Session::put("email_validated_$order->code", true);

        $ctx = [
            'ip'         => $http->ip(),
            'user_agent' => $http->userAgent(),
            'url'        => $http->fullUrl(),
            'fbc'        => $http->cookie('_fbc'),
            'fbp'        => $http->cookie('_fbp'),
            'external_id'=> optional($http->user())->id ?? $http->session()->getId(),
            'email'      => optional($http->user())->email,
            'phone'      => optional($http->user())->phone,
        ];

        FacebookPurchaseEventJob::dispatch($order, $ctx);

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
     * @return array
     */
    public function getCartInfo()
    {
        $cart = Session::get('cart', [
            'products' => [],
            'is_coupon_applied' => false,
            'coupon_code' => null,
            'coupon_discount' => 0,
            'coupon_id' => null,
        ]);

        $data = [
            'products' => [],
        ];

        if (!empty($cart['is_coupon_applied'])) {
            $data['is_coupon_applied'] = (bool) $cart['is_coupon_applied'];
            $data['coupon_code'] = $cart['coupon_code'] ?? null;
            $data['coupon_discount'] = $cart['coupon_discount'] ?? 0;
        }

        // Convierte un % en factor restante [0..1]
        $remainingPercentage = static function ($discount): float {
            $d = is_numeric($discount) ? (float) $discount : 0.0;
            if ($d < 0) $d = 0;
            if ($d > 100) $d = 100;
            return 1 - ($d / 100);
        };

        // Genera una clave de grupo a partir del nombre sin la última palabra.
        // Normaliza: quita acentos, a minúsculas y colapsa espacios.
        $groupKeyFromName = static function (?string $name): string {
            $name = (string) $name;
            $name = trim(preg_replace('/\s+/u', ' ', $name));
            // Quitar última palabra si hay más de una
            $tokens = $name !== '' ? preg_split('/\s+/u', $name, -1, PREG_SPLIT_NO_EMPTY) : [];
            if (is_array($tokens) && count($tokens) > 1) {
                array_pop($tokens);
                $base = implode(' ', $tokens);
            } else {
                $base = $name;
            }
            // Normalización: ascii + lower
            $base = \Illuminate\Support\Str::ascii($base);
            $base = mb_strtolower($base);
            // Clave "estable" (sin espacios raros)
            return trim($base);
        };

        $items = [];   // items enriquecidos
        $line = 0;

        foreach ($cart['products'] as $productInCart) {
            $variantId = $productInCart['product_variant_id'] ?? null;
            $qty = (int) ($productInCart['quantity'] ?? 0);
            if (!$variantId || $qty <= 0) {
                $line++;
                continue;
            }

            $productVariant = \App\Models\ProductVariant::find($variantId);
            if (!$productVariant || !$productVariant->product) {
                $line++;
                continue;
            }

            $product = $productVariant->product;

            $unitBasePrice = (float) $product->price;
            $subtotal = $unitBasePrice * $qty;

            // Precio unitario tras descuento de producto (si lo tiene)
            $unitPriceAfterProductDiscount = $product->discount
                ? $unitBasePrice * $remainingPercentage($product->discount)
                : $unitBasePrice;

            $data['products'][$line] = [
                'product_variant_id' => $productVariant->id,
                'product_name' => $product->name,
                'quantity' => $qty,
                'subtotal' => $subtotal,
                'total' => 0.0, // se actualizará tras distribuir el 2x1 por grupo
                'color' => $productVariant->color ?? null,
                'size' => $productVariant->size ?? null,
                'color_name' => $productVariant->color_name ?? null,
                'slug' => $product->slug,
            ];

            $items[] = [
                'line' => $line, // índice en $data['products']
                'group_key' => $groupKeyFromName($product->name), // nombre sin última palabra
                'product_id' => $product->id,
                'variant_id' => $productVariant->id,
                'qty' => $qty,
                'unit_price' => $unitPriceAfterProductDiscount,
            ];

            $line++;
        }

        // Agrupamos por nombre base (sin última palabra)
        $groups = [];
        foreach ($items as $it) {
            $groups[$it['group_key']][] = $it;
        }

        // Para cada grupo: 2x1 a nivel de grupo y distribución de unidades pagas
        foreach ($groups as $groupKey => $groupItems) {
            $totalQty = array_sum(array_column($groupItems, 'qty'));
            if ($totalQty <= 0) {
                continue;
            }

            // 2x1: se pagan ceil(totalQty / 2)
            $paidUnits = intdiv($totalQty, 2) + ($totalQty % 2);
            $paidRemaining = $paidUnits;

            // Asignar primero a líneas con mayor precio unitario
            usort($groupItems, function ($a, $b) {
                if ($a['unit_price'] === $b['unit_price']) return 0;
                return ($a['unit_price'] > $b['unit_price']) ? -1 : 1;
            });

            foreach ($groupItems as $gi) {
                $lineIndex = $gi['line'];

                if ($paidRemaining <= 0) {
                    // todas estas unidades entran como “gratis”
                    $data['products'][$lineIndex]['total'] = 0.0;
                    continue;
                }

                $paidForThisVariant = min($gi['qty'], $paidRemaining);
                $lineTotal = $paidForThisVariant * $gi['unit_price'];

                $data['products'][$lineIndex]['total'] = $lineTotal;

                $paidRemaining -= $paidForThisVariant;
            }
        }

        // Totales de orden
        $order_total = 0.0;
        foreach ($data['products'] as $p) {
            $order_total += (float) $p['total'];
        }
        $data['order_total'] = $order_total;

        // Cupón (si aplica)
        $orderTotalAfterCoupon = $order_total;
        if (!empty($cart['is_coupon_applied'])) {
            $coupon = null;
            if (!empty($cart['coupon_code'])) {
                $coupon = \App\Models\Coupon::where('code', $cart['coupon_code'])->first();
            }
            $discountPercent = $coupon?->discount ?? ($cart['coupon_discount'] ?? 0);
            $orderTotalAfterCoupon = $order_total * $remainingPercentage($discountPercent);
        }
        $data['order_total_after_coupon_applied'] = $orderTotalAfterCoupon;

        return $data;
    }



}
