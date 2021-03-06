<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
// use Carbon\Carbon;

class AfterMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $user = Auth::user();

        if(!empty($user)) {
            $token = base64_encode(str_random(40));
            $user->remember_token = $token;
            // $user->updated_at = Carbon::now()->toDateTimeString();
            $user->save();

            $response->header('Authorization', $token);
        }

        return $response;
    }
}
