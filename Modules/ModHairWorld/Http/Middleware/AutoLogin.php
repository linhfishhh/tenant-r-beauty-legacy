<?php

namespace Modules\ModHairWorld\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Modules\ModHairWorld\Entities\SalonManager;

class AutoLogin
{
    public function handle(Request $request, Closure $next)
    {
        if($request->bearerToken()){
            $auth = auth('api')->user();
            if($auth){
                \Auth::login($auth);
            }
        }
        return $next($request);
    }
}