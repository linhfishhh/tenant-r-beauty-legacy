<?php
namespace Modules\ModHairWorld\Events;


use Illuminate\Queue\SerializesModels;
use Modules\ModHairWorld\Entities\SalonOrder;

class SalonOrderDeleted
{
    use SerializesModels;

    public $model;
    public function __construct(SalonOrder $model)
    {
        $this->model = $model;
    }
}