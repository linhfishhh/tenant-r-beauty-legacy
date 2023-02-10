<?php

namespace Modules\ModHairWorld\Events;


use Illuminate\Queue\SerializesModels;
use Modules\ModHairWorld\Entities\SalonService;
use Modules\ModHairWorld\Entities\SalonServiceSale;

class SalonServiceSaleSaved
{
    use SerializesModels;

    public $model;
    public function __construct(SalonServiceSale $model)
    {
        $this->model = $model;
    }
}