<?php

namespace App\Http\Middleware;
use Closure;
class IsMeli
{

    public function handle($request, Closure $next)
    {
        if(!config("mercadolivre.is_active")) return abort(404);
        return $next($request);
    }
}
