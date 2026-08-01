<?php

// app/Http/Middleware/VisitCookie.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VisitCookie
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (! $request->cookies->has('vst')) {
            $raw = Str::uuid()->toString().'|'.time();
            $sig = hash_hmac('sha256', $raw, config('app.key'));
            $token = base64_encode($raw.'|'.$sig);

            // Cookie legible por JS (NO HttpOnly), pero segura
            cookie()->queue(
                cookie('vst', $token, 60 * 24, '/', null, true, false, false, 'Lax')
            );
        }

        return $response;
    }
}
