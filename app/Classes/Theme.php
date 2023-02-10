<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 23-Feb-18
 * Time: 16:41
 */

namespace App\Classes;


use App\Events\ThemeGlobalViewData;
use App\Menu;
use App\RevSlider;
use App\ThemeMenu;
use Nwidart\Modules\Laravel\Module;

class Theme
{
    public static function getCurrentTheme()
    {
        $rs = false;
        $themes = Theme::getThemes();
        foreach ($themes as $theme) {
            if ($theme->enabled()) {
                $rs = $theme;
                break;
            }
        }
        return $rs;
    }

    public static function getViewName($view_name)
    {
        $current_theme = Theme::getCurrentTheme();
        if (!$current_theme) {
            return false;
        }
        $view_name = $current_theme->getAlias() . '::' . $view_name;
        if (!\View::exists($view_name)) {
            return false;
        }
        return $view_name;
    }

    public static function getView(
        $view_name,
        $data = []
    ) {
        $theme_view_name = Theme::getViewName($view_name);
        if (!$theme_view_name) {
            throw new \RuntimeException(
                __(
                    'Theme hiện tại thiếu view template ":view.blade.php", vui lòng tạo trong folder "resources/view"',
                    ['view' => $view_name]
                )
            );
        }
        return view(
            $theme_view_name,
            $data
        );
    }

    public static function getIndexViewName()
    {
        return Theme::getViewName('home');
    }

    public static function getIndexView($data = [])
    {
        return Theme::getView(
            'home',
            $data
        );
    }


    public static function getMenuLocations($theme = null)
    {
        $rs = [];
        if ($theme == null) {
            $theme = Theme::getCurrentTheme();
        } else {
            $theme = \Module::find($theme);
        }
        if ($theme) {
            $locations = $theme->get(
                'menu_locations',
                []
            );
            if (is_array($locations)) {
                foreach ($locations as $location) {
                    if (!isset($location['id']) || !isset($location['title'])) {
                        continue;
                    }
                    $rs[$location['id']] = $location['title'];
                }
            }
        }
        return $rs;
    }

    public static function getSidebarLocations($theme = null)
    {
        $rs = [];
        if ($theme == null) {
            $theme = Theme::getCurrentTheme();
        } else {
            $theme = \Module::find($theme);
        }
        if ($theme) {
            $locations = $theme->get(
                'sidebar_locations',
                []
            );
            if (is_array($locations)) {
                foreach ($locations as $location) {
                    if (!isset($location['id']) || !isset($location['title'])) {
                        continue;
                    }
                    $rs[$location['id']] = $location['title'];
                }
            }
        }
        return $rs;
    }

    public static function getThemes()
    {
        /** @var \Nwidart\Modules\Laravel\Module[] $themes */
        $themes = \Module::all();
        $themes = array_filter(
            $themes,
            function (\Nwidart\Modules\Laravel\Module $module) {
                return $module->get(
                        'theme',
                        0
                    ) == 1;
            }
        );
        return $themes;
    }


    public static function getCover($theme = null)
    {
        if ($theme) {
            /** @var Module $module */
            $module = \Module::find($theme);
            if (!$module) {
                return false;
            }
            $path = $module->getExtraPath('theme.jpg');
            if (!\File::exists($path)) {
                return false;
            }
            return route(
                'backend.theme.cover',
                ['theme' => $theme]
            );
        }
        return false;
    }

    public static function getAssetUrl($asset_file_path)
    {
        $current_theme = Theme::getCurrentTheme();
        if (!$current_theme) {
            return false;
        }
        return \App\Classes\Module::getAssetUrl(
            $current_theme->getAlias(),
            $asset_file_path
        );
    }

    public static function fullLoadThemeMenus(
        $language = false
    ){
        $rs = collect();
        $current_theme = Theme::getCurrentTheme();
        if (!$current_theme) {
            return;
        }
        if (!$language) {
            $language = app()->getLocale();
        }
        $location_ids = array_keys(Theme::getMenuLocations());
        $loaded_menus = app('loaded_menus');
        $search_ids = [];
        foreach ($location_ids as $location_id){
            if(!$loaded_menus->has($location_id)){
                $search_ids[] = $location_id;
            }
        }
        $rs = ThemeMenu::whereTheme($current_theme->getAlias())->where(
            'language',
            '=',
            $language
        )->whereIn(
            'location',
            $search_ids
        )->with('menu.items')
            ->get();
        if($rs){
            foreach ($rs as $menu){
                $loaded_menus->put($menu->location, $menu->menu);
            }
        }
    }

    /**
     * @param string $location_id
     * @param bool|string $language
     * @return bool|Menu
     */
    public static function getMenu(
        $location_id,
        $language = false
    ) {
        $current_theme = Theme::getCurrentTheme();
        if (!$current_theme) {
            return false;
        }
        $rs = false;
        if (!$language) {
            $language = app()->getLocale();
        }
        $loaded_menus = app('loaded_menus');
        if($loaded_menus->has($location_id)){
            return $loaded_menus->get($location_id);
        }

        $theme_menu = ThemeMenu::whereTheme($current_theme->getAlias())->where(
            'language',
            '=',
            $language
        )->where(
            'location',
            '=',
            $location_id
        )->with('menu.items')
        ->first();
        if($theme_menu){
            if($theme_menu->menu){
                $rs = $theme_menu->menu;
                $loaded_menus->put($location_id, $rs);
            }
        }
        return $rs;
    }

    public static function hasSlider($alias)
    {
        $sliders = app('loaded_sliders');
        if($sliders->has($alias)){
            return true;
        }
        return RevSlider::whereAlias($alias)->count() > 0;
    }

    public static function getSlider($alias){
        $sliders = app('loaded_sliders');
        if($sliders->has($alias)){
            return $sliders->get($alias);
        }
        $slider = RevSlider::whereAlias($alias)->first();
        if($slider){
            $sliders->put($alias, $slider);
        }
        return $slider;
    }

    public static function getThemeGlobalViewData(){
        $data = [];
        $event = new ThemeGlobalViewData($data);
        event($event);
        return $event->data;
    }
}