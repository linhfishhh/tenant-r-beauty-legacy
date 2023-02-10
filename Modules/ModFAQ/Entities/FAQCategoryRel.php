<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 24-Apr-18
 * Time: 09:16
 */

namespace Modules\ModFAQ\Entities;


use App\Classes\PostTaxRel;

class FAQCategoryRel extends PostTaxRel
{

    public static function getTaxonomy(): string
    {
        return FAQCategory::class;
    }

    public static function getPostType(): string
    {
        return FAQ::class;
    }

    public static function getDBTable(): string
    {
        return 'faq_category_rels';
    }
}