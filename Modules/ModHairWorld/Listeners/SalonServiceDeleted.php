<?php
namespace Modules\ModHairWorld\Listeners;

use Modules\ModHairWorld\Entities\SalonServiceSale;

class SalonServiceDeleted
{
    function handle(\Modules\ModHairWorld\Events\SalonServiceDeleted $event){
        $model = $event->model;
        \Log::info($model->id);
        SalonServiceSale::whereServiceId($model->id)->delete();
        $reviews = $model->reviews;
        $salon = $model->salon;

        foreach ($reviews as $review){
            $review->delete();
        }

        if($salon){
            $salon->cacheRating();
            $salon->cacheSale();
        }

        $model->logos()->delete();
        $model->options()->delete();

    }
}