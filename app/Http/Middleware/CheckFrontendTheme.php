<?php

namespace App\Http\Middleware;

use App\Classes\Theme;
use Closure;
use Symfony\Component\HttpKernel\Exception\FatalErrorException;

class CheckFrontendTheme
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $current_theme = Theme::getCurrentTheme();
        if(!$current_theme){
            throw new \RuntimeException(__('Bạn chưa chọn theme cho website'));
        }
        return $next($request);
    }
}
