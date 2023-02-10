<?php

namespace App;

use App\Classes\MenuType;
use App\Events\MenuItem\MenuItemCreated;
use App\Events\MenuItem\MenuItemCreating;
use App\Events\MenuItem\MenuItemDeleted;
use App\Events\MenuItem\MenuItemDeleting;
use App\Events\MenuItem\MenuItemRetrieved;
use App\Events\MenuItem\MenuItemSaved;
use App\Events\MenuItem\MenuItemSaving;
use App\Events\MenuItem\MenuItemUpdated;
use App\Events\MenuItem\MenuItemUpdating;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * App\MenuItem
 *
 * @property int $id
 * @property int $menu_id
 * @property int $parent_id
 * @property string $title
 * @property string $icon
 * @property string $classes
 * @property string $attributes
 * @property string $target
 * @property string $options
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MenuItem whereAttributes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MenuItem whereClasses($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MenuItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MenuItem whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MenuItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MenuItem whereMenuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MenuItem whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MenuItem whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MenuItem whereTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MenuItem whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MenuItem whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $login_status
 * @property string $roles
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MenuItem whereLoginStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MenuItem whereRoles($value)
 * @property string $type
 * @method static \Illuminate\Database\Eloquent\Builder|\App\MenuItem whereType($value)
 */
class MenuItem extends Model
{
    protected $dispatchesEvents = ['retrieved' => MenuItemRetrieved::class, 'creating' => MenuItemCreating::class, 'created' => MenuItemCreated::class, 'updating' => MenuItemUpdating::class, 'updated' => MenuItemUpdated::class, 'saving' => MenuItemSaving::class, 'saved' => MenuItemSaved::class, 'deleting' => MenuItemDeleting::class, 'deleted' => MenuItemDeleted::class,];


    /**
     * @param MenuItem[] $items
     * @return bool
     */
    public function checkIfHasChildrenIn($items)
    {
        $rs = false;
        foreach ($items as $item) {
            if ($item->parent_id == $this->id) {
                $rs = true;
                break;
            }
        }
        return $rs;
    }

    /**
     * @param MenuItem[] $items_to_check
     * @param int $parent_id
     * @return bool
     */
    private function hasActiveChildrenIn_($items_to_check, $parent_id){
        $rs = false;
        foreach ($items_to_check as $item){
            if($item->parent_id == $parent_id){
                if($item->isActive()){
                    return true;
                }
                $temp =  $this->hasActiveChildrenIn_(
                    $items_to_check,
                    $item->id);
                if($temp){
                    return true;
                }
            }
        }
        return $rs;
    }

    /**
     * @param MenuItem[] $items_to_check
     * @return bool
     */
    public function hasActiveChildrenIn($items_to_check){
        return $this->hasActiveChildrenIn_(
            $items_to_check,
            $this->id);
    }

    /**
     * @return mixed|array|Collection
     */
    public function getOptions(){
        $options = $this->options;
        $options = json_decode($options, true);
        if(is_array($options)){
            $options = collect($options);
        }
        return $options;
    }

    /**
     * @return bool|MenuType
     */
    public function getMenuType()
    {
        $type = $this->type;
        $types = app('menu_types');
        $types->getTypes();
        $types = $types->getTypes();
        if (!$types) {
            return false;
        }
        if (!$types->has($type)) {
            return false;
        }
        return $types->get($type);
    }

    public function isActive()
    {
        $type = $this->getMenuType();
        if (!$type) {
            return false;
        }
        return $type->checkActive($this);
    }

    public function getURL(){
        $type = $this->getMenuType();
        if (!$type) {
            return false;
        }
        return $type->getURL($this);
    }

    public function getVisibleRoles()
    {
        return json_decode($this->roles, true);
    }

    public function isVisible()
    {
        $login_status = $this->login_status;
        if ($login_status == 'all') {
            return true;
        }
        if($login_status == 'guest'){
            return !me();
        }
        if($login_status == 'logged'){
            if(!me()){
                return false;
            }
            $roles = $this->getVisibleRoles();
            if(!is_array($roles)){
                return true;
            }
            if(count($roles) == 0){
                return true;
            }
            $my_role = me()->role_id;
            return in_array(
                $my_role,
                $roles);
        }
        return false;
    }
}
