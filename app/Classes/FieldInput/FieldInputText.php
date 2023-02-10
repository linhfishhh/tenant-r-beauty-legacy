<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 03-Apr-18
 * Time: 22:44
 */

namespace App\Classes\FieldInput;


use App\Classes\FieldInput;

class FieldInputText extends FieldInput
{
    public function getViewName(): string
    {
        return 'backend.includes.field_inputs.text';
    }

    public function __construct(string $field_name, $field_value, string $field_label, string $field_help, bool $field_required, array $configs = [], array $extra = [])
    {
        parent::__construct($field_name, $field_value, $field_label, $field_help, $field_required, $configs, $extra);
    }

    public static function buildConfigs($place_holder='', $prepend = '', $append = ''){
        $configs = [];
        $configs['prepend'] = $prepend;
        $configs['append'] = $append;
        $configs['placeholder'] = $place_holder;
        return $configs;
    }
}