<?php

namespace Modules\ModHairWorld\Events;


use Illuminate\Queue\SerializesModels;
use Modules\ModHairWorld\Entities\SalonServiceReview;

class ReviewDeleted
{
    use SerializesModels;

    public $model;
    public function __construct(SalonServiceReview $model)
    {
        $this->model = $model;
    }
}