<?php namespace App\Http\Middleware;

use Closure;

class CORS {
    public function handle($request, Closure $next)
    {

        // ALLOW OPTIONS METHOD
        $headers = [
            'Access-Control-Allow-Methods'=> 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Headers'=> 'Content-Type, X-Auth-Token, Origin'
        ];

        $response->header($headers);

        return $next($request);
    }

}
