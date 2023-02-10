<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 05-Apr-18
 * Time: 15:07
 */

namespace App\Classes\FieldInput;


use App\Classes\FieldInput;

class FieldInputFontAwesome extends FieldInput
{

    public function getViewName(): string
    {
        return 'backend.includes.field_inputs.icon_fa';
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
}