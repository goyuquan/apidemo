<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AfterMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $user = Auth::user();

        dd($user);

        if($user) {
            $token = base64_encode(str_random(40));
            $user->update([
                'remember_token' => $token,
                // 'updated_at', Carbon::now()->toDateTimeString(),
                //'updated_at', Carbon::now()->toDateTimeString()
            ]);

            $response->header('Authorization', $token);
        }

        return $response;
    }
}
