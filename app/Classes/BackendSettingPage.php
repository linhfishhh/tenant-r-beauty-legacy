<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 12-Mar-18
 * Time: 17:27
 */

namespace App\Classes;


use Illuminate\View\View;

abstract class BackendSettingPage {

    protected $settings = [];

    abstract public function getSlug():string;
	abstract public function getMenuTitle():string;
	abstract public function getMenuIcon():string;
	abstract public function getMenuOrder():int;
	abstract public function getPermissionTitle():string;
	abstract public function getPermissionOrder():int ;
    abstract public function getPageTitle():string;
    abstract public function getView($settings = []):View;

	public function getRules(){
	    return [];
    }

    public function getMessages(){
	    return [];
    }

    public function getParentMenuSlug(){
	    return 'setting_pages';
    }

    public function saveSettings($input_fields = []){
        if(property_exists($this, 'settings')){
            if(is_array($this->settings)){
                foreach ($this->settings as $field_name=> $settings){
                    if(!isset($settings[0])){
                        continue;
                    }
                    $name = $settings[0];
                    $autoload = isset($settings[1])?$settings[1]:0;
                    $value = isset($settings[2])?$settings[2]:null;
                    $value = isset($input_fields[$field_name])?$input_fields[$field_name]:$value;
                    setSetting($name, $value,$autoload);
                }
            }
        }
    }

    public function handleField($field_name, $value){
	    return $value;
    }

    public function getSettings(){
	    $rs = [];
        if(property_exists($this, 'settings')){
            foreach ($this->settings as $field_name=> $settings){
                if(!isset($settings[0])){
                    continue;
                }
                $name = $settings[0];
                $default_value = isset($settings[2])?$settings[2]:null;
                $rs[$name] = $default_value;
            }
            if(count($rs)>0){
                $rs = getSettings($rs);
            }
        }
        return $rs;
    }

	public function getMenuSlug(){
		return 'backend_option.'.$this->getSlug();
	}

	public function getPermissionGroupID(){
		return 'settings';
	}

	public function getPermissionID(){
		return 'manage_option_'.$this->getSlug();
	}

	public function getPermission(){
		return new Permission( $this->getPermissionID(), $this->getPermissionTitle(), $this->getPermissionGroupID(),$this->getPermissionOrder());
	}
}