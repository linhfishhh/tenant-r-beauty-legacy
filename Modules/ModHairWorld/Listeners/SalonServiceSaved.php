<?php
namespace Modules\ModHairWorld\Listeners;


class SalonServiceSaved
{
    function handle(\Modules\ModHairWorld\Events\SalonServiceSaved $event){
        $model = $event->model;
        $salon = $model->salon;
        if($salon){
            $salon->cacheSale();
        }
    }
}