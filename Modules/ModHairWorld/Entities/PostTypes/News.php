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

class News extends PostTypeWithFieldInput
{

    public static function getEditView($view_data = [])
    {
        /** @var PostTypeWithFieldInput $class */
        $class = get_called_class();
        $view_data['wa_field_groups'] = $class::fieldGroups($view_data['model']);
        return view(getThemeViewName('backend.pages.news.edit'), $view_data);
    }

    public static function getThemeIndexView($data = [])
    {
        /** @var PostType $class */
        $class = get_called_class();
        if(\Request::ajax()){
            $view_name = $class::getThemeIndexViewName().'_ajax';
        }
        else{
            $view_name = $class::getThemeIndexViewName();
        }
        if (!$view_name) {
            return false;
        }
        return view(
            $view_name,
            $data
        );
    }

    public static function getThemePostView(
        $post,
        $data = []
    )
    {
        /** @var PostType $class */
        $class = get_called_class();
        if($post->content_type == 1){
            $view_name = getThemeViewName('post_type.tin-tuc.post_1');
        }
        else if($post->content_type == 2){
            $view_name = getThemeViewName('post_type.tin-tuc.post_2');
        }
        else{
            $view_name = getThemeViewName('post_type.tin-tuc.post_3');
        }
        return view(
            $view_name,
            $data
        );
    }


    public static function getFileCatIDS()
    {
        return [
            static::getFileCatID().'_cover' => __('Tin tức - Ảnh đại diện'),
            static::getFileCatID().'_content' => __('Tin tức - Nội dung')
        ];
    }

    public static function dataTableQuery($query)
    {
        return $query->with(['cover'])->select([
		"id",
		"slug",
		"title",
		"user_id",
		"published",
		"language",
		"cover_id",
		"updated_at",
		"deleted_at",
		"published_at",
		]);
    }

    public static function dataTable($table)
    {
        $table->addColumn(
            'cover',
            function (News $news) {
                if ($news->cover) {
                    return $news->cover->getThumbnailUrl(config('app.default_thumbnail_name'));
                }
                return getNoThumbnailUrl();
            }
        );
        return $table;
    }

    public static function getIndexView($view_data = [])
    {
        return view(
            getThemeViewName('backend.pages.news.index'),
            $view_data
        );
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
            ],
            'post_type_news_content'
        );
        $tiny_cfg['wa_image_insert']['categories'] = [static::getFileCatID().'_content' ];
        $tiny_cfg['wa_link_insert']['categories'] = [static::getFileCatID().'_content' ];
        $tiny_cfg['wa_media_insert']['categories'] = [static::getFileCatID().'_content'];
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
                            [static::getFileCatID().'_cover' ],
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
                    new FieldInputSelect(
                        'content_type',
                        $model?$model->content_type:1,
                        'Layout nội dung',
                        '',
                        true,
                        FieldInputSelect::buildConfigs([
                            '1' => 'Layout loại 1',
                            '2' => 'Layout loại 2',
                            '3' => 'Layout loại 3',
                        ],0)
                    ),
                    new FieldInputSelect(
                        'listable',
                        $model?$model->listable:1,
                        'Hiển thị trong danh sách tin',
                        '',
                        true,
                        FieldInputSelect::buildConfigs([
                            '1' => 'Hiển thị',
                            '0' => 'Không hiển thị',
                        ],0)
                    ),
                    new FieldInputText(
                        'button_title',
                        $model?$model->button_title:'',
                        'Tiêu đề nút link',
                        '',
                        false,
                        FieldInputText::buildConfigs('Nhập tiêu đề')
                    ),
                    new FieldInputText(
                        'button_link',
                        $model?$model->button_link:'#',
                        'Liên kết nút link',
                        '',
                        false,
                        FieldInputText::buildConfigs('Nhập liên kết')
                    ),
                    new FieldInputTinyMCE(
                        'content',
                        $model ? $model->content : '',
                        __('Chi tiết tin tức'),
                        '',
                        false,
                        $tiny_cfg
                    ),
                    new FieldInputRepeater(
                        'content_1',
                        $model?json_decode($model->content_1, true):null,
                        'Danh sách block',
                        '',
                        false,
                        FieldInputRepeater::buildConfigs([
                            new FieldInputText(
                                'title',
                                '',
                                'Tiêu đề block',
                                '',
                                true,
                                FieldInputText::buildConfigs('Nhập tiêu đề khối')
                            ),
                            new FieldInputFile(
                                'cover_id',
                                0,
                                __('Ảnh đại diện'),
                                '',
                                true,
                                FieldInputFile::buildConfigs(
                                    __('CHỌN ẢNH ĐẠI DIỆN'),
                                    'Chọn ảnh đại diện',
                                    [static::getFileCatID().'_content' ],
                                    ['image'],
                                    $limit_owner?me()->id:0
                                )
                            ),
                            new FieldInputTinyMCE(
                                'content',
                                '',
                                __('Nội dung khối'),
                                '',
                                true,
                                $tiny_cfg
                            )
                        ])
                    ),
                    new FieldInputFileList(
                        'content_2',
                        $model?json_decode($model->content_2, true):[],
                        __('Danh sách ảnh'),
                        '',
                        false,
                        FieldInputFileList::buildConfigs(
                            __('CHỌN ẢNH'),
                            'Chọn ảnh',
                            [static::getFileCatID().'_content' ],
                            ['image'],
                            $limit_owner?me()->id:0
                        )
                    ),
                ]
            ),
            new FieldGroup(
                __('SEO'),
                [
                    new FieldInputTextArea(
                        'meta_keywords',
                        $model?$model->meta_keywords:'',
                        'Meta keywords',
                        '',
                        false,
                        FieldInputTextArea::buildConfigs('Nhập mỗi keyword một dòng',5)
                    )
                ]
            )
        ];
    }

    public static function taxonomies(): array
    {
        return [];
    }

    public static function menuTitle(): string
    {
        return __('Tin tức');
    }

    public static function menuIndexTitle(): string
    {
        return __('Danh sách tin tức');
    }

    public static function typeSlug(): string
    {
        return 'tin-tuc';
    }

    public static function singular(): string
    {
        return 'Tin tức';
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
        return 0;
    }

    static function dbTableName(): string
    {
        return 'news';
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