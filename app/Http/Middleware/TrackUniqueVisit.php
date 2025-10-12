<?php

namespace App\Http\Middleware;

use App\Models\Visit;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Jaybizzle\CrawlerDetect\CrawlerDetect;
use Illuminate\Support\Facades\Cache;

class TrackUniqueVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1) IP real detrás de Cloudflare
        $ip    = $request->headers->get('CF-Connecting-IP') ?? $request->ip() ?? '';
        $uaRaw = $request->userAgent() ?? '';
        $uaNorm = mb_strtolower(trim($uaRaw));
        $today = now()->toDateString();

        // 2) Sólo contar pageviews "reales":
        //    - Método GET
        //    - Acepta HTML
        //    - No es prefetch/prerender
        //    - No es asset estático (js/css/img/svg/ico/etc.)
        if ($request->method() !== 'GET') {
            return $next($request);
        }

        $accept = strtolower($request->headers->get('Accept', ''));
        if ($accept !== '' && !str_contains($accept, 'text/html')) {
            return $next($request);
        }

        $purpose = strtolower($request->headers->get('Purpose', ''));
        $secPurpose = strtolower($request->headers->get('Sec-Purpose', ''));
        if (str_contains($purpose, 'prefetch') || str_contains($secPurpose, 'prefetch') || str_contains($secPurpose, 'prerender')) {
            return $next($request);
        }

        // Omitir assets estáticos (si los sirves por la misma app)
        $path = strtolower($request->path());
        if (preg_match('~\.(?:js|css|png|jpg|jpeg|gif|svg|ico|webp|avif|mp4|mp3|json|xml|txt|map|woff2?|ttf|eot)$~i', $path)) {
            return $next($request);
        }

        // 3) Filtro de bots (CrawlerDetect + heurística propia)
        if ($this->isBot($uaRaw, $ip)) {
            return $next($request);
        }

        // 4) Deduplicación diaria por (IP + UA normalizado) con cache (evita query)
        $cacheKey = "uv:{$today}:" . md5($ip . '|' . $uaNorm);
        if (Cache::get($cacheKey)) {
            return $next($request);
        }

        // 5) Si no está en cache, chequeo en DB y registro
        $exists = Visit::where('ip_address', $ip)
            ->where('user_agent', $uaNorm)
            ->whereDate('visited_at', $today)
            ->exists();

        if (! $exists) {
            Visit::create([
                'ip_address' => $ip,
                'user_agent' => $uaNorm, // guardamos normalizado para reducir duplicados
                'visited_at' => $today,
            ]);
        }

        // TTL hasta fin de día para no recontar
        $ttl = now()->endOfDay()->diffInSeconds(now()) ?: 60*60;
        Cache::put($cacheKey, 1, $ttl);

        return $next($request);
    }

    private function isBot(?string $userAgent, ?string $ip): bool
    {
        $uaRaw = $userAgent ?? '';
        $ua = mb_strtolower(trim($uaRaw));
        $ip = $ip ?? '';

        // 0) CrawlerDetect (cobertura de crawlers conocidos)
        $crawlerDetect = new CrawlerDetect;
        if ($crawlerDetect->isCrawler($uaRaw)) {
            return true;
        }

        // 1) Bots/escáneres explícitos (incluye varios que suelen colarse)
        $explicitBots = [
            // Mensajes y escáneres
            'hello from palo alto networks','paloaltonetworks','cortex-xpanse','scanning-activity',
            'internetmeasurement','l9tcpid','xfox-scan','compatible; odin','odin; https://docs.getodin.com',

            // Librerías/HTTP clients/headless
            'libredtail-http','curl/','python-requests','wget/','go-http-client','okhttp',
            'java/','node-fetch','libwww-perl','httpclient','aiohttp',
            'headlesschrome','puppeteer','playwright','phantomjs','selenium','lighthouse','pagespeed','rendertron',

            // Navegadores “raros”/ancianos que casi siempre son scripts en 2025
            'konqueror/','ucbrowser/',

            // Catch-all
            'crawler','spider','scraper','scanner','fetcher',
        ];
        foreach ($explicitBots as $needle) {
            if ($needle !== '' && str_contains($ua, $needle)) {
                return true;
            }
        }

        // 2) Heurística por puntaje
        $score = 0;

        // UA vacío o genérico
        if ($ua === '' || $ua === 'mozilla/5.0') $score += 3;
        if (mb_strlen($uaRaw) > 0 && mb_strlen($uaRaw) <= 15) $score += 2;

        // Typos típicos
        foreach (['mozlila','bulid','moblie','live gecko','winndows','safri'] as $t) {
            if (str_contains($ua, $t)) $score += 2;
        }

        // Señales fuertes de librerías/headless (por si se escapó arriba)
        foreach ([
                     'curl/','python-requests','wget/','go-http-client','okhttp','java/','node-fetch',
                     'libwww-perl','httpclient','aiohttp','headlesschrome','puppeteer','playwright',
                     'phantomjs','selenium','lighthouse','pagespeed','rendertron'
                 ] as $s) {
            if (str_contains($ua, $s)) $score += 3;
        }

        // UA Android con modelo 1-3 letras (muchos scrapers): suma leve
        if (preg_match('~android [0-9]+;\s*[a-z0-9_-]{1,3}\)?~i', $uaRaw)) $score += 1;

        // IPs locales sospechosas (si tenés listas propias; nunca incluyas rangos de Cloudflare)
        if (method_exists($this, 'ipStartsWithAny') && method_exists($this, 'suspiciousIpPrefixes')) {
            if ($this->ipStartsWithAny($ip, $this->suspiciousIpPrefixes())) $score += 3;
        }

        // Pistas humanas (restan un poco)
        foreach (['safari/537.36','mobile safari/537.36','gecko/20100101'] as $h) {
            if (str_contains($ua, $h)) $score -= 1;
        }

        // No penalizar “chrome/x.0.0.0” (UA-Reduction común hoy)
        // if (preg_match('~chrome/\d+\.0\.0\.0\b~i', $uaRaw)) { /* neutro */ }

        return $score >= 3;
    }
}
