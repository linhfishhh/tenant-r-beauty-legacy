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
            static::getFileCatID().'_cover' => __('Tin t???c - ???nh ?????i di???n'),
            static::getFileCatID().'_content' => __('Tin t???c - N???i dung')
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
                __('Chi ti???t tin t???c'),
                [
                    new FieldInputFile(
                        'cover_id',
                        $model ? $model->cover_id : '',
                        __('???nh ?????i di???n'),
                        '',
                        false,
                        FieldInputFile::buildConfigs(
                            __('CH???N ???NH ?????I DI???N'),
                            'Ch???n ???nh ?????i di???n',
                            [static::getFileCatID().'_cover' ],
                            ['image'],
                            $limit_owner?me()->id:0
                        )
                    ),
                    new FieldInputTextArea(
                        'description',
                        $model ? $model->description : '',
                        __('M?? t??? ng???n'),
                        '',
                        true,
                        FieldInputTextArea::buildConfigs(
                            __('Nh???p m?? t??? ng???n cho tin t???c n??y'),
                            5
                        )
                    ),
                    new FieldInputSelect(
                        'content_type',
                        $model?$model->content_type:1,
                        'Layout n???i dung',
                        '',
                        true,
                        FieldInputSelect::buildConfigs([
                            '1' => 'Layout lo???i 1',
                            '2' => 'Layout lo???i 2',
                            '3' => 'Layout lo???i 3',
                        ],0)
                    ),
                    new FieldInputSelect(
                        'listable',
                        $model?$model->listable:1,
                        'Hi???n th??? trong danh s??ch tin',
                        '',
                        true,
                        FieldInputSelect::buildConfigs([
                            '1' => 'Hi???n th???',
                            '0' => 'Kh??ng hi???n th???',
                        ],0)
                    ),
                    new FieldInputText(
                        'button_title',
                        $model?$model->button_title:'',
                        'Ti??u ????? n??t link',
                        '',
                        false,
                        FieldInputText::buildConfigs('Nh???p ti??u ?????')
                    ),
                    new FieldInputText(
                        'button_link',
                        $model?$model->button_link:'#',
                        'Li??n k???t n??t link',
                        '',
                        false,
                        FieldInputText::buildConfigs('Nh???p li??n k???t')
                    ),
                    new FieldInputTinyMCE(
                        'content',
                        $model ? $model->content : '',
                        __('Chi ti???t tin t???c'),
                        '',
                        false,
                        $tiny_cfg
                    ),
                    new FieldInputRepeater(
                        'content_1',
                        $model?json_decode($model->content_1, true):null,
                        'Danh s??ch block',
                        '',
                        false,
                        FieldInputRepeater::buildConfigs([
                            new FieldInputText(
                                'title',
                                '',
                                'Ti??u ????? block',
                                '',
                                true,
                                FieldInputText::buildConfigs('Nh???p ti??u ????? kh???i')
                            ),
                            new FieldInputFile(
                                'cover_id',
                                0,
                                __('???nh ?????i di???n'),
                                '',
                                true,
                                FieldInputFile::buildConfigs(
                                    __('CH???N ???NH ?????I DI???N'),
                                    'Ch???n ???nh ?????i di???n',
                                    [static::getFileCatID().'_content' ],
                                    ['image'],
                                    $limit_owner?me()->id:0
                                )
                            ),
                            new FieldInputTinyMCE(
                                'content',
                                '',
                                __('N???i dung kh???i'),
                                '',
                                true,
                                $tiny_cfg
                            )
                        ])
                    ),
                    new FieldInputFileList(
                        'content_2',
                        $model?json_decode($model->content_2, true):[],
                        __('Danh s??ch ???nh'),
                        '',
                        false,
                        FieldInputFileList::buildConfigs(
                            __('CH???N ???NH'),
                            'Ch???n ???nh',
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
                        FieldInputTextArea::buildConfigs('Nh???p m???i keyword m???t d??ng',5)
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
        return __('Tin t???c');
    }

    public static function menuIndexTitle(): string
    {
        return __('Danh s??ch tin t???c');
    }

    public static function typeSlug(): string
    {
        return 'tin-tuc';
    }

    public static function singular(): string
    {
        return 'Tin t???c';
    }

    public static function plural(): string
    {
        return 'C??c tin t???c';
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