<?php
namespace Modules\ModHairWorld\Entities\FieldInput;


use App\Classes\FieldInput;

class FieldInputMapLocationLevelOne extends FieldInput
{

    public function getViewName(): string
    {
        return getThemeViewName('field_input.map_location_lv1');
    }

    public function __construct(string $field_name, $field_value, string $field_label, string $field_help, bool $field_required, array $configs = [], array $extra = [])
    {
        parent::__construct($field_name, $field_value, $field_label, $field_help, $field_required, $configs, $extra);
    }

    public static function buildConfigs($multiple = 0){
        $configs = [];
        $configs['multiple'] = $multiple;
        return $configs;
    }
}