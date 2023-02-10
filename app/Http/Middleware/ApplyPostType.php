<?php

namespace App\Http\Middleware;

use App\Classes\PostType;
use App\Events\PostType\PostIndexQuery;
use Carbon\Carbon;
use Closure;

class ApplyPostType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param PostType $post_type
     * @return mixed
     */
    public function handle($request, Closure $next, $post_type)
    {
        $request->route()->setParameter(
            'post_type',
            $post_type);
        return $next($request);
    }
}
