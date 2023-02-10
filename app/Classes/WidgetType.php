<?php
namespace App\Classes;


abstract class WidgetType extends MenuType {
    public function __construct()
    {
        $this->id_prefix = 'widget_type_'.str_slug($this->getID(),'_');
        if($this->getHtmlView() == false){
            $this->load_fn_name = false;
        }
        else{
            $this->load_fn_name = $this->id_prefix.'_load';
        }
    }
}