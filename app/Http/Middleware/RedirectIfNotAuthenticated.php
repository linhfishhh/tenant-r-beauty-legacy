<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param string                    $redirect_to
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $redirect_to = '/')
    {
        if (!Auth::guard(null)->check()) {
            if(\Route::has( $redirect_to)){
                return redirect(route( $redirect_to));
            }
            return redirect($redirect_to);
        }

        return $next($request);
    }
}
