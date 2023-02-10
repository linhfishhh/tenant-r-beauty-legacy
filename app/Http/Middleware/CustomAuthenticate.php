<?php

namespace App\Http\Middleware;

use App\Exceptions\CustomAuthenticationException;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class CustomAuthenticate
{
    /**
     * The authentication factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }


    public function handle($request, Closure $next, $login_route = null, ...$guards)
    {
        $this->authenticate($login_route, $guards);

        return $next($request);
    }

    /**
     * Determine if the user is logged in to any of the given guards.
     *
     * @param  array $guards
     * @return void
     *
     * @throws CustomAuthenticationException
     */
    protected function authenticate($login_route, array $guards)
    {
        if (empty($guards)) {
            return $this->auth->authenticate();
        }

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                return $this->auth->shouldUse($guard);
            }
        }

        throw new CustomAuthenticationException('Unauthenticated.', $login_route, $guards);
    }
}
