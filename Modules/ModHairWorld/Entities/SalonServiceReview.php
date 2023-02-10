<?php

namespace Modules\ModHairWorld\Entities;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\ModHairWorld\Events\ReviewCreated;
use Modules\ModHairWorld\Events\ReviewDeleted;

/**
 * Modules\ModHairWorld\Entities\SalonServiceReview
 *
 * @property int $id
 * @property int $service_id
 * @property int $user_id
 * @property int $order_id
 * @property string $title
 * @property string $content
 * @property bool $approved
 * @property int $badge_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceReview whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceReview whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceReview whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceReview whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceReview whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceReview whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceReview whereUserId($value)
 * @mixin \Eloquent
 * @property float $rating
 * @property-read SalonServiceReviewRating[]|Collection $criterias
 * @property-read User $user
 * @property-read SalonService $service
 * @property-read SalonServiceReviewRating[]|Collection $ratings
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceReview whereRating($value)
 * @property-read SalonLike|Builder $liked_by_me
 * @property-read SalonServiceReviewImage[]|Collection $images
 */
class SalonServiceReview extends Model
{
    protected $fillable = [];
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $dispatchesEvents = [
        'deleted' => ReviewDeleted::class,
        'created' => ReviewCreated::class
    ];

    function images(){
        return $this->hasMany(SalonServiceReviewImage::class,'review_id', 'id');
    }

    function criterias(){
        return $this->hasMany(SalonServiceReviewCriteria::class, 'review_id', 'id');
    }

    function getCriteriaRatings(){
        /** @var SalonServiceReviewCriteria[] $cris */
        $cris = app('review_criterias');
        $ids = [];
        $rs = [];
        foreach ($cris as $item){
            $ids[] = $item->id;
            $rs[$item->id] = 0;
        }
        /** @var SalonServiceReviewRating[]|Collection $ratings */
        $ratings = SalonServiceReviewRating::whereReviewId($this->id)->whereIn('criteria_id',$ids)->get(['id', 'rating', 'criteria_id']);
        foreach ($ratings as $rating){
            $rs[$rating->criteria_id] = $rating->rating;
        }
        return $rs;
    }

    function cacheRating(){
        if(!$this->service){
            return;
        }
        /** @var SalonServiceReviewCriteria[] $cris */
        $cris = app('review_criterias');
        $ids = [];
        foreach ($cris as $item){
            $ids[] = $item->id;
        }
        /** @var SalonServiceReviewRating[]|Collection $ratings */
        $ratings = SalonServiceReviewRating::whereReviewId($this->id)->whereIn('criteria_id',$ids)->get(['id', 'rating']);
        $rs = 0;
        if($ratings->count()){
            $rs = $ratings->average(function ($item){
                return $item->rating;
            });
        }
        $this->rating = $rs;
        $this->save();
        return $rs;
    }

    function likes(){
        return $this->hasMany(SalonServiceReviewLike::class, 'review_id', 'id');
    }

    function likedBy($user_id){
        return $this->likes()->where('user_id', $user_id)->count()>0;
    }

    function liked_by_me(){
        return $this->hasOne(SalonServiceReviewLike::class, 'review_id', 'id')
            ->where('user_id', me()?me()->id:-1)
            ;
    }

    function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    function service(){
        return $this->hasOne(SalonService::class,'id', 'service_id');
    }

    function ratings(){
        return $this->hasMany(SalonServiceReviewRating::class, 'review_id', 'id');
    }
}
