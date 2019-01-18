<?php

namespace App\Providers;

use App\Logic\Weapp\Account;
use App\Models\UserModel;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redis;
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

        $this->app['auth']->viaRequest('weappAuth', function ($request) {
            $auth = $request->header('Auth') ?? '';
            if (!empty($auth)) {
                $redis = Redis::get('cherishTime:' . $auth);
                if (!empty($redis)) {
                    $redisObj = json_decode($redis);
                    //续命
                    Redis::setex('cherishTime:' . $auth, Account::AUTH_EXIST_TIME, $redis);
                    return new UserModel($redisObj->userId);
                }
            }
        });
    }
}
