<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 05-Apr-18
 * Time: 10:22
 */

namespace App\Classes\FieldInput;


use App\Classes\FieldInput;

class FieldInputCode extends FieldInput
{

    public function getViewName(): string
    {
        return 'backend.includes.field_inputs.code';
    }

    public function __construct(string $field_name, $field_value, string $field_label, string $field_help, bool $field_required, array $configs = [], array $extra = [])
    {
        parent::__construct($field_name, $field_value, $field_label, $field_help, $field_required, $configs, $extra);
    }

    public static function buildConfigs($mode, $theme = 'ace/theme/monokai'){
        return [
            'mode'=> $mode,
            'theme' => $theme,
            'showPrintMargin' => 1,
            'wrap' => 1
        ];
    }
}