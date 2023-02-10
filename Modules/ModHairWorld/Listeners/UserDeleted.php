<?php

namespace Modules\ModHairWorld\Listeners;


use Modules\ModContact\Entities\Contact;
use Modules\ModHairWorld\Entities\Api\PhoneVerify;
use Modules\ModHairWorld\Entities\SalonLike;
use Modules\ModHairWorld\Entities\SalonManager;
use Modules\ModHairWorld\Entities\SalonServiceReview;
use Modules\ModHairWorld\Entities\SalonServiceReviewLike;
use Modules\ModHairWorld\Entities\SalonShowcaseLike;

class UserDeleted
{

    function handle(\App\Events\User\UserDeleted $event)
    {
        $model = $event->user;
        Contact::getQuery()->where('user_id', $model->id)->delete();
        $model->notifications()->delete();
        \DB::table('oauth_access_tokens')->where('user_id', $model->id)->delete();
        \DB::table('password_resets')->where('email', $model->email)->delete();
        PhoneVerify::getQuery()->where('phone', $model->phone)->delete();
        SalonManager::whereUserId($model->id)->delete();
        SalonLike::whereUserId($model->id)->delete();
        SalonServiceReviewLike::whereUserId($model->id)->delete();
        SalonShowcaseLike::whereUserId($model->id)->delete();
        $reviews = SalonServiceReview::whereUserId($model->id)->get();
        foreach ($reviews as $review){
            $review->delete();
        }
    }
}