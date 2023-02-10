<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 26-Feb-18
 * Time: 14:43
 */

namespace App\Classes;


use App\MenuItem;

abstract class MenuType
{
    protected $load_fn_name;
    protected $id_prefix;


    abstract function getID():string;
    abstract function getTitle():string;
    abstract function getOrder():int;
    abstract function getGroupID():string;
    abstract function getIcon():string;
    abstract function getHtmlView();
    abstract function rules(array $rules):array;
    abstract function messages(array $messages):array;

    public function __construct()
    {
        $this->id_prefix = 'menu_type_'.str_slug($this->getID(),'_');
        if($this->getHtmlView() == false){
            $this->load_fn_name = false;
        }
        else{
            $this->load_fn_name = $this->id_prefix.'_load';
        }
    }

    public function getJSID(){
        return $this->id_prefix;
    }

    public function getJSLoad(){
        return $this->load_fn_name;
    }

    public function renderHtmlView(){
        if($this->getHtmlView() != false){
            return \View::make($this->getHtmlView())->with('type', $this)->render();
        }
        return '';
    }

    public function getViewData(){
    	return [];
    }

    /**
     * @param MenuItem $item
     * @return bool
     */
    public function checkActive($item){
        return false;
    }

    /**
     * @param MenuItem $item
     * @return string
     */
    public function getURL($item){
        return '#';
    }
}