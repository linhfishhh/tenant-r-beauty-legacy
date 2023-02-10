<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 03-Apr-18
 * Time: 11:45
 */

namespace App\Classes;

use Closure;
use ReflectionClass;

abstract class FieldInput
{
    protected $configs = [];
    protected $field_name;
    protected $field_value;
    protected $field_label;
    protected $field_help;
    protected $field_required;
    protected $field_extra;
    protected $field_uid;
    protected $field_tree = [];

    public function setParent($field_name){
        $this->field_tree[] = $field_name;
    }

    /**
     * @return array
     */
    public function getFieldExtra()
    {
        return $this->field_extra;
    }

    /**
     * @return array
     */
    public function getConfigs(): array
    {
        return $this->configs;
    }

    /**
     * @return string
     */
    public function getFieldName(): string
    {
        return $this->field_name;
    }

    /**
     * @return mixed
     */
    public function processValue($raw_value)
    {
        return $raw_value;
    }

    /**
     * @return mixed
     */
    public function getFieldValue()
    {
        return $this->processValue($this->getRawFieldValue());
    }

    /**
     * @return mixed
     */
    public function getRawFieldValue()
    {
        return $this->field_value;
    }

    /**
     * @return string
     */
    public function getFieldLabel()
    {
        return $this->field_label;
    }

    /**
     * @return string
     */
    public function getFieldHelp()
    {
        return $this->field_help;
    }

    /**
     * @return bool
     */
    public function getFieldRequired()
    {
        return $this->field_required;
    }

    /**
     * FieldInput constructor.
     * @param string $field_name
     * @param $field_value
     * @param string $field_label
     * @param string $field_help
     * @param bool $field_required
     * @param array $configs
     * @param array $extra
     */
    public function __construct($field_name,
                                $field_value,
                                $field_label,
                                $field_help,
                                $field_required,
                                array $configs = [],
                                array $extra = []
                                )
    {
        $this->configs = $configs;
        $this->field_name = $field_name;
        $this->field_value = $field_value;
        $this->field_label = $field_label;
        $this->field_help = $field_help;
        $this->field_required = $field_required;
        $this->field_extra = $extra;
        $this->field_uid = uniqid('field_');
    }


    public function getUID(){
        return $this->field_uid;
    }

    abstract public function getViewName():string;

    public function getFieldNameForRules(){
        return $this->getFieldName();
    }

    public function getFieldNameForMessages(){
        return $this->getFieldName();
    }

    public function getRules(): array
    {
        $rs = [];
        if($this->getFieldRequired()){
            $rs[$this->getFieldNameForRules()][] = 'required';
        }
        $extra = $this->getFieldExtra();
        if(isset($extra['rules'])){
            $fn = $extra['rules'];
            if($fn instanceof Closure){
                $rs = $fn($rs, $this);
            }
        }
        return $rs;
    }

    public function getMessages(): array
    {
        $rs = [];
        if($this->getFieldRequired()){
            $rs[$this->getFieldNameForMessages().'.required'] = __('Thông tin này không được bỏ trống');
        }
        $extra = $this->getFieldExtra();
        if(isset($extra['messages'])){
            $fn = $extra['messages'];
            if($fn instanceof Closure){
                $rs = $fn($rs, $this);
            }
        }
        return $rs;
    }

    public function getViewData(){
        $rs = [];
        $rs['field'] = $this;
        return $rs;
    }

    public function getHtmlTemplateHandleID(){
        return $this->getHtmlTemplateID().'_handle';
    }

    public function getHtmlTemplateID(){
        $class = get_called_class();
        try {
            $reflect = new ReflectionClass( $class );
        } catch ( \ReflectionException $e ) {
        }
        $class_name = $reflect->getShortName();
        return 'tpl_field_'.mb_strtolower($class_name).'_'.$this->field_uid;
    }

    public function getJSRenderFunctionName(){
        $class = get_called_class();
        try {
            $reflect = new ReflectionClass( $class );
        } catch ( \ReflectionException $e ) {
        }
        $class_name = $reflect->getShortName();
        return 'field_render_'.mb_strtolower($class_name);
    }
}