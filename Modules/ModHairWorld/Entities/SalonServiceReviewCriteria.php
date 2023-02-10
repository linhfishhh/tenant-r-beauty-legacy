<?php

namespace Modules\ModHairWorld\Entities;

use App\Classes\FieldGroup;
use App\Classes\FieldInput\FieldInputTextArea;
use App\Classes\PostTypeWithFieldInput;

/**
 * Modules\ModHairWorld\Entities\SalonServiceReviewCriteria
 *
 * @property int $id
 * @property int $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceReviewCriteria whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceReviewCriteria whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceReviewCriteria whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceReviewCriteria whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SalonServiceReviewCriteria extends PostTypeWithFieldInput
{
    public static function isPublic()
    {
        return false;
    }

    public static function getFileCatIDS()
    {
        return [
        ];
    }

    public static function taxonomies(): array
    {
        return [];
    }

    public static function beforeStoreData($request,
                                           $post_before_save)
    {
        $post_before_save->name = $post_before_save->title;
    }

    public static function beforeUpdateData($request,
                                            $post_before_save)
    {
        $post_before_save->name = $post_before_save->title;
    }

    public static function fieldGroups($model)
    {
        /** @var SalonServiceReviewCriteria $model */
        return [
            new FieldGroup(
                __('Chi tiết tiêu chí'),
                [
                    new FieldInputTextArea(
                        'description',
                        $model ? $model->description : '',
                        __('Mô tả ngắn'),
                        '',
                        true,
                        FieldInputTextArea::buildConfigs(
                            __('Nhập mô tả ngắn cho tiêu chí'),
                            5
                        )
                    ),
                ]
            ),
        ];
    }

    public static function menuTitle(): string
    {
        return __('Tiêu chí đánh giá');
    }

    public static function menuIndexTitle(): string
    {
        return __('Danh sách tiêu chí');
    }

    public static function typeSlug(): string
    {
        return 'teu-chi-danh-gia';
    }

    public static function singular(): string
    {
        return 'Tiêu chí đánh giá';
    }

    public static function plural(): string
    {
        return 'Các tiêu chí';
    }

    public static function menuIcon(): string
    {
        return 'icon-stars';
    }

    public static function menuIndexIcon(): string
    {
        return 'icon-list';
    }

    public static function menuOrder(): int
    {
        return -2;
    }

    static function dbTableName(): string
    {
        return 'salon_service_review_criterias';
    }

    public static function commentType(): string
    {
        return '';
    }

    public static function attachmentType(): string
    {
        return '';
    }
}
