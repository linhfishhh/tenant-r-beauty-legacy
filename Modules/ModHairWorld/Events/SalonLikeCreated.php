<?php

namespace Modules\ModHairWorld\Events;


use Modules\ModHairWorld\Entities\SalonLike;

class SalonLikeCreated
{
    public $model;
    public function __construct(SalonLike $model)
    {
        $this->model = $model;
    }
}