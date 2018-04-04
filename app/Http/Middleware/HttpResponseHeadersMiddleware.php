<?php

namespace App\Http\Middleware;

use Closure;

class HttpResponseHeadersMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $user = Auth::user();
        $token = base64_encode(str_random(40));
        $user->update([
            'remember_token' => $token,
            // 'updated_at', Carbon::now()->toDateTimeString()
        ]);

        $response->header('Authorization', $token);

        return $response;
    }
}
