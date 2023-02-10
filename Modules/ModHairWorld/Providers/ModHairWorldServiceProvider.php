<?php

namespace Modules\ModHairWorld\Providers;

use App\Events\AfterHtmlBlock;
use App\Events\BackendMenuItemCheckActive;
use App\Events\BackendMenuItemRegister;
use App\Events\BackendSettingPageRegister;
use App\Events\DefineContent;
use App\Events\FileCategoryRegister;
use App\Events\MenuTypeRegister;
use App\Events\PermissionRegister;
use App\Events\SocialRegisterSuccessNoAccount;
use App\Events\ThemeIndexViewData;
use App\Events\ThemePostTypeIndexViewData;
use App\Events\ThemePostViewData;
use App\Events\ThumbnailSizeRegister;
use App\Events\User\UserCreated;
use App\Events\User\UserSaved;
use App\Events\User\UserSaving;
use App\Events\User\UserStoreRequestMessages;
use App\Events\User\UserStoreRequestRules;
use App\Events\User\UserUpdateRequestMessages;
use App\Events\User\UserUpdateRequestRules;
use App\Events\UserFilterQuery;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\ServiceProvider;
use Modules\ModHairWorld\Console\taodoc;
use Modules\ModHairWorld\Console\WASalonCache;
use Modules\ModHairWorld\Console\WACache;
use Modules\ModHairWorld\Entities\SalonService;
use Modules\ModHairWorld\Entities\SalonServiceCategory;
use Modules\ModHairWorld\Entities\SalonServiceReviewCriteria;
use Modules\ModHairWorld\Events\ChangeTimeRequestCreated;
use Modules\ModHairWorld\Events\ReviewCreated;
use Modules\ModHairWorld\Events\ReviewDeleted;
use Modules\ModHairWorld\Events\ReviewLikeCreated;
use Modules\ModHairWorld\Events\ReviewLikeDeleted;
use Modules\ModHairWorld\Events\SalonBrandDeleted;
use Modules\ModHairWorld\Events\SalonDeleted;
use Modules\ModHairWorld\Events\SalonLikeCreated;
use Modules\ModHairWorld\Events\SalonLikeDeleted;
use Modules\ModHairWorld\Events\SalonOrderCreated;
use Modules\ModHairWorld\Events\SalonOrderDeleted;
use Modules\ModHairWorld\Events\SalonOrderProcessed;
use Modules\ModHairWorld\Events\SalonOrderRetrieved;
use Modules\ModHairWorld\Events\SalonOrderUpdated;
use Modules\ModHairWorld\Events\SalonOrderWaitingToProcess;
use Modules\ModHairWorld\Events\SalonSaving;
use Modules\ModHairWorld\Events\SalonServiceCatDeleted;
use Modules\ModHairWorld\Events\SalonServiceDeleted;
use Modules\ModHairWorld\Events\SalonServiceOptionDeleted;
use Modules\ModHairWorld\Events\SalonServiceOptionSaved;
use Modules\ModHairWorld\Events\SalonServiceSaleDeleted;
use Modules\ModHairWorld\Events\SalonServiceSaleSaved;
use Modules\ModHairWorld\Events\SalonServiceSaved;
use Modules\ModHairWorld\Events\SalonServiceSaving;
use Modules\ModHairWorld\Events\SalonShowcaseDeleted;
use Modules\ModHairWorld\Events\SalonShowcaseItemDeleted;
use Modules\ModHairWorld\Events\SalonShowcaseLikeCreated;
use Modules\ModHairWorld\Events\SalonShowcaseLikeDeleted;
use Modules\ModHairWorld\Events\SalonStylistDeleted;
use Modules\ModHairWorld\Listeners\APITokenCreated;
use Modules\ModHairWorld\Listeners\BackendMenuActive;
use Modules\ModHairWorld\Listeners\BackendMenuRegister;
use Modules\ModHairWorld\Listeners\BackendUserSaved;
use Modules\ModHairWorld\Listeners\BackendUserSaving;
use Modules\ModHairWorld\Listeners\BackendUserStoreUpdateRequestMessages;
use Modules\ModHairWorld\Listeners\BackendUserStoreUpdateRequestRules;
use Modules\ModHairWorld\Listeners\FileCatRegister;
use Modules\ModHairWorld\Listeners\NewAccountFromSocial;
use Modules\ModHairWorld\Listeners\PostTypeRegister;
use Modules\ModHairWorld\Listeners\Theme\HomePageIndexData;
use Modules\ModHairWorld\Listeners\Theme\NewsDetailData;
use Modules\ModHairWorld\Listeners\Theme\NewsIndexData;
use Laravel\Passport\Events\AccessTokenCreated;
use Modules\ModHairWorld\Listeners\UserEditInfoHook;

class ModHairWorldServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            taodoc::class,
            WACache::class
        ]);
        $this->app->singleton('review_criterias', function (){
            return SalonServiceReviewCriteria::all();
        });
        $this->app->singleton('event_suppress', function (){
            return collect();
        });
        \Event::listen(
            AccessTokenCreated::class,
                    APITokenCreated::class
        );
        \Event::listen(
        BackendMenuItemRegister::class,
        BackendMenuRegister::class);
        \Event::listen(
            PermissionRegister::class,
            \Modules\ModHairWorld\Listeners\PermissionRegister::class);
        \Event::listen(
            BackendMenuItemCheckActive::class,
            BackendMenuActive::class);
        \Event::listen(
            FileCategoryRegister::class,
            FileCatRegister::class);
        \Event::listen(BackendSettingPageRegister::class, \Modules\ModHairWorld\Listeners\BackendPageRegister::class);

        \Event::listen(SalonDeleted::class, \Modules\ModHairWorld\Listeners\SalonDeleted::class);
        \Event::listen(SalonSaving::class, \Modules\ModHairWorld\Listeners\SalonSaving::class);

        \Event::listen(SalonServiceDeleted::class, \Modules\ModHairWorld\Listeners\SalonServiceDeleted::class);
        \Event::listen(SalonServiceSaved::class, \Modules\ModHairWorld\Listeners\SalonServiceSaved::class);
        \Event::listen(SalonServiceSaving::class, \Modules\ModHairWorld\Listeners\SalonServiceSaving::class);

        \Event::listen(SalonServiceSaleDeleted::class, \Modules\ModHairWorld\Listeners\SalonServiceSaleDeleted::class);
        \Event::listen(SalonServiceSaleSaved::class, \Modules\ModHairWorld\Listeners\SalonServiceSaleSaved::class);

        \Event::listen(SalonServiceOptionDeleted::class, \Modules\ModHairWorld\Listeners\SalonServiceOptionDeleted::class);
        \Event::listen(SalonServiceOptionSaved::class, \Modules\ModHairWorld\Listeners\SalonServiceOptionSaved::class);

        \Event::listen(SalonShowcaseDeleted::class, \Modules\ModHairWorld\Listeners\SalonShowcaseDeleted::class);
        \Event::listen(SalonOrderCreated::class, \Modules\ModHairWorld\Listeners\SalonOrderCreated::class);
        \Event::listen(SalonOrderDeleted::class, \Modules\ModHairWorld\Listeners\SalonOrderDeleted::class);

        \Event::listen(SalonOrderWaitingToProcess::class, \Modules\ModHairWorld\Listeners\SalonOrderWaitingToProcess::class);
        \Event::listen(SalonOrderProcessed::class, \Modules\ModHairWorld\Listeners\SalonOrderProcessed::class);

        \Event::listen(SalonOrderUpdated::class, \Modules\ModHairWorld\Listeners\SalonOrderUpdated::class);
        \Event::listen(SalonOrderRetrieved::class, \Modules\ModHairWorld\Listeners\SalonOrderRetrieved::class);

        \Event::listen(SalonServiceCatDeleted::class, \Modules\ModHairWorld\Listeners\SalonServiceCatDeleted::class);

        \Event::listen(ChangeTimeRequestCreated::class, \Modules\ModHairWorld\Listeners\ChangeTimeRequestCreated::class);

        \Event::listen(SalonStylistDeleted::class, \Modules\ModHairWorld\Listeners\SalonStylistDeleted::class);
        \Event::listen(SalonBrandDeleted::class, \Modules\ModHairWorld\Listeners\SalonBrandDeleted::class);

        \Event::listen(SalonLikeCreated::class, \Modules\ModHairWorld\Listeners\SalonLikeCreated::class);
        \Event::listen(SalonLikeDeleted::class, \Modules\ModHairWorld\Listeners\SalonLikeDeleted::class);
        \Event::listen(SalonShowcaseLikeCreated::class, \Modules\ModHairWorld\Listeners\SalonShowcaseLikeCreated::class);
        \Event::listen(SalonShowcaseLikeDeleted::class,\Modules\ModHairWorld\Listeners\SalonShowcaseLikeDeleted::class);
        \Event::listen(SalonShowcaseItemDeleted::class,\Modules\ModHairWorld\Listeners\SalonShowcaseItemDeleted::class);

        \Event::listen(ReviewLikeCreated::class, \Modules\ModHairWorld\Listeners\ReviewLikeCreated::class);
        \Event::listen(ReviewLikeDeleted::class, \Modules\ModHairWorld\Listeners\ReviewLikeDeleted::class);

        \Event::listen(ReviewDeleted::class, \Modules\ModHairWorld\Listeners\ReviewDeleted::class);
        \Event::listen(ReviewCreated::class, \Modules\ModHairWorld\Listeners\ReviewCreated::class);

        \Event::listen(ThumbnailSizeRegister::class, \Modules\ModHairWorld\Listeners\ThumbnailSizeRegister::class);
        \Event::listen(DefineContent::class, PostTypeRegister::class);
        \Event::listen(MenuTypeRegister::class, \Modules\ModHairWorld\Listeners\MenuTypeRegister::class);
        \Event::listen(SocialRegisterSuccessNoAccount::class, NewAccountFromSocial::class);

        \Event::listen(AfterHtmlBlock::class,UserEditInfoHook::class);

        \Event::listen(UserStoreRequestRules::class,BackendUserStoreUpdateRequestRules::class);
        \Event::listen(UserUpdateRequestRules::class,BackendUserStoreUpdateRequestRules::class);

        \Event::listen(UserStoreRequestMessages::class,BackendUserStoreUpdateRequestMessages::class);
        \Event::listen(UserUpdateRequestMessages::class,BackendUserStoreUpdateRequestMessages::class);

        \Event::listen(UserSaved::class,BackendUserSaved::class);
        \Event::listen(UserSaving::class,BackendUserSaving::class);
        \Event::listen(UserCreated::class,\Modules\ModHairWorld\Listeners\UserCreated::class);

        \Event::listen(UserFilterQuery::class,\Modules\ModHairWorld\Listeners\UserFilterQuery::class);



        \Event::listen(DefineContent::class, function(DefineContent $event){
            $event->registerPostType(SalonServiceCategory::class);
        });

        $this->themeHook();
    }

    private function themeHook(){
        \Event::listen(ThemeIndexViewData::class, HomePageIndexData::class);
        \Event::listen(ThemePostTypeIndexViewData::class, NewsIndexData::class);
        \Event::listen(ThemePostViewData::class, NewsDetailData::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('modhairworld.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'modhairworld'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/modhairworld');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/modhairworld';
        }, \Config::get('view.paths')), [$sourcePath]), 'modhairworld');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/modhairworld');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'modhairworld');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'modhairworld');
        }
    }

    /**
     * Register an additional directory of factories.
     * @source https://github.com/sebastiaanluca/laravel-resource-flow/blob/develop/src/Modules/ModuleServiceProvider.php#L66
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
