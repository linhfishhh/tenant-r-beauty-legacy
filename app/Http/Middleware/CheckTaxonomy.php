<?php

namespace App\Http\Middleware;

use App\Classes\Taxonomy;
use Closure;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckTaxonomy
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
    	$post_type = $request->route()->parameter( 'post_type');
    	$taxonomy = $request->route()->parameter( 'taxonomy');
		$taxonomy = getTaxonomy( $post_type, $taxonomy);
	    if($taxonomy == null){
		    throw new NotFoundHttpException();
	    }
	    $request->route()->setParameter( 'post_type', getPostType( $post_type));
	    $request->route()->setParameter( 'taxonomy', $taxonomy);
	    if($request->route()->hasParameter( 'term')){
	    	$term_id = $request->route()->parameter( 'term');
	    	$term = $taxonomy::find($term_id);
	    	if($term){
			    $request->route()->setParameter( 'term', $term);
		    }
		    else{
			    throw new NotFoundHttpException();
		    }
	    }
	    return $next($request);
    }
}
