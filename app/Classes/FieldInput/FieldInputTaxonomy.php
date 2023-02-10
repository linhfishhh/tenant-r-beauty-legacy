<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 05-Apr-18
 * Time: 11:17
 */

namespace App\Classes\FieldInput;


use App\Classes\FieldInput;
use App\Classes\PostTaxRel;
use App\Classes\PostType;
use App\Classes\Taxonomy;

class FieldInputTaxonomy extends FieldInput
{

    public function getViewName(): string
    {
        return 'backend.includes.field_inputs.taxonomy';
    }

    public function __construct(string $field_name, $field_value, string $field_label, string $field_help, bool $field_required, array $configs = [], array $extra = [])
    {
        parent::__construct($field_name, $field_value, $field_label, $field_help, $field_required, $configs, $extra);
    }

    public static function buildConfigs($mutiple = false){
        return [
            'multiple' => $mutiple
        ];
    }

    public function getViewData()
    {
        $data = parent::getViewData();
        $types = getPostTypes();
        $taxonomies = [];
        /** @var PostType $type */
        foreach ($types as $type){
            $taxs = $type::getTaxonomies();
            $taxonomies[$type::getTypeSlug()]['title'] = $type::getSingular();
            /**
             * @var Taxonomy $tax
             * @var PostTaxRel $rel
             */
            foreach ($taxs as $tax=>$rel){
                $taxonomies[$type::getTypeSlug()]['items'][$tax] = $tax::getSingular();
            }
        }
        $data['taxonomies'] = $taxonomies;
        return $data;
    }
}