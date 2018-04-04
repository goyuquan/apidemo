<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class HttpResponseHeadersMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $user = Auth::user();
        $token = base64_encode(str_random(40));
        $user->update(['remember_token' => $token]);
        $response->header('Authorization', $token);

        return $response;
    }
}
