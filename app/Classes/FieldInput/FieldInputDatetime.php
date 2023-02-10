<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 05-Apr-18
 * Time: 09:13
 */

namespace App\Classes\FieldInput;


use App\Classes\FieldInput;

class FieldInputDatetime extends FieldInput
{

    public function getViewName(): string
    {
        return 'backend.includes.field_inputs.datetime';
    }

    public function __construct(string $field_name, $field_value, string $field_label, string $field_help, bool $field_required, array $configs = [], array $extra = [])
    {
        parent::__construct($field_name, $field_value, $field_label, $field_help, $field_required, $configs, $extra);
    }

    public static function buildConfigs($min_date = '', $max_date = '', $drops = 'down'){
        $rs = [];
        $rs['minDate'] = $min_date;
        $rs['maxDate'] = $max_date;
        $rs['drops'] = $drops;
        return $rs;
    }
}