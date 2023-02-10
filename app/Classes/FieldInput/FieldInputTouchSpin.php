<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 03-Apr-18
 * Time: 14:39
 */

namespace App\Classes\FieldInput;


use App\Classes\FieldInput;

class FieldInputTouchSpin extends FieldInput
{

    public function getViewName(): string
    {
        return 'backend.includes.field_inputs.touch_spin';
    }

    public function getRules(): array
    {
        $rules = parent::getRules();
        $rules[$this->getFieldName()][] = 'numeric';
        $configs = $this->getConfigs();
        if(isset($configs['min'])){
            $rules[$this->getFieldName()][] = 'min:'.$configs['min'];
        }
        if(isset($configs['max'])){
            $rules[$this->getFieldName()][] = 'max:'.$configs['max'];
        }
        return $rules;
    }

    public function __construct(string $field_name, $field_value, string $field_label, string $field_help, bool $field_required, array $configs = [], array $extra = [])
    {
        parent::__construct($field_name, $field_value, $field_label, $field_help, $field_required, $configs, $extra);
    }

}