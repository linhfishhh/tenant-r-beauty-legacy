<?php

namespace Modules\ModHairWorld\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Modules\ModHairWorld\Events\ReviewLikeCreated;
use Modules\ModHairWorld\Events\ReviewLikeDeleted;

/**
 * Modules\ModHairWorld\Entities\SalonServiceReviewLike
 *
 * @property int $id
 * @property int $review_id
 * @property int $user_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceReviewLike whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceReviewLike whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceReviewLike whereReviewId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceReviewLike whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceReviewLike whereUserId($value)
 * @property-read User $user
 * @property-read SalonServiceReview $review
 * @mixin \Eloquent
 */
class SalonServiceReviewLike extends Model
{
    protected $fillable = [];
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $dispatchesEvents = [
        'created' => ReviewLikeCreated::class,
        'deleted' => ReviewLikeDeleted::class
    ];

    function user(){
        return $this->belongsTo(User::class, 'user_id','id');
    }

    function review(){
        return $this->belongsTo(SalonServiceReview::class,'review_id', 'id');
    }
}
