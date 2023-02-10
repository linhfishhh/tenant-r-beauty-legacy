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

class SalonSaving
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

    public function handle(\Modules\ModHairWorld\Events\SalonSaving $event)
    {
        $salon = $event->model;
        try{
            $address = $salon->getAddressLine();
            $salon->address_cache = $address;
        }
        catch (\Exception $exception){

        }
    }
}
