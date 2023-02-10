<?php

namespace App;

use App\Events\ThemeMenu\ThemeMenuCreated;
use App\Events\ThemeMenu\ThemeMenuCreating;
use App\Events\ThemeMenu\ThemeMenuDeleted;
use App\Events\ThemeMenu\ThemeMenuDeleting;
use App\Events\ThemeMenu\ThemeMenuRetrieved;
use App\Events\ThemeMenu\ThemeMenuSaved;
use App\Events\ThemeMenu\ThemeMenuSaving;
use App\Events\ThemeMenu\ThemeMenuUpdated;
use App\Events\ThemeMenu\ThemeMenuUpdating;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * App\ThemeMenu
 *
 * @property int $id
 * @property string $language
 * @property string $theme
 * @property string $location
 * @property int $menu_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ThemeMenu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ThemeMenu whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ThemeMenu whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ThemeMenu whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ThemeMenu whereMenuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ThemeMenu whereTheme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ThemeMenu whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read Menu $menu
 * @property-read MenuItem[]|Collection $items
 */
class ThemeMenu extends Model
{
    use Notifiable;

    protected $dispatchesEvents = ['retrieved' => ThemeMenuRetrieved::class, 'creating' => ThemeMenuCreating::class, 'created' => ThemeMenuCreated::class, 'updating' => ThemeMenuUpdating::class, 'updated' => ThemeMenuUpdated::class, 'saving' => ThemeMenuSaving::class, 'saved' => ThemeMenuSaved::class, 'deleting' => ThemeMenuDeleting::class, 'deleted' => ThemeMenuDeleted::class,];

    function menu()
    {
        return $this->hasOne(
            Menu::class,
            'id',
            'menu_id'
        )
        ;
    }

    function items(){
        $types = app('menu_types');
        $types = $types->getTypes();
        $type_ids = [];
        foreach ($types as $type) {
            $type_ids[] = $type->getID();
        }
        return $this->hasMany(MenuItem::class,'menu_id', 'menu_id')->orderBy(
            'id',
            'asc')->whereIn('type',$type_ids);
    }
}
