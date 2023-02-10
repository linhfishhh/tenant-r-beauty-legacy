<?php

namespace Modules\ModHairWorld\Events;


use Illuminate\Queue\SerializesModels;
use Modules\ModHairWorld\Entities\SalonServiceCategory;

class SalonServiceCatDeleted
{
    use SerializesModels;

    public $model;
    public function __construct(SalonServiceCategory $model)
    {
        $this->model = $model;
    }
}