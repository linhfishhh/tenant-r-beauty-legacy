<?php

namespace Modules\ModFAQ\Entities;


use App\Classes\TaxonomyWithFieldInput;

class FAQCategory extends TaxonomyWithFieldInput
{

    public static function postType(): string
    {
        return FAQ::class;
    }

    public static function postTaxRel(): string
    {
        return FAQCategoryRel::class;
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
        return __('Danh mục hỏi đáp');
    }

    public static function taxSlug(): string
    {
        return 'danh-muc-hoi-dap';
    }

    public static function singular(): string
    {
        return __('Danh mục hỏi đáp');
    }

    public static function plural(): string
    {
        return __('Các danh mục hỏi đáp');
    }

    public static function menuIcon(): string
    {
        return 'icon-folder';
    }

    public static function menuOrder(): int
    {
        return 1;
    }

    public static function dbTableName(): string
    {
        return 'faq_categories';
    }
}