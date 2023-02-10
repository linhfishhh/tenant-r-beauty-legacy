<?php

namespace Modules\ModHairWorld\Events;

use Illuminate\Queue\SerializesModels;
use Modules\ModHairWorld\Entities\Salon;

class SalonSaving
{
    use SerializesModels;

    public $model;
    public function __construct(Salon $model)
    {
        $this->model = $model;
    }

}
