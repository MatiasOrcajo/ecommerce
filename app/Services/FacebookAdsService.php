<?php

namespace App\Services;

use App\Models\Order;
use FacebookAds\Api;
use FacebookAds\Http\Exception\RequestException as FbRequestException;
use FacebookAds\Logger\CurlLogger;
use FacebookAds\Object\ServerSide\ActionSource;
use FacebookAds\Object\ServerSide\CustomData;
use FacebookAds\Object\ServerSide\Event;
use FacebookAds\Object\ServerSide\EventRequest;
use FacebookAds\Object\ServerSide\UserData;
use Illuminate\Support\Facades\Log;

class FacebookAdsService
{
    protected string $accessToken;

    protected string $pixelId;

    protected ?string $testEventCode = null;

    protected string $currency;

    public function __construct()
    {
        // Compat: primero intenta en services.facebook.*, luego facebook.*
        $this->pixelId = config('services.facebook.pixel_id')
            ?: config('facebook.pixel_id');

        $this->accessToken = config('services.facebook.pixel_access_token')
            ?: config('facebook.access_token');

        $this->testEventCode = config('services.facebook.pixel_test_code')
            ?: config('facebook.pixel_test_code');

        $this->currency = strtoupper(
            config('services.facebook.currency', config('facebook.currency', 'ARS'))
        );

        // -------- Validaciones explícitas --------
        if (empty($this->accessToken)) {
            throw new \InvalidArgumentException(
                'Facebook CAPI access token is missing (services.facebook.pixel_access_token / facebook.access_token).'
            );
        }

        if (empty($this->pixelId) || ! ctype_digit($this->pixelId)) {
            throw new \InvalidArgumentException(
                'Facebook Pixel ID missing/invalid. It must be numeric.'
            );
        }

        // -------- SDK ----------
        Api::init(null, null, $this->accessToken);
        Api::instance()->setLogger(new CurlLogger);
    }

    /**
     * Envía un evento Purchase a la Conversions API.
     *
     * @param  Order  $order  Pedido confirmado/pagado
     * @param  array  $ctx  Información del request normalizada o payload plano
     */
    public function purchaseEvent(Order $order, array $ctx): void
    {
        /* ---------- UserData ---------- */
        $email = $order->customer->email ?? $ctx['email'] ?? null;
        $phone = $order->customer->phone ?? $ctx['phone'] ?? null;

        $userData = (new UserData)
            ->setClientIpAddress($ctx['ip'] ?? null)
            ->setClientUserAgent($ctx['user_agent'] ?? null)
            ->setFbc($ctx['fbc'] ?? null)
            ->setFbp($ctx['fbp'] ?? null)
            ->setExternalId(isset($ctx['external_id']) ? (string) $ctx['external_id'] : null);

        if ($email) {
            $userData->setEmail($email);
        }
        if ($phone) {
            $userData->setPhone($phone);
        }

        /* ---------- Productos ---------- */
        $contentIds = [];
        $numItems = 0;

        foreach ($order->products ?? [] as $item) {
            $pid = (string) ($item->sku ?? $item->product_id ?? $item->id ?? '');
            $qty = max(1, (int) ($item->quantity ?? $item->qty ?? 1));

            if ($pid === '') {
                continue;
            }

            $contentIds[] = $pid;
            $numItems += $qty;
        }

        /* ---------- Montos y moneda ---------- */
        $currency = preg_match('/^[A-Z]{3}$/', $order->currency ?? '')
            ? strtoupper($order->currency)
            : $this->currency;

        $orderTotal = (float) ($order->total_amount ?? $order->total ?? $order->grand_total ?? 0);
        if (! is_finite($orderTotal) || $orderTotal < 0) {
            $orderTotal = 0.0;
        }

        /* ---------- CustomData ---------- */
        $custom = (new CustomData)
            ->setCurrency($currency)
            ->setValue($orderTotal)
            ->setContentType('product');

        if ($contentIds) {
            $custom->setContentIds($contentIds);
        }
        if (method_exists($custom, 'setOrderId') && isset($order->id)) {
            $custom->setOrderId((string) $order->id);
        }
        if (method_exists($custom, 'setNumItems')) {
            $custom->setNumItems($numItems);
        }

        /* ---------- Event ---------- */
        $url = filter_var($ctx['url'] ?? null, FILTER_VALIDATE_URL) ? $ctx['url'] : null;

        $event = (new Event)
            ->setEventName('Purchase')
            ->setEventTime(time())
            ->setEventSourceUrl($url)
            ->setActionSource(ActionSource::WEBSITE)
            ->setUserData($userData)
            ->setCustomData($custom);

        if (! empty($ctx['event_id'])) {
            $event->setEventId($ctx['event_id']);
        }

        /* ---------- Request ---------- */
        $request = (new EventRequest($this->pixelId))->setEvents([$event]);
        if ($this->testEventCode) {
            $request->setTestEventCode($this->testEventCode);
        }

        try {
            $response = $request->execute();

            Log::info('FB CAPI Purchase OK', [
                'events_received' => $response->getEventsReceived(),
                'fbtrace_id' => $response->getFbTraceId(),
            ]);
        } catch (FbRequestException $e) {
            Log::error('FB CAPI Purchase ERROR', [
                'status' => $e->getHttpStatusCode(),
                'error_code' => $e->getErrorCode(),
                'error_subcode' => $e->getErrorSubcode(),
                'error_type' => $e->getErrorType(),
                'user_title' => $e->getErrorUserTitle(),
                'user_message' => $e->getErrorUserMessage(),
                'message' => $e->getMessage(),
            ]);

            throw $e; // permite retry del Job
        }
    }
}
