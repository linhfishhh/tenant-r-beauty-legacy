<?php
namespace App\Trails;
use App\Events\PermissionRegister;
use App\Role;
use App\User;

trait PermissionHelper{
    function getPermissionInfo(){
        /** @var User|Role  $this */
        $rs = [];
        $event = app('permissions');
        foreach ($event->groups as $group){
            $add = [];
            foreach ($event->permissions as $permission){
                if($permission->group != $group->id){
                    continue;
                }
                if($this->hasPermission($permission->id)){
                    $add[] = $permission;
                }
            }
            if(count($add)>0){
                $rs[] = [
                    'group' => $group,
                    'permissions' => $add
                ];
            }
        }
        return $rs;
    }
}