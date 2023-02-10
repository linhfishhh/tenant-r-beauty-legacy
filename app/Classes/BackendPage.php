<?php
namespace App\Classes;


abstract class BackendPage
{
    abstract public static function getPageSlug():string;
    abstract public static function getMenuID():string;
    abstract public static function getMenuTitle():string;
    abstract public static function getMenuIcon():string;
    abstract public static function getMenuParentID():string;
    abstract public static function getMenuRoute();
    abstract public static function getMenuOrder():int;
    abstract public static function getPermission():string;
    public static function getPermissionGroup(){
        return 'backend';
    }
    public static function getView(){

    }
}