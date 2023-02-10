<?php

namespace Modules\ModHairWorld\Entities\PostTypes;


use App\Classes\FieldGroup;
use App\Classes\FieldInput\FieldInputFile;
use App\Classes\FieldInput\FieldInputFileList;
use App\Classes\FieldInput\FieldInputRepeater;
use App\Classes\FieldInput\FieldInputSelect;
use App\Classes\FieldInput\FieldInputText;
use App\Classes\FieldInput\FieldInputTextArea;
use App\Classes\FieldInput\FieldInputTinyMCE;
use App\Classes\PostType;
use App\Classes\PostTypeWithFieldInput;
use App\UploadedFile;


/**
 * Modules\ModHairWorld\Entities\PostTypes\MobileHelp
 *
 * @property string $content
 * @mixin \Eloquent
 */

class MobileHelp extends PostTypeWithFieldInput
{
    public static function isPublic()
    {
        return false;
    }

    public static function taxonomies(): array
    {
        return [
            MobileHelpCategory::class => MobileHelpCategoryRel::class,
            //NewsTag::class => NewsTagRel::class
        ];
    }

    public static function getFileCatIDS()
    {
        return [
            static::getFileCatID().'_mobile_content' => __('Tài liệu quản lý')
        ];
    }

    public static function fieldGroups($model)
    {
        $tiny_cfg = FieldInputTinyMCE::buildConfigs(
            [
                'height' => 400,
            ]
        );
        $tiny_cfg['wa_image_insert']['categories'] = [static::getFileCatID().'_mobile_content' ];
        $tiny_cfg['wa_link_insert']['categories'] = [static::getFileCatID().'_mobile_content' ];
        $tiny_cfg['wa_media_insert']['categories'] = [static::getFileCatID().'_mobile_content'];
        $limit_owner = false;
        if(!me()->hasPermission(static::getManageGlobalPermissionID())){
            $limit_owner = true;
            $tiny_cfg['wa_image_insert']['owned'] = me()->id;
            $tiny_cfg['wa_link_insert']['owned'] = me()->id;
            $tiny_cfg['wa_media_insert']['owned'] = me()->id;
        }
        /** @var News $model */
        return [
            new FieldGroup(
                __('Chi tiết tin tức'),
                [

                    new FieldInputTinyMCE(
                        'content',
                        $model ? $model->content : '',
                        __('Nội dung chi tiết'),
                        '',
                        false,
                        $tiny_cfg
                    ),
                ]
            ),
        ];
    }

    public static function menuTitle(): string
    {
        return __('Tài liệu cho quản lý');
    }

    public static function menuIndexTitle(): string
    {
        return __('Danh sách tài liệu');
    }

    public static function typeSlug(): string
    {
        return 'tai-lieu-quan-ly';
    }

    public static function singular(): string
    {
        return 'Tài liệu cho quản lý';
    }

    public static function plural(): string
    {
        return 'Các tài liệu';
    }

    public static function menuIcon(): string
    {
        return 'icon-question4';
    }

    public static function menuIndexIcon(): string
    {
        return 'icon-list';
    }

    public static function menuOrder(): int
    {
        return 999;
    }

    static function dbTableName(): string
    {
        return 'mobile_helps';
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