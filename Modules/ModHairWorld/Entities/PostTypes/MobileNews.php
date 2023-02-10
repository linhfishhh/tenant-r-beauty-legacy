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
 * Modules\ModHairWorld\Entities\PostTypes\News
 *
 * @property-read UploadedFile $cover
 * @property int $cover_id
 * @property string $description
 * @property string $content
 * @property int $content_type
 * @property string $content_1
 * @property string $content_2
 * @property string $button_title
 * @property string $button_link
 * @mixin \Eloquent
 */

class MobileNews extends PostTypeWithFieldInput
{
    public static function isPublic()
    {
        return false;
    }

    public static function taxonomies(): array
    {
        return [
            MobileNewsCategory::class => MobileNewsCategoryRel::class,
            //NewsTag::class => NewsTagRel::class
        ];
    }

    public static function getFileCatIDS()
    {
        return [
            static::getFileCatID().'_mobile_cover' => __('Tin tức mobile - Ảnh đại diện'),
            static::getFileCatID().'_mobile_content' => __('Tin tức mobile - Nội dung')
        ];
    }

    public static function dataTableQuery($query)
    {
        return $query->with(['cover']);
    }

    public static function dataTable($table)
    {
        $table->addColumn(
            'cover',
            function (MobileNews $news) {
                if ($news->cover) {
                    return $news->cover->getThumbnailUrl(config('app.default_thumbnail_name'));
                }
                return getNoThumbnailUrl();
            }
        );
        return $table;
    }

    public function cover()
    {
        return $this->hasOne(
            UploadedFile::class,
            'id',
            'cover_id'
        );
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
                    new FieldInputFile(
                        'cover_id',
                        $model ? $model->cover_id : '',
                        __('Ảnh đại diện'),
                        '',
                        false,
                        FieldInputFile::buildConfigs(
                            __('CHỌN ẢNH ĐẠI DIỆN'),
                            'Chọn ảnh đại diện',
                            [static::getFileCatID().'_mobile_cover' ],
                            ['image'],
                            $limit_owner?me()->id:0
                        )
                    ),
                    new FieldInputTextArea(
                        'description',
                        $model ? $model->description : '',
                        __('Mô tả ngắn'),
                        '',
                        true,
                        FieldInputTextArea::buildConfigs(
                            __('Nhập mô tả ngắn cho tin tức này'),
                            5
                        )
                    ),

                    new FieldInputTinyMCE(
                        'content',
                        $model ? $model->content : '',
                        __('Chi tiết tin tức'),
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
        return __('Tin tức mobile');
    }

    public static function menuIndexTitle(): string
    {
        return __('Danh sách tin tức mobile');
    }

    public static function typeSlug(): string
    {
        return 'tin-tuc-mobile';
    }

    public static function singular(): string
    {
        return 'Tin tức mobile';
    }

    public static function plural(): string
    {
        return 'Các tin tức';
    }

    public static function menuIcon(): string
    {
        return 'icon-magazine';
    }

    public static function menuIndexIcon(): string
    {
        return 'icon-list';
    }

    public static function menuOrder(): int
    {
        return 1;
    }

    static function dbTableName(): string
    {
        return 'mobile_news';
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