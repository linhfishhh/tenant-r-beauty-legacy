<?php

namespace App\Http\Middleware;

use App\Classes\Taxonomy;
use App\Events\Taxonomy\TermDetailPostsQuery;
use App\Events\Taxonomy\TermDetailQuery;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApplyTerm
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
        /** @var Taxonomy $taxonomy */
        $taxonomy = $request->route()->parameter('taxonomy');
        $term_slug = $request->route()->parameter('term_slug');
        $query = $taxonomy::getPublicDetailQuery($term_slug);
        $event = new TermDetailQuery($query);
        event($event);
        $event->do_after_register();
        /** @var Taxonomy $term */
        $term = $event->query->first();
        if(!$term){
            throw new NotFoundHttpException();
        }
        $posts_query = $term->publicPosts();
        $post_event = new TermDetailPostsQuery($posts_query);
        event($post_event);
        $post_event->do_after_register();
        $request->route()->setParameter(
            'term',
            $term);
        $request->route()->setParameter(
            'posts_query',
            $posts_query);
        return $next($request);
    }
}
