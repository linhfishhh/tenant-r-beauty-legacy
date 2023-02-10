<?php

namespace Modules\ModHairWorld\Entities\PostTypes;

use App\Classes\PostTaxRel;

/**
 * App\NewsCategoryRel
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @mixin \Eloquent
 */
class MobileNewsCategoryRel extends PostTaxRel
{
    //
    public static function getTaxonomy(): string
    {
        return MobileNewsCategory::class;
    }

    public static function getPostType(): string
    {
        return MobileNews::class;
    }


    public static function getDBTable(): string
    {
        return 'mobile_news_category_rels';
    }
}
