<?php

namespace App\Http\Middleware;

use App\Models\Visit;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Jaybizzle\CrawlerDetect\CrawlerDetect;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class TrackUniqueVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        $ip        = $request->ip() ?? '';
        $uaRaw     = $request->userAgent() ?? '';
        $date      = now()->toDateString();

        if ($this->isBot($uaRaw, $ip, $request)) {
            return $next($request);
        }

        // Registrar 1 vez por día por (ip, ua normalizado)
        $uaNorm = mb_strtolower(trim($uaRaw));
        $exists = Visit::where('ip_address', $ip)
            ->where('user_agent', $uaNorm)
            ->whereDate('visited_at', $date)
            ->exists();

        if (! $exists) {
            Visit::create([
                'ip_address' => $ip,
                'user_agent' => $uaNorm,   // guarda normalizado para reducir duplicados
                'visited_at' => $date,
            ]);
        }

        return $next($request);
    }

    private function isBot(string $uaRaw, string $ip, Request $request): bool
    {
        $ua = mb_strtolower(trim($uaRaw));

        // 0) Librería de terceros
        $cd = new CrawlerDetect;
        if ($cd->isCrawler($uaRaw)) {
            return true;
        }

        // 1) Patrones explícitos (de lo que viste en tus logs + genéricos)
        $needles = [
            // escáneres / medición
            'hello from palo alto networks', 'paloaltonetworks', 'cortex-xpanse',
            'internetmeasurement/1.0', 'xfox-scan', 'l9tcpid', 'odin; https://docs.getodin.com',
            'od in; https://docs.getodin.com', 'libredtail-http',

            // headless / automatización / auditorías
            'headlesschrome', 'puppeteer', 'playwright', 'phantomjs', 'selenium', 'lighthouse', 'pagespeed', 'rendertron',

            // librerías http / scrapers
            'curl/', 'python-requests', 'wget/', 'go-http-client', 'okhttp', 'java/', 'node-fetch',
            'libwww-perl', 'httpclient', 'aiohttp',

            // social preview / uptime
            'facebookexternalhit', 'slackbot', 'telegrambot', 'discordbot',
            'whatsapp', 'linkedinbot', 'twitterbot', 'bitlybot', 'uptimerobot', 'uptime-kuma',

            // catch-all
            'crawler', 'spider', 'scraper', 'scanner', 'fetcher', 'preview'
        ];
        foreach ($needles as $n) {
            if ($n !== '' && str_contains($ua, $n)) {
                return true;
            }
        }

        // 2) UA vacío o genérico
        if ($ua === '' || $ua === 'mozilla/5.0') {
            return true;
        }

        // 3) Typos/malformed comunes en bots (ejemplos reales de tu lista)
        $typos = ['mozlila', 'bulid', 'moblie', 'live gecko', 'winndows', 'safri'];
        foreach ($typos as $t) {
            if (str_contains($ua, $t)) {
                return true;
            }
        }

        // 4) UA sospechosamente corto
        if (mb_strlen($uaRaw) <= 15) {
            return true;
        }

        // 5) Heurística por ráfagas (rate limiting simple por IP+UA)
        //    Si en 60s el mismo par IP+UA hace > 30 hits, lo tratamos como bot.
        $burstKey = 'visit:burst:' . sha1($ip.'|'.$ua);
        $hits = Cache::increment($burstKey);
        Cache::add($burstKey.':ttl', true, now()->addSeconds(60)); // asegura un TTL mínimo
        Cache::put($burstKey, $hits, 60);
        if ($hits > 30) {
            return true;
        }

        // 6) Señales de headers: muchos bots no mandan Accept-Language
        $al = $request->headers->get('accept-language');
        if ($al === null || $al === '') {
            // No bloqueamos siempre; solo si además el UA es "sospechoso" por patrón genérico de Chrome muy viejo o imposible
            if (preg_match('~msie\s[0-9]|trident/|chrome/([0-4][0-9]\.|[1-9]\.)~i', $uaRaw)) {
                return true;
            }
        }

        return false;
    }
}
