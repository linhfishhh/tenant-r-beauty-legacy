<?php

namespace Modules\ModHairWorld\Events;


use Modules\ModHairWorld\Entities\SalonShowcaseLike;

class SalonShowcaseLikeDeleted
{
    public $model;
    public function __construct(SalonShowcaseLike $model)
    {
        $this->model = $model;
    }
}