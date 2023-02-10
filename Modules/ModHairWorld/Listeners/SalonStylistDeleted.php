<?php
namespace Modules\ModHairWorld\Listeners;

use Modules\ModHairWorld\Entities\SalonStylist;

class SalonStylistDeleted
{
    function handle(\Modules\ModHairWorld\Events\SalonStylistDeleted $event){
        /** @var SalonStylist $stylist */
        $stylist = $event->model;
        $avatar = $stylist->avatar;
        if($avatar){
            //$avatar->delete();
        }
    }
}