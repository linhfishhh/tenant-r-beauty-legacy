<?php

namespace Modules\ModHairWorld\Entities;


/**
 * Modules\ModHairWorld\Entities\SalonShowcaseLikeSpamFilter
 *
 * @property int $id
 * @property int $user_id
 * @property int $target_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonShowcaseLikeSpamFilter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonShowcaseLikeSpamFilter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonShowcaseLikeSpamFilter whereTargetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonShowcaseLikeSpamFilter whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonShowcaseLikeSpamFilter whereUserId($value)
 * @mixin \Eloquent
 */
class SalonShowcaseLikeSpamFilter extends SalonLikeSpamFilter
{
    protected $table = 'salon_showcase_like_spam_filters';
}