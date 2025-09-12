<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;
use FacebookAds\Http\Exception\RequestException as FbRequestException;

use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;
use FacebookAds\Object\ServerSide\{
    ActionSource, Content, CustomData, DeliveryCategory, Event, EventRequest, UserData
};
use Illuminate\Support\Facades\Log;

class FacebookAdsService
{
    protected string $accessToken;
    protected string $pixelId;
    protected ?string $testEventCode = null;
    protected string $currency;

    public function __construct()
    {
        // Compat: primero busca en services.facebook.*, si no encuentra usa facebook.*
        $this->accessToken = (string) (
        config('services.facebook.pixel_access_token')
            ?: config('facebook.access_token')
        );

        $this->pixelId = (string) (
        config('services.facebook.pixel_id')
            ?: config('facebook.pixel_id')
        );

        $this->testEventCode = config('services.facebook.pixel_test_code')
            ?: config('facebook.pixel_test_code');

        $this->currency = (string) (
        config('services.facebook.currency', 'ARS')
            ?: config('facebook.currency', 'ARS')
        );

        // Validaciones claras para evitar “id vacío”
        if (empty($this->accessToken)) {
            throw new \InvalidArgumentException('Facebook CAPI access token is missing (services.facebook.pixel_access_token / facebook.access_token).');
        }
        if (empty($this->pixelId) || !ctype_digit($this->pixelId)) {
            throw new \InvalidArgumentException('Facebook Pixel ID missing/invalid. It must be numeric.');
        }

        Api::init(null, null, $this->accessToken);
        Api::instance()->setLogger(new CurlLogger());
    }



    /**
     * Envía un evento Purchase (pedido pagado) por Conversions API.
     *
     * @param \App\Models\Order $order   Pedido confirmado/pagado (ajusta el typehint si tu modelo se llama distinto)
     * @param Request|array     $data    Request (se normaliza) o payload plano (ideal para Jobs)
     */
    /**
     * Envía un evento Purchase (pedido pagado) por Conversions API.
     *
     * @param \App\Models\Order $order Pedido confirmado/pagado
     * @param Request|array     $data  Request (se normaliza) o payload plano (ideal para Jobs)
     */
    public function purchaseEvent(Order $order, Request|array $data): void
    {
        $ctx = $data instanceof Request ? $this->ctxFromRequest($data) : $data;

        // ---- UserData (prioriza datos del pedido y luego del contexto) ----
        $email = $order->customer->email ?? ($ctx['email'] ?? null);
        $phone = $order->customer->phone ?? ($ctx['phone'] ?? null);

        $userData = (new UserData())
            ->setClientIpAddress($ctx['ip'] ?? null)
            ->setClientUserAgent($ctx['user_agent'] ?? null)
            ->setFbc($ctx['fbc'] ?? null)
            ->setFbp($ctx['fbp'] ?? null)
            ->setExternalId(isset($ctx['external_id']) ? (string)$ctx['external_id'] : null);

        if (!empty($email)) $userData->setEmail($email);
        if (!empty($phone)) $userData->setPhone($phone);

        // ---- Extraer IDs de productos (sin 'contents' para evitar incompatibilidades del SDK) ----
        $items = $order->products ?? [];
        $contentIds = [];
        $numItems   = 0;

        foreach ($items as $it) {
            $pid = (string)($it->sku ?? $it->product_id ?? $it->id ?? '');
            $qty = max(1, (int)($it->quantity ?? $it->qty ?? 1));
            if ($pid === '') continue;

            $contentIds[] = $pid;
            $numItems    += $qty;
        }

        // ---- Moneda y total sanitizados ----
        $currency = strtoupper((string)($order->currency ?? $this->currency ?? 'ARS'));
        if (!preg_match('/^[A-Z]{3}$/', $currency)) {
            $currency = 'ARS';
        }

        $orderTotal = (float)($order->total_amount ?? $order->total ?? $order->grand_total ?? 0);
        if (!is_finite($orderTotal) || $orderTotal < 0) {
            $orderTotal = 0.0;
        }

        // ---- CustomData (sin setContents) ----
        $custom = (new CustomData())
            ->setCurrency($currency)
            ->setValue($orderTotal)
            ->setContentType('product');

        if (!empty($contentIds)) {
            $custom->setContentIds($contentIds);
        }

        if (method_exists($custom, 'setOrderId') && isset($order->id)) {
            $custom->setOrderId((string)$order->id);
        }
        if (method_exists($custom, 'setNumItems')) {
            $custom->setNumItems((int)$numItems);
        }

        // ---- Event ----
        $url = $ctx['url'] ?? null;
        if (!empty($url) && !filter_var($url, FILTER_VALIDATE_URL)) {
            $url = null;
        }

        $event = (new Event())
            ->setEventName('Purchase')
            ->setEventTime(time())
            ->setEventSourceUrl($url)
            ->setActionSource(ActionSource::WEBSITE)
            ->setUserData($userData)
            ->setCustomData($custom);

        if (!empty($ctx['event_id'])) {
            $event->setEventId($ctx['event_id']);
        }

        // ---- Request ----
        $req = (new EventRequest($this->pixelId))->setEvents([$event]);
        if (!empty($this->testEventCode)) {
            $req->setTestEventCode($this->testEventCode);
        }

        try {
            $res = $req->execute();
            Log::info('FB CAPI OK', [
                'events_received' => $res->getEventsReceived(),
                'fbtrace_id'      => $res->getFbTraceId(),
            ]);
        } catch (FbRequestException $e) {
            Log::error('FB CAPI ERROR', [
                'status'        => $e->getHttpStatusCode(),
                'error_code'    => $e->getErrorCode(),
                'error_subcode' => $e->getErrorSubcode(),
                'error_type'    => $e->getErrorType(),
                'user_title'    => $e->getErrorUserTitle(),
                'user_message'  => $e->getErrorUserMessage(),
                'message'       => $e->getMessage(),
            ]);
            throw $e;
        }
    }



    private function ctxFromRequest(Request $r): array
    {
        return [
            'ip'          => $r->ip(),
            'user_agent'  => $r->userAgent(),
            'url'         => $r->fullUrl(),
            'fbc'         => $r->cookie('_fbc'),
            'fbp'         => $r->cookie('_fbp'),
            'external_id' => optional($r->user())->id ?? $r->session()->getId(),
            'email'       => optional($r->user())->email,
            'phone'       => optional($r->user())->phone,
        ];
    }
}
