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
class MobileHelpCategory extends TaxonomyWithFieldInput
{


    public static function postType(): String
    {
        return MobileHelp::class;
    }

    public static function postTaxRel(): String
    {
        return MobileHelpCategoryRel::class;
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
        return __('Danh mục tài liệu');
    }

    public static function taxSlug(): string
    {
        return 'danh-muc-tai-lieu';
    }

    public static function singular(): string
    {
        return __('Danh mục tài liệu');
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
        return 'mobile_help_categories';
    }

    public static function isPublic()
    {
        return false;
    }
}
