<?php
namespace Modules\ModHairWorld\Listeners;


class SalonServiceSaving
{
    function handle(\Modules\ModHairWorld\Events\SalonServiceSaving $event){
        $model = $event->model;
        $model->cacheSale(false);
    }
}