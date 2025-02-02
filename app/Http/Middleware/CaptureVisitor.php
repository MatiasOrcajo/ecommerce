<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Events\NewVisitor;

class CaptureVisitor
{
    public function handle(Request $request, Closure $next)
    {
        event(new NewVisitor($request->ip()));

        return $next($request);
    }
}
