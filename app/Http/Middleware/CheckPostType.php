<?php

namespace App\Http\Middleware;

use App\Classes\PostType;
use Closure;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckPostType
{
	/**
	 * @param \Request $request
	 * @param Closure $next
	 *
	 * @return mixed
	 */
	public function handle($request, Closure $next)
    {
    	$type = $request->route()->parameter( 'post_type');
    	$type = getPostTypeByPublicSlug( $type);
		if($type == null){
			throw new NotFoundHttpException();
		}
	    $request->route()->setParameter( 'post_type', $type);
		$post_id =  $request->route()->parameter( 'post', null);
		if($post_id){
			$post = $type::find($post_id);
			if(!$post){
				throw new NotFoundHttpException();
			}
			$request->route()->setParameter( 'post', $post);
		}
        return $next($request);
    }
}
