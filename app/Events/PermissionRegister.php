<?php

namespace App\Events;

use App\Classes\Permission;
use App\Classes\PermissionGroup;
use Illuminate\Foundation\Events\Dispatchable;

class PermissionRegister
{
    use Dispatchable;

    /** @var \Illuminate\Support\Collection|PermissionGroup[] $groups */
    public $groups;
    /** @var \Illuminate\Support\Collection|Permission[] $permissions */
    public $permissions;
	private $after_register;
    
    
    /**
     * @param PermissionGroup[] $groups
     */
    public function registerGroups($groups = []){
        foreach ( $groups as $item ) {
            if($this->groups->has( $item->id)){
                continue;
            }
            $this->groups->put(
                $item->id,
                $item
            );
        }
    }
    
    /**
     * @param Permission[] $permissions
     */
    public function registerPermissions($permissions = []){
        foreach ( $permissions as $item ) {
            if($this->permissions->has( $item->id)){
                continue;
            }
            $this->permissions->put(
                $item->id,
                $item
            );
        }
    }

	public function hook_after_register(\Closure $function){
		$this->after_register[] = $function;
	}
    
    public function __construct()
    {
        $this->groups = collect();
        $this->permissions = collect();
	    $this->after_register = [];
    }

	public function do_after_register(){
		foreach ($this->after_register as $func){
			$func($this);
		}
	}
    
}
