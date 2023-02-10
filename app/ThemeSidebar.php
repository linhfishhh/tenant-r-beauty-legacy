<?php

namespace App;

use App\Events\ThemeSidebar\ThemeSidebarCreated;
use App\Events\ThemeSidebar\ThemeSidebarCreating;
use App\Events\ThemeSidebar\ThemeSidebarDeleted;
use App\Events\ThemeSidebar\ThemeSidebarDeleting;
use App\Events\ThemeSidebar\ThemeSidebarRetrieved;
use App\Events\ThemeSidebar\ThemeSidebarSaved;
use App\Events\ThemeSidebar\ThemeSidebarSaving;
use App\Events\ThemeSidebar\ThemeSidebarUpdated;
use App\Events\ThemeSidebar\ThemeSidebarUpdating;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * App\ThemeSidebar
 *
 * @property int $id
 * @property string $language
 * @property string $theme
 * @property string $location
 * @property int $sidebar_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ThemeSidebar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ThemeSidebar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ThemeSidebar whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ThemeSidebar whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ThemeSidebar whereSidebarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ThemeSidebar whereTheme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ThemeSidebar whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ThemeSidebar extends Model
{
    use Notifiable;

    protected $dispatchesEvents = [
        'retrieved' => ThemeSidebarRetrieved::class,
        'creating' => ThemeSidebarCreating::class,
        'created' => ThemeSidebarCreated::class,
        'updating' => ThemeSidebarUpdating::class,
        'updated' => ThemeSidebarUpdated::class,
        'saving' => ThemeSidebarSaving::class,
        'saved' => ThemeSidebarSaved::class,
        'deleting' => ThemeSidebarDeleting::class,
        'deleted' => ThemeSidebarDeleted::class,
    ];
}
