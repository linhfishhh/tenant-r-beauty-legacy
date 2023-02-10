<?php

namespace App\Http\Middleware;

use App\Classes\PostType;
use App\Events\PostType\PostDetailQuery;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApplyPost
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var PostType $post_type */
        $post_type = $request->route()->parameter('post_type');
        $post_slug = $request->route()->parameter('post_slug');
        $post_query = $post_type::getPublicDetailQuery($post_slug);
        $event = new PostDetailQuery($post_query);
        event($event);
        $event->do_after_register();
        $post= $post_query->first();
        if(!$post){
            throw new NotFoundHttpException();
        }
        $request->route()->setParameter(
            'post',
            $post);
        return $next($request);
    }
}
