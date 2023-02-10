<?php

namespace Modules\ModHairWorld\Events;


use Illuminate\Queue\SerializesModels;
use Modules\ModHairWorld\Entities\SalonOrderChangeTimeRequest;

class ChangeTimeRequestCreated
{
    use SerializesModels;

    public $model;
    public function __construct(SalonOrderChangeTimeRequest $model)
    {
        $this->model = $model;
    }
}