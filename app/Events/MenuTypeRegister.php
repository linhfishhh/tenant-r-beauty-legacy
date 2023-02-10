<?php

namespace App\Events;

use App\Classes\MenuType;
use App\Classes\MenuTypeGroup;
use App\Classes\Theme;
use App\Classes\WidgetType;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MenuTypeRegister
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var \Illuminate\Support\Collection|MenuType[] $types */
    protected $types;
    /** @var \Illuminate\Support\Collection|MenuTypeGroup[] $groups */
	protected $groups;
	private $after_register;

    public function __construct()
    {
        $this->types = collect();
        $this->groups = collect();
	    $this->after_register = [];
    }

	public function hook_after_register(\Closure $function){
		$this->after_register[] = $function;
	}

	public function do_after_register(){
		foreach ($this->after_register as $func){
			$func($this);
		}
	}

    /**
     * @param WidgetType $type
     */
    public function registerType($type){
        if($theme = Theme::getCurrentTheme()){
            $dis = $theme->get('disabled_widgets',[]);
            if(in_array($type->getID(), $dis)){
                return;
            }
        }
        if(!$this->types->has($type->getID())){
            $this->types->put($type->getID(), $type);
        }
    }

    public function registerTypeGroup($group){
    	/** @var MenuTypeGroup $group */
        if(!$this->groups->has($group->id)){
            $this->groups->put($group->id, $group);
        }
    }

    public function getGroups(){
    	return $this->groups;
    }

    public function getTypes(){
        return $this->types;
    }

    public function getInfo(){
        $rs = [];
        $this->groups->sort(function($group){
            return $group->order;
        });
        $this->types->sort(function($type){
        	/** @var MenuType $type */
            return $type->getOrder();
        });
        foreach ($this->groups as $group){
            $types = [];
            foreach ($this->types as $type){
                if($type->getGroupID() != $group->id){
                    continue;
                }
                $types[] = $type;
            }
            if(count($types)>0){
                $rs[] = [
                    'group' => $group,
                    'types' => $types
                ];
            }
        }
        return $rs;
    }

}
