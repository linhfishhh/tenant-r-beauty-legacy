<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class HasAllPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$permissions)
    {
        $ok = true;
        if (!\Auth::user()) {
            $ok = false;
        } else {
            if (!\Auth::user()->hasAllPermissions($permissions)) {
                $ok = false;
            }
        }
        if (!$ok) {
            throw new NotFoundHttpException();
        }
        return $next($request);
    }
}
