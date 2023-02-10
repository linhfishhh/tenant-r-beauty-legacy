<?php

namespace Modules\ModHairWorld\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Modules\ModHairWorld\Entities\SalonManager;

class AutoGetSalon
{
    public function handle(Request $request, Closure $next)
    {
        $user = \Auth::user();
        $manager = SalonManager::whereUserId($user->id)->with(['salon'])->first();
        if(!$manager){
            abort(400, 'Bạn không có quyền quản lý salon');
        }
        if(!$manager->salon){
            abort(400, 'Bạn không có quyền quản lý salon');
        }
        $request->route()->setParameter('salon',$manager->salon);
        return $next($request);
    }
}