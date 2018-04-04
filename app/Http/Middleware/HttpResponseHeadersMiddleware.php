<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class HttpResponseHeadersMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $user = Auth::user();
        $token = base64_encode(str_random(40));

        dump($user);
        $user->update([
            'remember_token' => $token,
            // 'updated_at', Carbon::now()->toDateTimeString()
        ]);

        $response->header('Authorization', $token);

        return $response;
    }
}
