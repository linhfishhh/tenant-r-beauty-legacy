<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 05-Apr-18
 * Time: 11:17
 */

namespace App\Classes\FieldInput;


use App\Classes\FieldInput;
use App\Menu;

class FieldInputWidgetTypeGroup extends FieldInput
{

    public function getViewName(): string
    {
        return 'backend.includes.field_inputs.widget_type_group';
    }

    public function __construct(string $field_name, $field_value, string $field_label, string $field_help, bool $field_required, array $configs = [], array $extra = [])
    {
        parent::__construct($field_name, $field_value, $field_label, $field_help, $field_required, $configs, $extra);
    }

    public static function buildConfigs($multiple = false){
        return [
            'multiple' => $multiple
        ];
    }

    public function getViewData()
    {
        $data = parent::getViewData();
        $data['groups'] = getWidgetTypeGroups();
        return $data;
    }
}