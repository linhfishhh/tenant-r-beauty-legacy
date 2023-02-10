<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 05-Apr-18
 * Time: 11:17
 */

namespace App\Classes\FieldInput;


use App\Classes\FieldInput;
use App\Classes\PostType;

class FieldInputPost extends FieldInput
{

    public function getViewName(): string
    {
        return 'backend.includes.field_inputs.post';
    }

    public function __construct(string $field_name, $field_value, string $field_label, string $field_help, bool $field_required, array $configs = [], array $extra = [])
    {
        parent::__construct($field_name, $field_value, $field_label, $field_help, $field_required, $configs, $extra);
    }

    public static function buildConfigs($multiple = false, $inline = false, $force_class = ''){
        $rs = [
            'multiple' => $multiple,
            'inline' => $inline,
            'force' => $force_class
        ];
        return $rs;
    }

    public function getViewData()
    {
        $rs =  parent::getViewData();
        $types = getPostTypes();
        $post_types = [];
        /** @var PostType $type */
        foreach ($types as $type){
            $post_types[] = $type;
        }
        $rs['post_types'] = $post_types;
        return $rs;
    }

    public function processValue($value)
    {
        $rs = (array) $value;
        /** @var PostType $post_type */
        $post_type = isset($rs['post_type'])?$rs['post_type']:'';
        $posts =  isset($rs['posts'])?$rs['posts']:[];
//        if(class_exists($post_type)){
//            if(is_array($posts)){
//                $posts = $post_type::whereIn('id',$posts)->get(['id', 'title', 'language']);
//            }
//            else{
//                $posts = $post_type::where('id', '=', $posts)->get(['id', 'title', 'language']);
//            }
//        }
        $rs['posts'] = $posts;
        $rs['post_type'] = $post_type;
        return $rs;
    }

    public function getFieldNameForRules()
    {
        return parent::getFieldNameForRules().'.posts';
    }

    public function getFieldNameForMessages()
    {
        return parent::getFieldNameForMessages().'.posts';
    }
}