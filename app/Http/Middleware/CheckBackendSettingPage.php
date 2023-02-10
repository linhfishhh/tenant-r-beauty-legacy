<?php

namespace App\Http\Middleware;

use App\Classes\BackendSettingPage;
use App\Events\BackendSettingPageRegister;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckBackendSettingPage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws AuthenticationException
     */
    public function handle($request, Closure $next)
    {
        $page_slug = $request->route()->parameter('page');
        /** @var BackendSettingPageRegister $event */
        $event = app('backend_setting_pages');
        /** @var BackendSettingPage $page */
        $page = $event->getPage($page_slug,null);
        if($page == null){
            throw new NotFoundHttpException();
        }
        if(!me()->hasPermission($page->getPermissionID())){
            throw new AuthenticationException(__('Bạn không có quyền truy cập chức năng này'));
        }
        $request->route()->setParameter('page', $page);
        return $next($request);
    }
}
