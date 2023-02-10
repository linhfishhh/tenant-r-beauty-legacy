<?php

namespace App\Events;

use App\Classes\BackendDashBoardWidget;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BackendDashboardWidgetRegister
{
    use Dispatchable, SerializesModels;

    private $after_register = [];
    /** @var \Illuminate\Support\Collection|BackendDashBoardWidget[] $widgets */
    private $widgets;


    /**
     * @return \Illuminate\Support\Collection|BackendDashBoardWidget[]
     */
    public function getWidgets(){
        $rs = $this->widgets;
        $rs = $this->widgets->sortBy(function ($item){
            /** @var BackendDashBoardWidget $item */
            return (int) $item->getOrder();
        });
        return $rs;
    }

    public function __construct()
    {
        $this->widgets = collect();
        $this->after_register = [];
    }

    public function register(BackendDashBoardWidget $widget){
        if($this->widgets->has($widget->getId())){
            return;
        }
        $this->widgets->put($widget->getId(), $widget);
    }

    public function removeWidget($widget_id){
        if(!$this->widgets->has($widget_id)){
            return;
        }
        $this->widgets->forget($widget_id);
    }

    public function hook_after_register(\Closure $function){
        $this->after_register[] = $function;
    }

    public function do_after_register(){
        foreach ($this->after_register as $func){
            $func($this);
        }
    }
}
