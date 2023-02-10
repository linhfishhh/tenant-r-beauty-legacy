<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 9/21/18
 * Time: 08:52
 */

namespace Modules\ModHairWorld\Listeners;


class NotificationSent
{
    public function handle(\Illuminate\Notifications\Events\NotificationSent $event)
    {
        \Log::info($event->notification->id);
    }
}