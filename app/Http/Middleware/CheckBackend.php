<?php

namespace App\Http\Middleware;

use Closure;

class CheckBackend
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        \App::bind( 'is_frontend', function(){
            return false;
        });
        \App::bind( 'is_backend', function(){
            return true;
        });
        return $next($request);
    }
}
