<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckTaxonomyManage
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
	    $taxonomy = $request->route()->parameter( 'taxonomy');
	    if(!Auth::user()->hasPermission( $taxonomy::getManagePermissionID())){
		    if($request->ajax()){
				throw new UnauthorizedException(__('Bạn không được phép truy cập chức năng này'));
		    }
		    else{
			    throw new NotFoundHttpException();
		    }
	    }
        return $next($request);
    }
}
