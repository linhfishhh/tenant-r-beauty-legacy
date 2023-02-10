<?php

namespace Modules\ModHairWorld\Events;


use Illuminate\Queue\SerializesModels;
use Modules\ModHairWorld\Entities\SalonServiceOption;

class SalonServiceOptionSaved
{
    use SerializesModels;

    public $model;
    public function __construct(SalonServiceOption $model)
    {
        $this->model = $model;
    }
}