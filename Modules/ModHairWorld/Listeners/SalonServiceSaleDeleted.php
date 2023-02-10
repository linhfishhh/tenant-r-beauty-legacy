<?php
namespace Modules\ModHairWorld\Listeners;


use Illuminate\Support\Collection;
use Modules\ModHairWorld\Entities\SalonServiceSale;

class SalonServiceSaleDeleted
{
    function handle(\Modules\ModHairWorld\Events\SalonServiceSaleDeleted $event){
        $model = $event->model;
        $service = $model->service;
        if($service){
            $service->cacheSale();
        }
    }
}