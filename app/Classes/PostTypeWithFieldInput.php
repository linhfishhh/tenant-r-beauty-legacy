<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 14-Apr-18
 * Time: 07:44
 */

namespace App\Classes;


abstract class PostTypeWithFieldInput extends PostType
{

    /**
     * @param PostTypeWithFieldInput $model
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
        return view('backend.pages.post.edit_with_fields', $view_data);
    }


    public static function getStoreRules(array $rules)
    {
        /** @var PostTypeWithFieldInput $class */
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
        /** @var PostTypeWithFieldInput $class */
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
        /** @var PostTypeWithFieldInput $class */
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
        /** @var PostTypeWithFieldInput $class */
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
     * @param PostTypeWithFieldInput $post_before_save
     */
    public static function beforeStoreData(
        $request,
        $post_before_save
    ) {
        /** @var PostTypeWithFieldInput $class */
        $class = get_called_class();
        foreach ($class::fieldGroups(null) as $group){
            foreach ($group->getFields() as $field) {
                $extra = $field->getFieldExtra();
                if(isset($extra['post_save_unhandled']) && $extra['post_save_unhandled']){
                    continue;
                }
                $field_name = $field->getFieldName();
                $value = $request->get($field_name);
                if(is_array($value) || is_object($value)){
                    $value = json_encode($value);
                }
                $post_before_save[$field_name] = $value;
            }
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param PostTypeWithFieldInput $post_before_save
     */
    public static function beforeUpdateData(
        $request,
        $post_before_save
    ) {
        /** @var PostTypeWithFieldInput $class */
        $class = get_called_class();
        foreach ($class::fieldGroups(null) as $group){
            foreach ($group->getFields() as $field) {
                $extra = $field->getFieldExtra();
                if(isset($extra['post_save_unhandled']) && $extra['post_save_unhandled']){
                    continue;
                }
                $field_name = $field->getFieldName();
                $value = $request->get($field_name);
                if(is_array($value) || is_object($value)){
                    $value = json_encode($value);
                }
                $post_before_save[$field_name] = $value;
            }
        }
    }


    abstract public static function taxonomies(): array;
    public static function getTaxonomies(): array
    {
        /** @var PostTypeWithFieldInput $class */
        $class = get_called_class();
        return $class::taxonomies();
    }

    abstract public static function menuTitle(): string;
    public static function getMenuTitle(): string
    {
        /** @var PostTypeWithFieldInput $class */
        $class = get_called_class();
        return $class::menuTitle();
    }

    abstract public static function menuIndexTitle(): string;
    public static function getMenuIndexTitle(): string
    {
        /** @var PostTypeWithFieldInput $class */
        $class = get_called_class();
        return $class::menuIndexTitle();
    }

    abstract public static function typeSlug(): string;
    public static function getTypeSlug(): string
    {
        /** @var PostTypeWithFieldInput $class */
        $class = get_called_class();
        return $class::typeSlug();
    }

    abstract public static function singular(): string;
    public static function getSingular(): string
    {
        /** @var PostTypeWithFieldInput $class */
        $class = get_called_class();
        return $class::singular();
    }

    abstract public static function plural(): string;
    public static function getPlural(): string
    {
        /** @var PostTypeWithFieldInput $class */
        $class = get_called_class();
        return $class::plural();
    }

    abstract public static function menuIcon(): string;
    public static function getMenuIcon(): string
    {
        /** @var PostTypeWithFieldInput $class */
        $class = get_called_class();
        return $class::menuIcon();
    }

    abstract public static function menuIndexIcon(): string;
    public static function getMenuIndexIcon(): string
    {
        /** @var PostTypeWithFieldInput $class */
        $class = get_called_class();
        return $class::menuIndexIcon();
    }

    abstract public static function menuOrder():int;
    public static function getMenuOrder(): int
    {
        /** @var PostTypeWithFieldInput $class */
        $class = get_called_class();
        return $class::menuOrder();
    }

    abstract static function dbTableName():string;
    public static function getDBTable(): string
    {
        /** @var PostTypeWithFieldInput $class */
        $class = get_called_class();
        return $class::dbTableName();
    }


    abstract public static function commentType():string;
    public static function getCommentType(): string
    {
        /** @var PostTypeWithFieldInput $class */
        $class = get_called_class();
        return $class::commentType();
    }

    abstract public static function attachmentType():string;
    public static function getAttachmentType():string{
        /** @var PostTypeWithFieldInput $class */
        $class = get_called_class();
        return $class::attachmentType();
    }
}