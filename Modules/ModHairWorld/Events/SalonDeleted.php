<?php

namespace Modules\ModHairWorld\Events;

use Illuminate\Queue\SerializesModels;
use Modules\ModHairWorld\Entities\Salon;

class SalonDeleted
{
    use SerializesModels;

    public $model;
    public function __construct(Salon $model)
    {
        $this->model = $model;
    }

}
