<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Visit;
use Jaybizzle\CrawlerDetect\CrawlerDetect;
use Illuminate\Support\Facades\Cache;

class BeaconController extends Controller
{
    public function __invoke(Request $request)
    {
        // 1) Validar payload
        $data = $request->validate([
            'token' => 'required|string',      // viene de la cookie 'vst'
            'tz'    => 'nullable|string',
            'sw'    => 'nullable|integer',
            'sh'    => 'nullable|integer',
            'pn'    => 'nullable|integer',
        ]);

        // 2) Verificar que cookie 'vst' exista y matchee
        $cookie = $request->cookie('vst');
        if (!$cookie || $cookie !== $data['token']) {
            return response()->noContent(Response::HTTP_ACCEPTED);
        }

        // 3) IP real detrás de Cloudflare
        $ip = $request->headers->get('CF-Connecting-IP') ?? $request->ip() ?? '';
        $uaRaw = $request->userAgent() ?? '';
        $uaNorm = mb_strtolower(trim($uaRaw));
        $today = now()->toDateString();

        // 4) Filtrar bots (CrawlerDetect)
        $crawler = new CrawlerDetect();
        if ($crawler->isCrawler($uaRaw)) {
            return response()->noContent(Response::HTTP_ACCEPTED);
        }

        // 5) Deduplicar 1 vez por día (cache → DB)
        $cacheKey = "uv:{$today}:" . md5($ip.'|'.$uaNorm);
        if (!Cache::has($cacheKey)) {
            $exists = Visit::where('ip_address', $ip)
                ->where('user_agent', $uaNorm)
                ->whereDate('visited_at', $today)
                ->exists();

            if (!$exists) {
                Visit::create([
                    'ip_address' => $ip,
                    'user_agent' => $uaNorm,
                    'visited_at' => $today, // o ->now() si es datetime
                ]);
            }

            $ttl = now()->endOfDay()->diffInSeconds(now()) ?: 3600;
            Cache::put($cacheKey, 1, $ttl);
        }

        // 6) Responder rápido (no bloquear navegación)
        return response()->noContent(Response::HTTP_ACCEPTED);
    }
}
