<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/23/18
 * Time: 14:49
 */

namespace Modules\ModHairWorld\Entities;


/**
 * Modules\ModHairWorld\Entities\SalonReviewLikeSpamFilter
 *
 * @property int $id
 * @property int $user_id
 * @property int $target_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonReviewLikeSpamFilter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonReviewLikeSpamFilter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonReviewLikeSpamFilter whereTargetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonReviewLikeSpamFilter whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonReviewLikeSpamFilter whereUserId($value)
 * @mixin \Eloquent
 */
class SalonReviewLikeSpamFilter extends SalonLikeSpamFilter
{
    protected $table = 'salon_service_review_like_spam_filters';
}