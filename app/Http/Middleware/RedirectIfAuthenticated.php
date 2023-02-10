<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RedirectIfAuthenticated
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
        if (Auth::guard(null)->check()) {
            throw new NotFoundHttpException();
        }

        return $next($request);
    }
}
