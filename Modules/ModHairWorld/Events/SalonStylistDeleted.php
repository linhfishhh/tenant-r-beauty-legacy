<?php
namespace Modules\ModHairWorld\Events;


use Illuminate\Queue\SerializesModels;
use Modules\ModHairWorld\Entities\SalonStylist;

class SalonStylistDeleted
{
    use SerializesModels;

    public $model;
    public function __construct(SalonStylist $model)
    {
        $this->model = $model;
    }
}