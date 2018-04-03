<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
    * Register any application services.
    *
    * @return void
    */
    public function register()
    {
        //
    }

    /**
    * Boot the authentication services for the application.
    *
    * @return void
    */
    public function boot()
    {
        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->header('Authorization')) {
                $key = $request->header('Authorization');
                $user = User::where('remember_token', $key)->first();
                $user->update('updated_at', Carbon::now()->toDateTimeString());

                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized'
                ], 401);
                if(!empty($user)) {
                    $request->request->add(['userid' => $user->id]);
                    return $user;
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized'
                ], 401);
            }
        });
    }
}
