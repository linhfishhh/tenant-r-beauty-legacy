<?php

namespace App\Providers;

use App\Events\BackendDashboardWidgetRegister;
use App\Events\BackendMenuItemRegister;
use App\Events\BackendSettingPageRegister;
use App\Events\DefineContent;
use App\Events\FileCategoryRegister;
use App\Events\FileTypeGroupRegister;
use App\Events\MenuTypeRegister;
use App\Events\PermissionRegister;
use App\Events\ThumbnailSizeRegister;
use App\Events\TinyMCEScriptHook;
use App\Events\WidgetTypeRegister;
use App\Setting;
use Illuminate\Support\ServiceProvider;
use Schema;

class AppServiceProvider extends ServiceProvider
{
    
    public function boot()
    {
	    Schema::defaultStringLength(191);
    }
    
    public function register()
    {
	    $this->app->bind('path.public', function() {
		    return base_path('public_html');
	    });
        $this->app->bind('is_frontend');
        $this->app->bind('is_backend');

        $this->app->singleton('settings', function (){
            $rs = Setting::whereAutoload(true)->get();
            return $rs->mapWithKeys(function (Setting $setting){
                return [
                    $setting->name => json_decode($setting->value)
                ];
            });
        });

	    if ($this->app->environment() !== 'production') {
		    $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
	    }

	    $this->app->singleton( 'backend_menu', function (){
	    	$event  = new BackendMenuItemRegister();
	    	event($event);
		    $event->do_after_register();
            $event->sortItems();
	    	return $event;
	    });

	    $this->app->singleton( 'menu_types', function (){
		    $event  = new MenuTypeRegister();
		    event($event);
		    $event->do_after_register();
		    return $event;
	    });

        $this->app->singleton( 'backend_dashboard_widgets', function (){
            $event  = new BackendDashboardWidgetRegister();
            event($event);
            $event->do_after_register();
            return $event;
        });

	    $this->app->singleton( 'widget_types', function (){
		    $event  = new WidgetTypeRegister();
		    event($event);
		    return $event;
	    });

	    $this->app->singleton( 'permissions', function (){
		    $event  = new PermissionRegister();
		    event($event);
		    $event->do_after_register();
            $event->groups = $event->groups->sortBy('order');
            $event->permissions = $event->permissions->sortBy('order');
		    return $event;
	    });

	    $this->app->singleton( 'post_types', function (){
		    $event  = new DefineContent();
		    event($event);
		    $event->do_after_register();
		    return $event;
	    });

        $this->app->singleton( 'file_categories', function (){
            $event  = new FileCategoryRegister();
            event($event);
            $event->do_after_register();
            return $event;
        });

	    $this->app->singleton( 'file_type_groups', function (){
		    $event  = new FileTypeGroupRegister();
		    event($event);
		    $event->do_after_register();
		    return $event;
	    });

	    $this->app->singleton( 'thumbnail_sizes', function (){
		    $event  = new ThumbnailSizeRegister();
		    event($event);
		    $event->do_after_register();
		    return $event;
	    });

        $this->app->singleton( 'backend_setting_pages', function (){
            $event  = new BackendSettingPageRegister();
            event($event);
            $event->do_after_register();
            return $event;
        });

        $this->app->singleton( 'tinymce_scripts', function (){
            $event  = new TinyMCEScriptHook();
            event($event);
            $event->do_after_register();
            return $event;
        });
    }
}
