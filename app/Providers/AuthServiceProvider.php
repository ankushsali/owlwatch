<?php

namespace App\Providers;

use App\Models\ApiToken;
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
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->header('api-token') && $request->header('user-id')){
                $user_id = $request->header('user-id');
                  
                $where_array = array(
                  "token" =>$request->header('api-token'),
                  "user_id" =>$request->header('user-id')
                );
                $user = ApiToken::where($where_array)->first();
                return $user;
            }
        });
    }
}
