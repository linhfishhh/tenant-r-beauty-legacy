<?php

namespace App\Classes\FieldInput;


use App\Classes\FieldInput;

class FieldInputFile extends FieldInput
{

    public function getViewName(): string
    {
        return 'backend.includes.field_inputs.file';
    }

    public function __construct(string $field_name, $field_value, string $field_label, string $field_help, bool $field_required, array $configs = [], array $extra = [])
    {
        parent::__construct($field_name, $field_value, $field_label, $field_help, $field_required, $configs, $extra);
    }

    public static function buildConfigs($title, $button_title='', $categories = [], $limit_groups = [], $owned_by = 0){
        $rs = [
            'title' => $title,
            'categories' => $categories,
            'limit' => $limit_groups,
            'button_title' => $button_title,
            'owned' => $owned_by
        ];
        return $rs;
    }
}