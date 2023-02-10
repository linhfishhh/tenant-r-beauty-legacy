<?php

namespace App\Classes\FieldInput;


use App\Classes\FieldInput;

class FieldInputRepeater extends FieldInput
{

    public function getViewName(): string
    {
        return 'backend.includes.field_inputs.repeater';
    }

    public function __construct(string $field_name, $field_value, string $field_label, string $field_help, bool $field_required, array $configs = [], array $extra = [])
    {
        $extra['addition_view'] = 'backend.includes.field_inputs.child_fields';
        parent::__construct($field_name, $field_value, $field_label, $field_help, $field_required, $configs, $extra);
    }


    /**
     * @param array|FieldInput[] $child_fields
     * @param string $add_btn_label
     * @param string $item_label
     * @return array
     */
    public static function buildConfigs(array $child_fields, $add_btn_label='', $item_label=''){
        $rs = [];
        $rs['add_btn_label'] = $add_btn_label;
        $rs['item_label'] = $item_label;
        $rs['child_fields'] = $child_fields;
        return $rs;
    }

    public function getConfigs(): array
    {
        $rs = parent::getConfigs();
        /** @var FieldInput $field */
        foreach ($rs['child_fields'] as $field){
            $rs['child_field_data'][] = [
                'template_id' => $field->getHtmlTemplateID(),
                'handle_id' => $field->getHtmlTemplateHandleID(),
                'render_id' => $field->getJSRenderFunctionName(),
                'label' => $field->getFieldLabel(),
                'value' => $field->getRawFieldValue(),
                'name' => $field->getFieldName(),
                'uid' => $field->getUID()
            ];
        }
        return $rs;
    }

    public function getChildren(){
        return parent::getConfigs()['child_fields'];
    }

    public function getRules(): array
    {
        $rs = parent::getRules();
        //$rs[$this->getFieldNameForRules()][] = 'array';
        $prefix = $this->getFieldNameForRules().'.*.';
        /** @var FieldInput $child */
        foreach ($this->getChildren() as $child){
            $rules = $child->getRules();
            foreach ($rules as $field_name=>$field_rules){
                $rs[$prefix.$field_name] = $field_rules;
            }
        }
        return $rs;
    }

    public function getMessages(): array
    {
        $rs = parent::getMessages();
        $rs[$this->getFieldNameForMessages().'.array'] = __('Giá trị không hợp lệ');
        $rs[$this->getFieldNameForMessages().'.required'] = __('Vui lòng thêm thông tin này');
        $prefix = $this->getFieldNameForMessages().'.*.';
        /** @var FieldInput $child */
        foreach ($this->getChildren() as $child){
            $messages = $child->getMessages();
            foreach ($messages as $field_name=>$field_messages){
                    $rs[$prefix.$field_name] = $field_messages;
            }
        }
        return $rs;
    }


}