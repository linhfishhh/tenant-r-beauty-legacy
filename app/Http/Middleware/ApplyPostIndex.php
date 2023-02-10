<?php

namespace App\Http\Middleware;

use App\Classes\PostType;
use App\Events\PostType\PostIndexQuery;
use Carbon\Carbon;
use Closure;

class ApplyPostIndex
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
        /** @var PostType $post_type */
        $post_type = $request->route()->parameter('post_type');
        $posts_query = $post_type::getPublicIndexQuery();
        $event = new PostIndexQuery($posts_query);
        event($event);
        $event->do_after_register();
        $posts = $event->query;
        $request->route()->setParameter(
            'posts_query',
            $posts);
        return $next($request);
    }
}
