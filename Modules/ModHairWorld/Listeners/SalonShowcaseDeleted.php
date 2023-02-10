<?php

namespace Modules\ModHairWorld\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\ModHairWorld\Entities\SalonShowcaseLike;

class SalonShowcaseDeleted
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param \Modules\ModHairWorld\Events\SalonShowcaseDeleted $event
     * @return void
     * @throws \Exception
     */
    public function handle(\Modules\ModHairWorld\Events\SalonShowcaseDeleted $event)
    {
        $items = $event->model->items;
        foreach ($items as $item){
            $item->delete();
        }
        SalonShowcaseLike::whereShowcaseId($event->model->id)->delete();
    }
}
