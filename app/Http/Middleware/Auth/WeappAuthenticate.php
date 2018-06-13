<?php

namespace App\Http\Middleware\Auth;

use App\Define\Retcode;
use App\Exceptions\EvaException;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class WeappAuthenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        config(['auth.defaults.guard' => 'weappAuth']);
        if ($this->auth->guard($guard)->guest()) {
            return errorCode('logic.notLogin');
        }

        return $next($request);
    }
}
