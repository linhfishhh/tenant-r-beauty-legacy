<?php

namespace App\Http\Middleware;

use App\Classes\Taxonomy;
use App\Events\Taxonomy\TermIndexQuery;
use Closure;

class ApplyTermIndex
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
        /** @var Taxonomy $taxonomy */
        $taxonomy = $request->route()->parameter('taxonomy');
        $query = $taxonomy::getPublicIndexQuery();
        $event = new TermIndexQuery($query);
        event($event);
        $event->do_after_register();
        $terms = $event->query;
        $request->route()->setParameter(
            'terms_query',
            $terms);
        return $next($request);
    }
}
