<?php
namespace Modules\ModHairWorld\Listeners;

class SalonServiceOptionSaved
{
    function handle(\Modules\ModHairWorld\Events\SalonServiceOptionSaved $event){
        $model = $event->model;
        $service = $model->service;
        if($service){
            $service->cacheSale();
        }
    }
}