<?php

namespace App\Http\Middleware;

use App\Models\Visit;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackUniqueVisit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $userAgent = $request->userAgent();
        $date = now()->toDateString();

        $exists = Visit::where('ip_address', $ip)
            ->where('user_agent', $userAgent)
            ->whereDate('visited_at', $date)
            ->exists();

        if (! $exists) {
            if (stripos($userAgent, 'bot') === false) {
                Visit::create([
                    'ip_address' => $ip,
                    'user_agent' => $userAgent,
                    'visited_at' => $date,
                ]);
            }

        }

        return $next($request);
    }
}
