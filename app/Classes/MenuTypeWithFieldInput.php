<?php
namespace App\Classes;


use Illuminate\Support\Collection;

abstract class MenuTypeWithFieldInput extends MenuType
{
    /** @var array|FieldInput[]|Collection */
    protected $field_inputs;

    abstract protected function get_id():string;
    abstract protected function get_title():string;
    abstract protected function get_order():int;
    abstract protected function get_group_id():string;
    abstract protected function get_icon():string;
    abstract protected function registerFieldInputs():array;

    public function __construct()
    {
        parent::__construct();
        $this->field_inputs = collect();
        /** @var FieldInput[] $fields */
        $fields = $this->registerFieldInputs();
        foreach ($fields as $field){
            $this->field_inputs->put($field->getFieldName(), $field);
        }
    }

    /**
     * @return FieldInput[]|array|Collection
     */
    public function getFieldInputs(){
        return $this->field_inputs;
    }


    public function getViewData()
    {
        $rs = parent::getViewData();
        $rs['menu_type'] = $this;
        return $rs;
    }

    function getHtmlView()
    {
        return 'backend.pages.menu.library.with_field';
    }

    function rules(array $rules): array
    {
        $rs = $rules;
        foreach ($this->getFieldInputs() as $fieldInput){
            $rs = array_merge($rs, $fieldInput->getRules());
        }
        return $rs;
    }

    function messages(array $messages): array
    {
        $rs = $messages;
        foreach ($this->getFieldInputs() as $fieldInput){
            $rs = array_merge($rs, $fieldInput->getMessages());
        }
        return $rs;
    }

    function getID(): string
    {
        return $this->get_id();
    }

    function getTitle(): string
    {
        return $this->get_title();
    }

    function getOrder(): int
    {
        return $this->get_order();
    }

    function getGroupID(): string
    {
        return $this->get_group_id();
    }

    function getIcon(): string
    {
        return $this->get_icon();
    }
}