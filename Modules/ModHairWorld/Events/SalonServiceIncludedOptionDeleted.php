<?php

namespace Modules\ModHairWorld\Events;


use Illuminate\Queue\SerializesModels;
use Modules\ModHairWorld\Entities\SalonServiceIncludedOption;

class SalonServiceIncludedOptionDeleted
{
    use SerializesModels;

    public $model;
    public function __construct(SalonServiceIncludedOption $model)
    {
        $this->model = $model;
    }
}