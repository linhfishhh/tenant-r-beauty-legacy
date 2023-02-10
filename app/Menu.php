<?php

namespace App;

use App\Events\Menu\MenuCreated;
use App\Events\Menu\MenuCreating;
use App\Events\Menu\MenuDeleted;
use App\Events\Menu\MenuDeleting;
use App\Events\Menu\MenuRetrieved;
use App\Events\Menu\MenuSaved;
use App\Events\Menu\MenuSaving;
use App\Events\Menu\MenuUpdated;
use App\Events\Menu\MenuUpdating;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * App\Menu
 *
 * @property int $id
 * @property string $title
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Menu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Menu whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Menu whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Menu whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read MenuItem[]|Collection $items
 */
class Menu extends Model
{
    use Notifiable;

    protected $dispatchesEvents = ['retrieved' => MenuRetrieved::class, 'creating' => MenuCreating::class, 'created' => MenuCreated::class, 'updating' => MenuUpdating::class, 'updated' => MenuUpdated::class, 'saving' => MenuSaving::class, 'saved' => MenuSaved::class, 'deleting' => MenuDeleting::class, 'deleted' => MenuDeleted::class,];

    function items()
    {
        $types = app('menu_types');
        $types = $types->getTypes();
        $type_ids = [];
        foreach ($types as $type) {
            $type_ids[] = $type->getID();
        }
        return $this->hasMany(
            MenuItem::class,
            'menu_id',
            'id'
        )->whereIn('type',$type_ids)
            ->orderBy('id');
    }

    function getVisibleItems()
    {
        $rs = [];
        foreach ($this->items as $item) {
            if($item->isVisible()){
                $rs[] = $item;
            }
        }
        return $rs;
    }
}
