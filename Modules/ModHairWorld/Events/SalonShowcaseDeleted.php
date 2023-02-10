<?php

namespace Modules\ModHairWorld\Events;

use Illuminate\Queue\SerializesModels;
use Modules\ModHairWorld\Entities\SalonShowcase;

class SalonShowcaseDeleted
{
    use SerializesModels;

    public $model;
    public function __construct(SalonShowcase $model)
    {
        $this->model = $model;
    }
}
