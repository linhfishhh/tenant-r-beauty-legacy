<?php

namespace Modules\ModHairWorld\Entities\PostTypes;

use App\Classes\TaxonomyWithFieldInput;

/**
 * App\NewsCategory
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property string $icon
 * @mixin \Eloquent
 */
class MobileNewsCategory extends TaxonomyWithFieldInput
{


    public static function postType(): String
    {
        return MobileNews::class;
    }

    public static function postTaxRel(): String
    {
        return MobileNewsCategoryRel::class;
    }

    public static function hierarchy(): bool
    {
        return false;
    }

    public static function single(): bool
    {
        return true;
    }

    public static function menuTitle(): string
    {
        return __('Danh mục tin tức');
    }

    public static function taxSlug(): string
    {
        return 'danh-muc';
    }

    public static function singular(): string
    {
        return __('Danh mục');
    }

    public static function plural(): string
    {
        return __('Các danh mục');
    }

    public static function menuIcon(): string
    {
        return 'icon-folder';
    }

    public static function menuOrder(): int
    {
        return 0;
    }

    public static function dbTableName(): string
    {
        return 'mobile_news_categories';
    }

    public static function isPublic()
    {
        return false;
    }
}
