<?php

// app/Http/Controllers/BeaconController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use App\Models\Visit;

class BeaconController extends Controller
{
    public function __invoke(Request $r): Response
    {
        // CSRF ya chequeado por VerifyCsrfToken (excepto si la ruta está exenta)
        $token = (string) $r->input('token');
        if ($token === '') return response()->noContent(202);

        $ip  = $r->headers->get('CF-Connecting-IP') ?? $r->ip() ?? '';
        $ua  = mb_strtolower(trim($r->userAgent() ?? ''));
        $day = now()->toDateString();

        // Anti-bot
        if (app(\App\Http\Middleware\TrackUniqueVisit::class)
            ->isBot($r, $r->userAgent() ?? '', $ip)) {
            return response()->noContent(202);
        }

        // Dedupe y guardado (igual que ya hacías)
        $exists = Visit::where('ip_address',$ip)->where('user_agent',$ua)->whereDate('visited_at',$day)->exists();
        if (!$exists) {
            Visit::create(['ip_address'=>$ip,'user_agent'=>$ua,'visited_at'=>$day]);
        }

        // Si no hay cookie, fijarla ahora
        $resp = response()->noContent(202);
        if (!$r->cookie('vst') && $token) {
            $resp->withCookie(cookie()->forever('vst',$token,'/',null,true,true,false,'lax'));
        }
        return $resp;
    }


}

