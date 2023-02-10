<?php
namespace Modules\ModHairWorld\Listeners;

class SalonServiceOptionDeleted
{
    function handle(\Modules\ModHairWorld\Events\SalonServiceOptionDeleted $event){
        $model = $event->model;
        $service = $model->service;
        if($service){
            $service->cacheSale();
        }
    }
}