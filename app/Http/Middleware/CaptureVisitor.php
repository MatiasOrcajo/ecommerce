<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Events\NewVisitor;
use Illuminate\Support\Str;

class CaptureVisitor
{
    public function handle(Request $request, Closure $next)
    {
        $userAgent = strtolower($request->userAgent());

        $botSignatures = [
            'bot',
            'crawl',
            'spider',
            'python',
            'zgrab',
            'netcraft',
            'aiohttp',
            'expanse',
            'curl',
            'wget',
            'httpclient',
            'java',
            'php',
        ];

        // Si el User-Agent contiene alguna firma de bot, no registra la visita
        foreach ($botSignatures as $bot) {
            if (Str::contains($userAgent, $bot)) {
                return $next($request);
            }
        }

        event(new NewVisitor($request->ip()));

        return $next($request);
    }
}
