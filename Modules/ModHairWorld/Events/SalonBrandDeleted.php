<?php
namespace Modules\ModHairWorld\Events;


use Illuminate\Queue\SerializesModels;
use Modules\ModHairWorld\Entities\SalonBrand;

class SalonBrandDeleted
{
    use SerializesModels;

    public $model;
    public function __construct(SalonBrand $model)
    {
        $this->model = $model;
    }
}