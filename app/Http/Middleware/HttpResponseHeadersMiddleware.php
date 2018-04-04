<?php

namespace App\Http\Middleware;

use Closure;

class HttpResponseHeadersMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $response->header('Authorization', '______________________________');

        return $response;
    }
}
