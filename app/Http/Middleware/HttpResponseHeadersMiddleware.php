<?php

namespace App\Http\Middleware;

use Closure;

class HttpResponseHeadersMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $response->header("Access-Control-Expose-Headers", "Authorization, X-Custom-header")
                ->header("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept, Authorization, X-Custom-header")
                ->header('Authorization', '______________________________');

        return $response;
    }
}
