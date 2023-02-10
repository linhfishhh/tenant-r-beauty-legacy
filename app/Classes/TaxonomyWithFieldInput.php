<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 22-Apr-18
 * Time: 10:02
 */

namespace App\Classes;


abstract class TaxonomyWithFieldInput extends Taxonomy
{
    /**
     * @param TaxonomyWithFieldInput $model
     * @return array|FieldGroup[]
     */
    public static function fieldGroups($model){
        return [];
    }

    public static function getEditView($view_data = [])
    {
        /** @var PostTypeWithFieldInput $class */
        $class = get_called_class();
        $view_data['wa_field_groups'] = $class::fieldGroups($view_data['model']);
        return view('backend.pages.taxonomy.edit_with_fields', $view_data);
    }

    public static function getStoreRules(array $rules)
    {
        /** @var TaxonomyWithFieldInput $class */
        $class = get_called_class();
        $rs = $rules;
        foreach ($class::fieldGroups(null) as $group){
            foreach ($group->getFields() as $field){
                $rs = array_merge($rs, $field->getRules());
            }
        }
        return $rs;
    }

    public static function getStoreMessages(array $messages)
    {
        /** @var TaxonomyWithFieldInput $class */
        $class = get_called_class();
        $rs = $messages;
        foreach ($class::fieldGroups(null) as $group){
            foreach ($group->getFields() as $field){
                $rs = array_merge($rs, $field->getMessages());
            }
        }
        return $rs;
    }

    public static function getUpdateRules(array $rules)
    {
        /** @var TaxonomyWithFieldInput $class */
        $class = get_called_class();
        $rs = $rules;
        foreach ($class::fieldGroups(null) as $group){
            foreach ($group->getFields() as $field){
                $rs = array_merge($rs, $field->getRules());
            }
        }
        return $rs;
    }

    public static function getUpdateMessages(array $messages)
    {
        /** @var TaxonomyWithFieldInput $class */
        $class = get_called_class();
        $rs = $messages;
        foreach ($class::fieldGroups(null) as $group){
            foreach ($group->getFields() as $field){
                $rs = array_merge($rs, $field->getMessages());
            }
        }
        return $rs;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param TaxonomyWithFieldInput $tax_before_save
     */
    public static function beforeStoreData(
        $request,
        $tax_before_save
    ) {
        /** @var TaxonomyWithFieldInput $class */
        $class = get_called_class();
        foreach ($class::fieldGroups(null) as $group){
            foreach ($group->getFields() as $field) {
                $extra = $field->getFieldExtra();
                if(isset($extra['term_save_unhandled']) && $extra['term_save_unhandled']){
                    continue;
                }
                $field_name = $field->getFieldName();
                $value = $request->get($field_name);
                if(is_array($value) || is_object($value)){
                    $value = json_encode($value);
                }
                $tax_before_save[$field_name] = $value;
            }
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param TaxonomyWithFieldInput $tax_before_save
     */
    public static function beforeUpdateData(
        $request,
        $tax_before_save
    ) {
        /** @var TaxonomyWithFieldInput $class */
        $class = get_called_class();
        foreach ($class::fieldGroups(null) as $group){
            foreach ($group->getFields() as $field) {
                $extra = $field->getFieldExtra();
                if(isset($extra['term_save_unhandled']) && $extra['term_save_unhandled']){
                    continue;
                }
                $field_name = $field->getFieldName();
                $value = $request->get($field_name);
                if(is_array($value) || is_object($value)){
                    $value = json_encode($value);
                }
                $tax_before_save[$field_name] = $value;
            }
        }
    }


    public abstract static function postType():string;
    public static function getPostType(): String
    {
        /** @var TaxonomyWithFieldInput $class */
        $class = get_called_class();
        return $class::postType();
    }

    abstract public static function postTaxRel():string;
    public static function getPostTaxRel(): String
    {
        /** @var TaxonomyWithFieldInput $class */
        $class = get_called_class();
        return $class::postTaxRel();
    }

    abstract public static function hierarchy(): bool;
    public static function isHierarchy(): bool
    {
        /** @var TaxonomyWithFieldInput $class */
        $class = get_called_class();
        return $class::hierarchy();
    }

    abstract public static function single(): bool;
    public static function isSingle(): bool
    {
        /** @var TaxonomyWithFieldInput $class */
        $class = get_called_class();
        return $class::single();
    }

    abstract public static function menuTitle(): string;
    public static function getMenuTitle(): string
    {
        /** @var TaxonomyWithFieldInput $class */
        $class = get_called_class();
        return $class::menuTitle();
    }

    abstract public static function taxSlug(): string;
    public static function getTaxSlug(): string
    {
        /** @var TaxonomyWithFieldInput $class */
        $class = get_called_class();
        return $class::taxSlug();
    }

    abstract public static function singular(): string;
    public static function getSingular(): string
    {
        /** @var TaxonomyWithFieldInput $class */
        $class = get_called_class();
        return $class::singular();
    }

    abstract public static function plural(): string;
    public static function getPlural(): string
    {
        /** @var TaxonomyWithFieldInput $class */
        $class = get_called_class();
        return $class::plural();
    }

    abstract public static function menuIcon(): string;
    public static function getMenuIcon(): string
    {
        /** @var TaxonomyWithFieldInput $class */
        $class = get_called_class();
        return $class::menuIcon();
    }

    abstract public static function menuOrder(): int;
    public static function getMenuOrder(): int
    {
        /** @var TaxonomyWithFieldInput $class */
        $class = get_called_class();
        return $class::menuOrder();
    }

    abstract public static function dbTableName(): string;
    public static function getDBTable(): string
    {
        /** @var TaxonomyWithFieldInput $class */
        $class = get_called_class();
        return $class::dbTableName();
    }
}