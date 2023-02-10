<?php

namespace App;

use App\Events\Role\RoleCreated;
use App\Events\Role\RoleCreating;
use App\Events\Role\RoleDeleted;
use App\Events\Role\RoleDeleting;
use App\Events\Role\RoleRetrieved;
use App\Events\Role\RoleSaved;
use App\Events\Role\RoleSaving;
use App\Events\Role\RoleUpdated;
use App\Events\Role\RoleUpdating;
use App\Trails\PermissionHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

/**
 * App\Role
 *
 * @property int $id
 * @property string $title
 * @property string $desc
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property RolePermission[] $permissions
 * @property User[] $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 */
class Role extends Model
{
    use Notifiable, PermissionHelper;
    protected $dispatchesEvents = [
        'retrieved' => RoleRetrieved::class,
        'creating' => RoleCreating::class,
        'created' => RoleCreated::class,
        'updating' => RoleUpdating::class,
        'updated' => RoleUpdated::class,
        'saving' => RoleSaving::class,
        'saved' => RoleSaved::class,
        'deleting' => RoleDeleting::class,
        'deleted' => RoleDeleted::class,
    ];

    function permissions(){
        return $this->hasMany( RolePermission::class,'role_id', 'id');
    }

    public function getPermissions()
    {
        $rs = [];
        /** @var RolePermission[] $role_permissions */
        $role_permissions = $this->permissions;
        foreach ($role_permissions as $role_permission) {
            $rs[] = $role_permission->permission;
        }
        return $rs;
    }

    public function hasPermission($permission)
    {
        if ($this->isUltimateRole()) {
            return true;
        }
        $permissions = $this->getPermissions();
        return in_array($permission, $permissions);
    }

    public function hasAnyPermissions(array $permissions_to_check)
    {
        if ($this->isUltimateRole()) {
            return true;
        }
        $permissions = $this->getPermissions();
        return count(array_intersect($permissions_to_check, $permissions)) > 0;
    }

    public function hasAllPermissions(array $permissions_to_check)
    {
        if ($this->isUltimateRole()) {
            return true;
        }
        $permissions = $this->getPermissions();
        return !array_diff($permissions_to_check, $permissions);
    }

    static function getHtmlSelectData(){
        $rs = Role::get(['id', 'title']);
        $items = [];
        foreach ($rs as $r){
            if($r->id == config('app.ultimate_role_id')){
                if(!\Auth::user()){
                    continue;
                }
                if(!\Auth::user()->isUltimateUser()){
                    continue;
                }
            }
            $items[] = [
                'id' => $r->id.'',
                'text' => $r->title
            ];
        }
        return $items;
    }

    function users(){
        return $this->hasMany(User::class, 'role_id', 'id');
    }

    function isUltimateRole(){
        return $this->id == \Config::get('app.ultimate_role_id');
    }
}
