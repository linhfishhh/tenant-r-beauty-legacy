<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 09-Apr-18
 * Time: 16:15
 */

namespace App\Classes\FieldInput;


use App\Classes\FieldInput;

class FieldInputSelect extends FieldInput
{

    public function getViewName(): string
    {
        return 'backend.includes.field_inputs.select';
    }

    public function __construct(string $field_name, $field_value, string $field_label, string $field_help, bool $field_required, array $configs = [], array $extra = [])
    {
        parent::__construct($field_name, $field_value, $field_label, $field_help, $field_required, $configs, $extra);
    }

    public static function buildConfigs(array $list, bool $multiple){
        $configs = [];
        $configs['list'] = $list;
        $configs['multiple'] = $multiple;
        return $configs;
    }
}