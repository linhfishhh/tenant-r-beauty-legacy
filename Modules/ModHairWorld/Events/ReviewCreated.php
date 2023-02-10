<?php

namespace Modules\ModHairWorld\Events;


use Illuminate\Queue\SerializesModels;
use Modules\ModHairWorld\Entities\SalonServiceReview;
use Modules\ModHairWorld\Notifications\CommonNotify;

class ReviewCreated
{
    use SerializesModels;

    public $model;
    public function __construct(SalonServiceReview $model)
    {
        $this->model = $model;
    }
}