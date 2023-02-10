<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 28-Feb-18
 * Time: 17:43
 */

namespace App\Classes;


use Illuminate\Support\Facades\File;

class Module
{

    public static function getFile($module, $path){
        /** @var \Nwidart\Modules\Laravel\Module $module */
        $module = \Module::find($module);
        if(!$module){
            return false;
        }
        $rs = $module->getExtraPath($path);
        $rs = str_replace('//', '/', $rs);
        $rs = str_replace('\\\\', '\\', $rs);
        if(!File::exists($rs)){
            return false;
        }
        return $rs;
    }

    public static function getAsset($module, $path){
        return Module::getFile($module, 'Resources/assets/'.$path);
    }

    public static function getAssetUrl($module, $path){
        $asset_path = Module::getAsset($module, $path);
        if($asset_path){
            return route('frontend.module.asset.url', ['module'=>$module, 'file' => $path]);
        }
        return $asset_path;
    }
}