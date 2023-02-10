<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\ModHairWorld\Entities\ThemeConfigPages\Salon;

class SimpleCache
{

    /**
     * @param Request $request
     * @param Closure $next
     * @param $path
     * @return Response
     */
    public function handle($request, Closure $next, $path)
    {
        /** @var Response $response */
        $response = $next($request);
        if($path=='*salon*'){
            /** @var Salon $salon */
            $path = $request->path().'/index.html';
            $file = public_path($path);
        }
        else{
            $file = public_path($path);
        }
        $dir = dirname($file);
        if(!\File::exists($dir)){
            \File::makeDirectory($dir, 493, true);
        }
        \File::put(public_path($path), $response->content());
        return $response;
    }
}
