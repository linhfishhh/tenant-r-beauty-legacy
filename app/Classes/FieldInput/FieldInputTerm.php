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
use App\Classes\Taxonomy;

class FieldInputTerm extends FieldInput
{

    public function getViewName(): string
    {
        return 'backend.includes.field_inputs.term';
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
        $taxonomies = [];
        /** @var PostType $type */
        foreach ($types as $type){
            $taxs = $type::getTaxonomies();
            $taxonomies[$type::getTypeSlug()]['title'] = $type::getSingular();
            /**
             * @var Taxonomy $tax
             */
            $taxonomies[$type::getTypeSlug()]['items']= [];
            foreach ($taxs as $tax=>$rel){
                $taxonomies[$type::getTypeSlug()]['items'][$tax] = [
                    'singular' => $tax::getSingular(),
                    'single' => $tax::isSingle(),
                    'hierarchy' => $tax::isHierarchy(),
                    'ajax_url' => route('backend.taxonomy.select', ['post_type' => $type::getTypeSlug(), 'taxonomy' => $tax::getTaxSlug()]),
                    'tax_slug' => $tax::getTaxSlug(),
                    'post_type_slug' => $type::getTypeSlug()
                ];
            }
        }
        $rs['taxonomies'] = $taxonomies;
        return $rs;
    }

    public function processValue($value): array
    {
        $rs = (array) $value;
        /** @var Taxonomy $taxonomy */
        $taxonomy = isset($rs['taxonomy'])?$rs['taxonomy']:'';
        $terms =  isset($rs['terms'])?$rs['terms']:[];
        $rs['terms'] = $terms;
        $rs['taxonomy'] = $taxonomy;
        return $rs;
    }

    public function getFieldNameForRules()
    {
        return parent::getFieldNameForRules().'.terms';
    }

    public function getFieldNameForMessages()
    {
        return parent::getFieldNameForMessages().'.terms';
    }
}