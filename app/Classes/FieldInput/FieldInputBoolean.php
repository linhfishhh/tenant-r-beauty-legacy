<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 04-Apr-18
 * Time: 16:59
 */

namespace App\Classes\FieldInput;


use App\Classes\FieldInput;

class FieldInputBoolean extends FieldInput
{

    public function getViewName(): string
    {
        return 'backend.includes.field_inputs.boolean';
    }

    public function __construct(string $field_name, $field_value, string $field_label, string $field_help, bool $field_required, array $configs = [], array $extra = [])
    {
        parent::__construct($field_name, $field_value, $field_label, $field_help, $field_required, $configs, $extra);
    }

    public static function buildConfigs($true_label, $false_label, $true_color_class = '', $false_color_class = ''){
        $configs = [];
        $configs['true_label'] = $true_label;
        $configs['false_label'] = $false_label;
        $configs['true_color_class'] = $true_color_class;
        $configs['false_color_class'] = $false_color_class;
        return $configs;
    }
}