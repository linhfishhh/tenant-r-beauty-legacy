<?php

namespace App\Providers;

use Blade;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use View;

class BladeServiceProvider extends ServiceProvider {
    
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {

        Blade::directive( 'enqueueBundle',
            function (
                $input
            ) {
                return "<?php enqueueBundle(" . $input . "); ?>";
            } );
        Blade::directive( 'enqueueJS',
            function (
                $input
            ) {
                return "<?php enqueueJS(" . $input . "); ?>";
            } );
        Blade::directive( 'enqueueJSByID',
            function (
                $input
            ) {
                return "<?php enqueueJSByID(" . $input . "); ?>";
            } );
        Blade::directive( 'showJSQueue',
            function ( $input ) {
                return "<?php showJSQueue(" . $input . "); ?>";
            } );
        Blade::directive( 'enqueueCSS',
            function ( $input ) {
                return "<?php enqueueCSS(" . $input . "); ?>";
            } );
        Blade::directive( 'enqueueCSSByID',
            function ( $input ) {
                return "<?php enqueueCSSByID(" . $input . "); ?>";
            } );
        Blade::directive( 'showCSSQueue',
            function ( $input ) {
                return "<?php showCSSQueue(" . $input . "); ?>";
            } );
        Blade::directive( 'dump',
            function ( $input ) {
                return "<?php dump(" . $input . "); ?>";
            } );
        Blade::directive( 'event',
            function ( $input ) {
                return "<?php event(" . $input . "); ?>";
            } );
        Blade::if( 'unique',
            function ( $id ) {
                $unique_blocks = app('unique_blocks');
                if($unique_blocks->has($id)){
                    return false;
                }
                else{
                    $unique_blocks->put($id, $id);
                    return true;
                }
            } );

        Blade::if( 'hasPermission',
            function ( $permission ) {
                return \Auth::user()->hasPermission($permission);
            } );
        Blade::if( 'route',
            function ( $route_name ) {
                return \Route::currentRouteName() == $route_name;
            } );
        Blade::if( 'notRoute',
            function ( $route_name ) {
                return \Route::currentRouteName() != $route_name;
            } );
        Blade::if( 'hasAnyPermissions',
            function ( $permissions ) {
                return \Auth::user()->hasAnyPermissions($permissions);
            } );
        Blade::if( 'hasAllPermissions',
            function ( $permissions ) {
                return \Auth::user()->hasAllPermissions($permissions);
            } );
    }
    
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        $this->app->singleton( 'script_queue',
            function () {
                return new Collection();
            } );
        $this->app->singleton( 'css_queue',
            function () {
                return new Collection();
            } );
        $this->app->singleton( 'unique_blocks',
            function () {
                return new Collection();
            } );
        $this->app->singleton( 'loaded_menus', function (){
            return new Collection();
        });
        $this->app->singleton( 'loaded_sliders', function (){
            return new Collection();
        });
    }
}
