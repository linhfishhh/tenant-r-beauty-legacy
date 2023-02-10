<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\TrustProxies::class,
        \App\Http\Middleware\Cors::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            //\Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api-nl' => [
            'throttle:60,1',
            'bindings',
        ],

        'api' => [
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'scopes' => \Laravel\Passport\Http\Middleware\CheckScopes::class,
        'scope' => \Laravel\Passport\Http\Middleware\CheckForAnyScope::class,
        'org_auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth' => \App\Http\Middleware\CustomAuthenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'user' => \App\Http\Middleware\RedirectIfNotAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'frontend' => \App\Http\Middleware\CheckFrontend::class,
        'backend' => \App\Http\Middleware\CheckBackend::class,
        'permission.has' => \App\Http\Middleware\HasPermission::class,
        'permission.any' => \App\Http\Middleware\HasAnyPermissions::class,
        'permission.all' => \App\Http\Middleware\HasAllPermissions::class,
	    'post_type' => \App\Http\Middleware\CheckPostType::class,
	    'taxonomy' => \App\Http\Middleware\CheckTaxonomy::class,
        'comment' => \App\Http\Middleware\CheckComment::class,
        'frontend.taxonomy' => \App\Http\Middleware\ApplyTaxonomy::class,
        'frontend.term.index' => \App\Http\Middleware\ApplyTermIndex::class,
        'frontend.post_type' => \App\Http\Middleware\ApplyPostType::class,
        'frontend.post_type.index' => \App\Http\Middleware\ApplyPostIndex::class,
        'frontend.post' => \App\Http\Middleware\ApplyPost::class,
        'frontend.post.attachment' => \App\Http\Middleware\ApplyPostAttachment::class,
        'frontend.term' => \App\Http\Middleware\ApplyTerm::class,
        'taxonomy.manage' => \App\Http\Middleware\CheckTaxonomyManage::class,
        'backend.settings.page' => \App\Http\Middleware\CheckBackendSettingPage::class,
        'frontend.theme' =>  \App\Http\Middleware\CheckFrontendTheme::class,
        'frontend.cache' => \App\Http\Middleware\SimpleCache::class,
    ];
}
