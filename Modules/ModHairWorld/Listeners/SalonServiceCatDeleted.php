<?php

namespace Modules\ModHairWorld\Listeners;


class SalonServiceCatDeleted
{
    function handle(\Modules\ModHairWorld\Events\SalonServiceCatDeleted $event){
        $cat = $event->model;
        $cat->services()->update([
            'category_id' => null
        ]);
    }
}