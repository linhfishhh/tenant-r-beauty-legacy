<?php

namespace Modules\ModHairWorld\Events;

use Illuminate\Queue\SerializesModels;
use Modules\ModHairWorld\Entities\SalonShowcaseItem;

class SalonShowcaseItemDeleted
{
    use SerializesModels;

    public $model;
    public function __construct(SalonShowcaseItem $model)
    {
        $this->model = $model;
    }
}
