<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
class WeddingEnabled
{

    public function handle(Request $request, Closure $next)
    {
        if (!config('features.wedding_list')) {
            abort(403);
        }
        return $next($request);
    }
}
