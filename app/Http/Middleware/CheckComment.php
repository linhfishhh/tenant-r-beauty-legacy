<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckComment
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
        $type = $request->route()->parameter( 'post_type');
        $type = getPostType( $type);
        if($type == null){
            throw new NotFoundHttpException();
        }
        $comment_type = $type::getCommentType();
        if(!$comment_type){
            throw new NotFoundHttpException();
        }
        $request->route()->setParameter( 'post_type', $type);
        return $next($request);
    }
}
