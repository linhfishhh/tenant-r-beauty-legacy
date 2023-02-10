<?php
namespace Modules\ModHairWorld\Listeners;

use Modules\ModHairWorld\Entities\SalonBrand;

class SalonBrandDeleted
{
    function handle(\Modules\ModHairWorld\Events\SalonBrandDeleted $event){
        /** @var SalonBrand $brand */
        $brand = $event->model;
        $logo = $brand->logo;
        if($logo){
            //$logo->delete();
        }
    }
}