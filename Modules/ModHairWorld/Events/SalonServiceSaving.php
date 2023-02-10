<?php

namespace Modules\ModHairWorld\Events;


use Illuminate\Queue\SerializesModels;
use Modules\ModHairWorld\Entities\SalonService;

class SalonServiceSaving
{
    use SerializesModels;

    public $model;
    public function __construct(SalonService $model)
    {
        $this->model = $model;
    }
}