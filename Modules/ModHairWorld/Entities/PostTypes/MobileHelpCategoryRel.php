<?php

namespace Modules\ModHairWorld\Entities\PostTypes;

use App\Classes\PostTaxRel;

/**
 * App\MobileHelpCategoryRel
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @mixin \Eloquent
 */
class MobileHelpCategoryRel extends PostTaxRel
{
    //
    public static function getTaxonomy(): string
    {
        return MobileHelpCategory::class;
    }

    public static function getPostType(): string
    {
        return MobileHelp::class;
    }


    public static function getDBTable(): string
    {
        return 'mobile_help_category_rels';
    }
}
