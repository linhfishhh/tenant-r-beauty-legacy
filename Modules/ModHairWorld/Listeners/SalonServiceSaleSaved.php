<?php
namespace Modules\ModHairWorld\Listeners;


use Illuminate\Support\Collection;
use Modules\ModHairWorld\Entities\SalonServiceSale;

class SalonServiceSaleSaved
{
    function handle(\Modules\ModHairWorld\Events\SalonServiceSaleSaved $event){
        $model = $event->model;
        $service = $model->service;
        if($service){
            $service->cacheSale();
        }
    }
}