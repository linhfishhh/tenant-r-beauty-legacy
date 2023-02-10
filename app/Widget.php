<?php

namespace App;

use App\Events\Widget\WidgetCreated;
use App\Events\Widget\WidgetCreating;
use App\Events\Widget\WidgetDeleted;
use App\Events\Widget\WidgetDeleting;
use App\Events\Widget\WidgetRetrieved;
use App\Events\Widget\WidgetSaved;
use App\Events\Widget\WidgetSaving;
use App\Events\Widget\WidgetUpdated;
use App\Events\Widget\WidgetUpdating;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * App\Widget
 *
 * @property int $id
 * @property int $sidebar_id
 * @property string $type
 * @property string $title
 * @property string $classes
 * @property string $attributes
 * @property string $login_status
 * @property string $roles
 * @property string $options
 * @property Widget[]|Collection $widgets
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Widget whereAttributes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Widget whereClasses($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Widget whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Widget whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Widget whereLoginStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Widget whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Widget whereRoles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Widget whereSidebarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Widget whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Widget whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Widget whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Widget extends Model
{
    use Notifiable;

    protected $dispatchesEvents = [
        'retrieved' => WidgetRetrieved::class,
        'creating' => WidgetCreating::class,
        'created' => WidgetCreated::class,
        'updating' => WidgetUpdating::class,
        'updated' => WidgetUpdated::class,
        'saving' => WidgetSaving::class,
        'saved' => WidgetSaved::class,
        'deleting' => WidgetDeleting::class,
        'deleted' => WidgetDeleted::class,
    ];

    function widgets(){
    	return $this->hasMany( Widget::class, 'sidebar_id', 'id')->orderBy( 'id');
    }
}
