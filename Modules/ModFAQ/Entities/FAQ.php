<?php

namespace Modules\ModFAQ\Entities;

use App\Classes\FieldGroup;
use App\Classes\FieldInput\FieldInputTinyMCE;
use App\Classes\PostTypeWithFieldInput;

/**
 * Modules\ModFAQ\Entities\FAQ
 *
 * @property string $answer
 * @property int $need_notify
 * @property int $notified
 * @mixin \Eloquent
 */
class FAQ extends PostTypeWithFieldInput
{
    public static function dataTableFilter($query)
    {
        $answered = \Request::get('answered', -1);
        if($answered != -1){
            if($answered == '1'){
                $query->where('answer', '!=', '');
            }
            else{
                $query->where('answer', '=', '');
            }
        }
        return $query;
    }


    public static function dataTable($table)
    {
        $table->editColumn(
            'title',
            function (FAQ $faq){
                return str_limit($faq->title);
            });
        return $table;
    }

    public static function getIndexView($view_data = [])
    {
        return view('modfaq::backend.pages.index', $view_data);
    }

    public static function getFileCatIDS()
    {
        return [
            static::getFileCatID().'_content' => __('Hỏi đáp - Nội dung'),
        ];
    }
    /**
     * @param FAQ $model
     * @return \App\Classes\FieldGroup[]|array
     */
    public static function fieldGroups($model)
    {
        $tiny_cfg = FieldInputTinyMCE::buildConfigs(
            [
                'height' => 400,
            ],
            'post_type_faq.answer'
        );
        $tiny_cfg['wa_image_insert']['categories'] = [static::getFileCatID().'_content'];
        $tiny_cfg['wa_link_insert']['categories'] = [static::getFileCatID().'_content'];
        $tiny_cfg['wa_media_insert']['categories'] = [static::getFileCatID().'_content'];
        return [
            new FieldGroup(__('Chi tiết hỏi đáp'),
                [
                    new FieldInputTinyMCE(
                        'answer',
                        $model?$model->answer:'',
                        'Câu trả lời',
                        '',
                        true,
                        $tiny_cfg
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
        return __('Hỏi đáp thường gặp');
    }

    public static function menuIndexTitle(): string
    {
        return __('Danh sách hỏi đáp');
    }

    public static function typeSlug(): string
    {
        return 'hoi-dap';
    }

    public static function singular(): string
    {
        return __('Hỏi đáp');
    }

    public static function plural(): string
    {
        return __('Các hỏi hỏi');
    }

    public static function menuIcon(): string
    {
        return 'icon-question4';
    }

    public static function menuIndexIcon(): string
    {
        return 'icon-list-unordered';
    }

    public static function menuOrder(): int
    {
        return 99;
    }

    static function dbTableName(): string
    {
        return 'faqs';
    }

    public static function commentType(): string
    {
        return '';
    }

    public static function attachmentType(): string
    {
        return '';
    }

    public static function getDashboardWidgetOrder()
    {
        return 3;
    }

}