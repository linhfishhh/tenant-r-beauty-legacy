<?php

namespace Modules\ModHairWorld\Listeners;
use App\UploadedFile;

class SalonShowcaseItemDeleted
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
     * @param \Modules\ModHairWorld\Events\SalonShowcaseItemDeleted $event
     * @return void
     * @throws \Exception
     */
    public function handle(\Modules\ModHairWorld\Events\SalonShowcaseItemDeleted $event)
    {
        $item = $event->model;
        $image = $item->image;
        if($image){
            //$image->delete();
        }
    }
}
