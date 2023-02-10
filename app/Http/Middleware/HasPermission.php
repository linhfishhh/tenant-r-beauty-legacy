<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class HasPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        $ok = true;
        if (!\Auth::user()) {
            $ok = false;
        } else {
            if (!\Auth::user()->hasPermission($permission)) {
                $ok = false;
            }
        }
        if (!$ok) {
            throw new NotFoundHttpException();
        }
        return $next($request);
    }
}
