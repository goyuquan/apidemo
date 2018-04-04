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
                $token = $request->header('Authorization');
                $user = User::where('remember_token', $token)->first();

                if(!empty($user)) {
                    $request->request->add(['userid' => $user->id]);
                    // $user->update('updated_at', Carbon::now()->toDateTimeString()); //TODO
                    return $user;
                } else {
                    return null;
                }
            } else {
                return null;
                return response()->json([
                    'status' => 'errorrrr',
                    'message' => 'Unauthorized'
                ], 401);
            }
        });
    }
}
