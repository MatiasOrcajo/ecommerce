<?php

// app/Http/Controllers/BeaconController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use App\Models\Visit;

class BeaconController extends Controller
{
    public function __invoke(Request $request)
    {
        // 0) Sólo JSON válido
        $data = $request->validate([
            'token'  => ['nullable','string'], // el token “vst” del cliente
            '_token' => ['required','string'], // CSRF (ya validado por middleware)
            'tz'     => ['nullable','string'],
            'sw'     => ['nullable','integer'],
            'sh'     => ['nullable','integer'],
            'pn'     => ['nullable','integer'],
        ]);

        // 1) Tomar IP real detrás de Cloudflare
        $ip    = $request->headers->get('CF-Connecting-IP') ?? $request->ip() ?? '';
        $uaRaw = $request->userAgent() ?? '';
        $ua    = mb_strtolower(trim($uaRaw));

        // 2) Debe existir cookie vst (o token en body) -> indica que hubo pageview real
        $vstCookie = $request->cookie('vst') ?: ($data['token'] ?? null);
        if (!$vstCookie) {
            return response()->noContent(); // nada que registrar
        }

        // 3) Bot quick-filter: si querés, reutilizá tu función isBot(...)
        if (method_exists($this, 'isBot') && $this->isBot($uaRaw, $ip)) {
            return response()->noContent();
        }

        // 4) Deduplicar por día (ip + ua normalizado)
        $today    = now()->toDateString();
        $cacheKey = "uv:{$today}:" . md5($ip.'|'.$ua);
        if (Cache::get($cacheKey)) {
            return response()->noContent();
        }

        // 5) Consultar DB por si ya existe
        $exists = Visit::where('ip_address', $ip)
            ->where('user_agent', $ua)
            ->whereDate('visited_at', $today)
            ->exists();

        if (!$exists) {
            Visit::create([
                'ip_address' => $ip,
                'user_agent' => $ua,
                'visited_at' => $today,   // si tu columna es date/datetime funciona
            ]);
        }

        // 6) Grabar en cache hasta fin del día
        $ttl = now()->endOfDay()->diffInSeconds(now()) ?: 3600;
        Cache::put($cacheKey, 1, $ttl);

        return response()->noContent(200);
    }

    /** Opcional: pega aquí tu isBot($ua, $ip) si la tenías en un trait/ctrl. */
}

