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
    /** Nombre de la cookie visible para JS */
    private const COOKIE_NAME = 'vst';

    public function handle(Request $request, Closure $next): Response
    {
        // --- 0) Determinar IP real detrás de Cloudflare ---
        $ip = $this->realIp($request);

        // --- 1) Sólo contar pageviews “reales” (HTML GET no-prefetch, no-assets) ---
        if (! $this->isLikelyHtmlView($request)) {
            return $next($request);
        }

        $uaRaw  = $request->userAgent() ?? '';
        $uaNorm = mb_strtolower(trim($uaRaw));
        $today  = now()->toDateString();

        // --- 2) Filtro de bots: Cloudflare + CrawlerDetect + heurística ---
        if ($this->isBot($request, $uaRaw, $ip)) {
            return $next($request);
        }

        // --- 3) Cookie + Beacon: sólo contamos si vimos el beacon válido hoy ---
        $token = $request->cookie(self::COOKIE_NAME);
        $isHuman = $token && Cache::get('human:'.$this->tokenId($token)) === true;

        // Si aún no vimos el beacon, no contamos (evita sumar bots que sólo traen HTML)
        if (! $isHuman) {
            // Aseguramos que el navegador tenga la cookie firmada para el próximo beacon
            if (! $token) {
                $this->queueVisitCookie();
            }
            return $next($request);
        }

        // --- 4) Deduplicación diaria por (IP + UA normalizado) con cache ---
        $cacheKey = "uv:{$today}:".md5($ip.'|'.$uaNorm);
        if (Cache::get($cacheKey)) {
            return $next($request);
        }

        // --- 5) Persistencia (única por día) ---
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
        $ttl = now()->endOfDay()->diffInSeconds(now()) ?: 3600;
        Cache::put($cacheKey, 1, $ttl);

        // Reforzamos que el cliente tenga cookie para siguientes vistas
        if (! $token) {
            $this->queueVisitCookie();
        }

        return $next($request);
    }

    /** IP real considerando Cloudflare */
    private function realIp(Request $request): string
    {
        return $request->headers->get('CF-Connecting-IP')
            ?: $request->getClientIp()
                ?: $request->ip()
                    ?: '';
    }

    /** ¿Es muy probable que sea una vista HTML real? */
    private function isLikelyHtmlView(Request $request): bool
    {
        if ($request->method() !== 'GET') return false;

        // Evitar assets estáticos (si salen por la misma app)
        $path = strtolower($request->path());
        if (preg_match('~\.(?:js|css|png|jpe?g|gif|svg|ico|webp|avif|mp4|mp3|json|xml|txt|map|woff2?|ttf|eot)$~i', $path)) {
            return false;
        }

        // Debe aceptar HTML (algunos scrapers piden */* o JSON)
        $accept = strtolower($request->headers->get('Accept', ''));
        if ($accept !== '' && !str_contains($accept, 'text/html')) return false;

        // Evitar prefetch/prerender
        $purpose    = strtolower($request->headers->get('Purpose', ''));
        $secPurpose = strtolower($request->headers->get('Sec-Purpose', ''));
        if (str_contains($purpose, 'prefetch')
            || str_contains($secPurpose, 'prefetch')
            || str_contains($secPurpose, 'prerender')) {
            return false;
        }

        return true;
    }

    /** Coloca en cola una cookie firmada legible por JS (para que el beacon la devuelva) */
    private function queueVisitCookie(): void
    {
        $raw   = Str::uuid()->toString().'|'.time();
        $sig   = hash_hmac('sha256', $raw, config('app.key'));
        $token = base64_encode($raw.'|'.$sig);

        // Cookie legible por JS (NO HttpOnly), segura y con SameSite=Lax
        cookie()->queue(
            cookie(self::COOKIE_NAME, $token, 24 * 60, '/', null, true, false, false, 'Lax')
        );
    }

    /** ID corto y estable para usar en cache keys */
    private function tokenId(string $token): string
    {
        return substr(hash('sha1', $token), 0, 20);
        // Nota: la validación de HMAC se hace en el endpoint del beacon (/v-beacon)
    }

    /** Detección de bots: Cloudflare + CrawlerDetect + heurística propia */
    private function isBot(Request $request, string $uaRaw, string $ip): bool
    {
        $ua = mb_strtolower(trim($uaRaw));

        // (A) Cloudflare Bot Management (si existe el header)
        // 0–29: likely automated; 30–59: ambiguous; 60–99: likely human.
        $cfScore = $request->headers->get('CF-Bot-Score');
        if ($cfScore !== null && is_numeric($cfScore) && (int)$cfScore <= 29) {
            return true;
        }

        // (B) CrawlerDetect (listas conocidas)
        $cd = new CrawlerDetect;
        if ($cd->isCrawler($uaRaw)) return true;

        // (C) Firmas explícitas
        $explicit = [
            // Mensajes/escáneres
            'hello from palo alto networks','paloaltonetworks','cortex-xpanse','scanning-activity',
            'internetmeasurement','l9tcpid','xfox-scan','compatible; odin','odin; https://docs.getodin.com',

            // Librerías/headless
            'libredtail-http','curl/','python-requests','wget/','go-http-client','okhttp',
            'java/','node-fetch','libwww-perl','httpclient','aiohttp',
            'headlesschrome','puppeteer','playwright','phantomjs','selenium','lighthouse','pagespeed','rendertron',

            // Navegadores “raros” hoy
            'konqueror/','ucbrowser/',

            // Genéricos
            'crawler','spider','scraper','scanner','fetcher',
        ];
        foreach ($explicit as $s) {
            if ($s !== '' && str_contains($ua, $s)) return true;
        }

        // (D) Heurística por puntaje
        $score = 0;

        // UA vacío o muy corto
        if ($ua === '' || $ua === 'mozilla/5.0') $score += 3;
        if (mb_strlen($uaRaw) > 0 && mb_strlen($uaRaw) <= 15) $score += 2;

        // Typos típicos de UA falsos
        foreach (['mozlila','bulid','moblie','live gecko','winndows','safri'] as $t) {
            if (str_contains($ua, $t)) $score += 2;
        }

        // Repetimos firmas fuertes por si se escapó (defensa en profundidad)
        foreach ([
                     'curl/','python-requests','wget/','go-http-client','okhttp','java/','node-fetch',
                     'libwww-perl','httpclient','aiohttp','headlesschrome','puppeteer','playwright',
                     'phantomjs','selenium','lighthouse','pagespeed','rendertron'
                 ] as $h) {
            if (str_contains($ua, $h)) $score += 3;
        }

        // Android con modelo 1–3 letras (muchos scrapers): suma leve
        if (preg_match('~android [0-9]+;\s*[a-z0-9_-]{1,3}\)?~i', $uaRaw)) $score += 1;

        // Pistas humanas (restan levemente)
        foreach (['safari/537.36','mobile safari/537.36','gecko/20100101'] as $hint) {
            if (str_contains($ua, $hint)) $score -= 1;
        }

        return $score >= 3;
    }
}
