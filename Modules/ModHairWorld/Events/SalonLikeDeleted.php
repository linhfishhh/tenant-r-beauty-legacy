<?php


namespace Modules\ModHairWorld\Events;


use Modules\ModHairWorld\Entities\SalonLike;

class SalonLikeDeleted
{
    public $model;
    public function __construct(SalonLike $model)
    {
        $this->model = $model;
    }
}