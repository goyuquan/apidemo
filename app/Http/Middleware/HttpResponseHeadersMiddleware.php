<?php

namespace App\Http\Middleware;

use Closure;

class HttpResponseHeadersMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $response->header("Access-Control-Expose-Headers", "Authorization, X-Custom")
                ->header('Authorization', '______________________________');

        return $response;
    }
}
