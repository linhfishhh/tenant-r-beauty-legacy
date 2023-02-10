<?php

namespace Modules\ModHairWorld\Events;


use Illuminate\Queue\SerializesModels;
use Modules\ModHairWorld\Entities\SalonServiceReviewLike;

class ReviewLikeDeleted
{
    use SerializesModels;

    public $model;
    public function __construct(SalonServiceReviewLike $model)
    {
        $this->model = $model;
    }
}