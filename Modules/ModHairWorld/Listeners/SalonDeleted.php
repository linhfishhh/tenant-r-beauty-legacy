<?php

namespace Modules\ModHairWorld\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\ModHairWorld\Entities\Salon;
use Modules\ModHairWorld\Entities\SalonBrand;
use Modules\ModHairWorld\Entities\SalonExtraInfo;
use Modules\ModHairWorld\Entities\SalonLike;
use Modules\ModHairWorld\Entities\SalonManager;
use Modules\ModHairWorld\Entities\SalonOpenTime;
use Modules\ModHairWorld\Entities\SalonService;
use Modules\ModHairWorld\Entities\SalonShowcase;
use Modules\ModHairWorld\Entities\SalonStylist;

class SalonDeleted
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

    public function handle(\Modules\ModHairWorld\Events\SalonDeleted $event)
    {
        SalonExtraInfo::whereSalonId($event->model->id)->delete();
        SalonManager::whereSalonId($event->model->id)->delete();
        SalonOpenTime::whereSalonId($event->model->id)->delete();
        SalonStylist::whereSalonId($event->model->id)->delete();
        SalonBrand::whereSalonId($event->model->id)->delete();
        SalonLike::whereSalonId($event->model->id)->delete();

        $showcases = SalonShowcase::whereSalonId($event->model->id)->get();
        foreach ($showcases as $showcase){
            $showcase->delete();
        }
        $services = SalonService::whereSalonId($event->model->id)->get();

        foreach ($services as $service){
            $service->delete();
        }
    }
}
