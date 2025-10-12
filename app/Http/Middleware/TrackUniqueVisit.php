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

    private function isBot(string $userAgent = null, string $ip = null): bool
    {
        $uaRaw = $userAgent ?? '';
        $ua = mb_strtolower(trim($uaRaw));
        $ip = $ip ?? '';

        // 0) CrawlerDetect (cobertura de crawlers conocidos)
        $crawlerDetect = new \Jaybizzle\CrawlerDetect\CrawlerDetect;
        if ($crawlerDetect->isCrawler($uaRaw)) {
            return true;
        }

        // 1) Bots explícitos (nombres propios o mensajes)
        $explicitBots = [
            'libredtail-http','hello from palo alto networks','paloaltonetworks',
            'cortex-xpanse','scanning-activity','l9tcpid','internetmeasurement','xfox-scan',
            'odin; https://docs.getodin.com','compatible; odin','konqueror/','ucbrowser/'
        ];
        foreach ($explicitBots as $needle) {
            if ($needle !== '' && str_contains($ua, $needle)) {
                return true;
            }
        }

        // 2) Heurística por puntaje (no marcar por una sola señal débil)
        $score = 0;

        // 2.1 Señales fuertes (sumas grandes)
        $strongSignals = [
            'curl/', 'python-requests', 'wget/', 'go-http-client', 'okhttp',
            'java/', 'node-fetch', 'libwww-perl', 'httpclient', 'aiohttp',
            'headlesschrome', 'puppeteer', 'playwright', 'phantomjs', 'selenium',
            'lighthouse', 'pagespeed', 'rendertron',
            'crawler', 'spider', 'scraper', 'scanner', 'fetcher'
        ];
        foreach ($strongSignals as $needle) {
            if (str_contains($ua, $needle)) { $score += 3; }
        }

        // 2.2 Typos / UA mal formados
        $typos = ['mozlila','bulid','moblie','live gecko','winndows','safri'];
        foreach ($typos as $needle) {
            if (str_contains($ua, $needle)) { $score += 2; }
        }

        // 2.3 UA vacío o demasiado corto
        if ($ua === '' || $ua === 'mozilla/5.0') { $score += 3; }
        if (mb_strlen($uaRaw) > 0 && mb_strlen($uaRaw) <= 15) { $score += 2; }

        // 2.4 IPs sospechosas (si tenés una función/lista propia)
        if (method_exists($this, 'ipStartsWithAny') && method_exists($this, 'suspiciousIpPrefixes')) {
            if ($this->ipStartsWithAny($ip, $this->suspiciousIpPrefixes())) { $score += 3; }
        }

        // 2.5 Señales débiles (sumas chicas)
        //   - Chrome con ceros NO suma (para evitar falsos positivos como los tuyos)
        //   - Modelos Android raros suman poco, pero no determinan
        if (preg_match('~android [0-9]+;\s*[a-z0-9_-]{1,3}\)?~i', $uaRaw)) { $score += 1; }

        // 2.6 Navegadores MUY viejos para el SO (suma leve)
        $isWin10 = str_contains($ua, 'windows nt 10.0');
        if ($isWin10 && preg_match('~firefox/(?:[0-6][0-9]|7[0-2])\.~', $ua)) { // ≤72 aprox.
            $score += 1;
        }

        // 2.7 Señales de “humano” (restan)
        $humanHints = [
            'safari/537.36',            // típico Chrome-based moderno
            'mobile safari/537.36',     // típico Android/iOS webview
            'version/13.0.3 mobile/15e148 safari/604.1', // típico iOS 13.x
            'gecko/20100101',           // típico Firefox
        ];
        foreach ($humanHints as $hint) {
            if (str_contains($ua, $hint)) { $score -= 1; }
        }

        // 2.8 NO penalizar “chrome/x.0.0.0” (lo dejamos neutro)
        // if (preg_match('~chrome/\d+\.0\.0\.0\b~i', $uaRaw)) { /* score += 0; */ }

        // 3) Umbral
        // >=3: bot. 1–2: gris (decidí según IP/ratio de requests); <=0: humano.
        if ($score >= 3) {
            return true;
        }

        return false;
    }

}
