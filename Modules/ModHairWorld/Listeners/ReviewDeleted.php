<?php

namespace Modules\ModHairWorld\Listeners;


use Illuminate\Support\Collection;

class ReviewDeleted
{
    function handle(\Modules\ModHairWorld\Events\ReviewDeleted $event){
        $review = $event->model;
        $review->ratings()->delete();
        $review->likes()->delete();
        $service = $review->service;
        if($service){
            $service->cacheRating(false);
            $salon = $service->salon;
            if($salon){
                $salon->cacheRating(false);
            }
        }
    }
}