<?php
namespace App\Classes\FieldInput;


use App\Classes\FieldInput;
use App\RevSlider;

class FieldInputSlider extends FieldInput
{

    public function getViewName(): string
    {
        return 'backend.includes.field_inputs.slider';
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
        $sliders = RevSlider::getSliders()->load('slides');
        $data['sliders'] = $sliders;
        return $data;
    }
}