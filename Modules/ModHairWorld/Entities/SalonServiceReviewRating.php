<?php
namespace Modules\ModHairWorld\Entities;


use Illuminate\Database\Eloquent\Model;


/**
 * Modules\ModHairWorld\Entities\SalonServiceReviewRating
 *
 * @property int $id
 * @property int $review_id
 * @property int $criteria_id
 * @property int $rating
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceReviewRating whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceReviewRating whereCriteriaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceReviewRating whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceReviewRating whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceReviewRating whereReviewId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceReviewRating whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SalonServiceReviewRating extends Model
{
    protected $table = 'salon_service_review_ratings';
    protected $fillable = [];
    protected $dates = [
        'created_at',
        'updated_at'
    ];
}