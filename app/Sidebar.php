<?php

namespace App;

use App\Events\Sidebar\SidebarCreated;
use App\Events\Sidebar\SidebarCreating;
use App\Events\Sidebar\SidebarDeleted;
use App\Events\Sidebar\SidebarDeleting;
use App\Events\Sidebar\SidebarRetrieved;
use App\Events\Sidebar\SidebarSaved;
use App\Events\Sidebar\SidebarSaving;
use App\Events\Sidebar\SidebarUpdated;
use App\Events\Sidebar\SidebarUpdating;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * App\Sidebar
 *
 * @property int $id
 * @property string $title
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Widget[] $widgets
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sidebar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sidebar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sidebar whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sidebar whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Sidebar extends Model
{
    use Notifiable;

    protected $dispatchesEvents = [
        'retrieved' => SidebarRetrieved::class,
        'creating' => SidebarCreating::class,
        'created' => SidebarCreated::class,
        'updating' => SidebarUpdating::class,
        'updated' => SidebarUpdated::class,
        'saving' => SidebarSaving::class,
        'saved' => SidebarSaved::class,
        'deleting' => SidebarDeleting::class,
        'deleted' => SidebarDeleted::class,
    ];

    function widgets(){
        return $this->hasMany(Widget::class,'sidebar_id', 'id')->orderBy('id');
    }
}
