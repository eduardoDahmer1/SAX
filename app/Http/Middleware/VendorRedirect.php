<?php

namespace App\Http\Middleware;
use Closure;
class VendorRedirect
{

    public function handle($request, Closure $next)
    {
        if(!config("features.marketplace")) return redirect()->route('front.index');
        return $next($request);
    }
}
