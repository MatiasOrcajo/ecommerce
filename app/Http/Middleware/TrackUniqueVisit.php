<?php

namespace App\Http\Middleware;

use App\Models\Visit;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Jaybizzle\CrawlerDetect\CrawlerDetect;

class TrackUniqueVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $userAgent = $request->userAgent();
        $date = now()->toDateString();

        $crawlerDetect = new CrawlerDetect;

        if (! $crawlerDetect->isCrawler($userAgent)) {
            $exists = Visit::where('ip_address', $ip)
                ->where('user_agent', $userAgent)
                ->whereDate('visited_at', $date)
                ->exists();

            if (! $exists) {
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
