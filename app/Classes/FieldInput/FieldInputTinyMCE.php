<?php
namespace App\Classes\FieldInput;


use App\Classes\FieldInput;

class FieldInputTinyMCE extends FieldInput
{

    public function getViewName(): string
    {
        return 'backend.includes.field_inputs.tinymce';
    }

    public function __construct(string $field_name, $field_value, string $field_label, string $field_help, bool $field_required, array $configs = [], array $extra = [])
    {
        if(isset($configs['script_hook_id']) && $configs['script_hook_id']){
            $scripts = app('tinymce_scripts')->getScriptFor($configs['script_hook_id']);
            $classes = app('tinymce_scripts')->getBodyClassFor($configs['script_hook_id']);
            $classes = implode(
                ' ',
                $classes);
            $configs['content_css'] = $scripts;
            $configs['body_class'] = $classes;
        }
        parent::__construct($field_name, $field_value, $field_label, $field_help, $field_required, $configs, $extra);
    }


    public static function buildConfigs($js_configs = [], $script_hook_id = ''){
        $rs = FieldInputTinyMCE::defaultConfigs();
        $rs['script_hook_id'] = $script_hook_id;
        $rs = array_merge($rs, $js_configs);
        return $rs;
    }

    public static function defaultConfigs(){
        $rs =  [
                'height' => 200,
                'menubar' => false,
                'toolbar1' => 'formatselect fontsizeselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat ',
                'toolbar2' => 'wainsert | searchreplace autolink directionality advcode visualblocks visualchars fullscreen image link media table blockquote charmap hr nonbreaking anchor insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern | code',
                'plugins' => 'searchreplace directionality advcode visualblocks visualchars fullscreen image link media table charmap hr nonbreaking anchor insertdatetime advlist lists textcolor contextmenu colorpicker textpattern',
                'language' => app()->getLocale(),
                'image_advtab' => true,
                'image_class_list' =>[
                    [
                        'title' => __('Canh trái'),
                        'value' => 'align-left'
                    ],
                    [
                        'title' => __('Canh phải'),
                        'value' => 'align-right'
                    ],
                    [
                        'title' => __('Canh giữa'),
                        'value' => 'align-center'
                    ]
                ],
                'wa_image_insert' => [
                    'title' => __('Chọn ảnh thêm vào'),
                    'limit' => ['image'],
                    'select' => -1
                ],
                'wa_link_insert' => [
                    'title' => __('Chọn file thêm link vào'),
                    'limit' => [],
                    'select' => -1
                ],
                'wa_media_insert' => [
                    'title' => __('Chọn file đa phương tiện thêm vào'),
                    'limit' => [],
                    'select' => -1
                ],
        ];
        return $rs;
    }
}