<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 03-Apr-18
 * Time: 15:11
 */

namespace App\Classes;


use Illuminate\View\View;

abstract class BackendSettingPageWithFieldInput extends BackendSettingPage
{
    abstract protected function slug():string;
    abstract protected function menuTitle():string;
    abstract protected function menuIcon():string;
    abstract protected function menuOrder():int;
    abstract protected function permissionTitle():string;
    abstract protected function permissionOrder():int;
    abstract protected function pageTitle():string;

    /**
     * @return array|FieldGroup[]
     */
    abstract protected function fieldGroups():array;

    public function getSlug(): string
    {
        return $this->slug();
    }

    public function getMenuTitle(): string
    {
        return $this->menuTitle();
    }

    public function getMenuIcon(): string
    {
        return $this->menuIcon();
    }

    public function getMenuOrder(): int
    {
        return $this->menuOrder();
    }

    public function getPermissionTitle(): string
    {
        return $this->permissionTitle();
    }

    public function getPermissionOrder(): int
    {
        return $this->permissionOrder();
    }

    public function getPageTitle(): string
    {
        return $this->pageTitle();
    }

    public function getView($settings = []): View
    {
        $field_groups = $this->fieldGroups();
        return view('backend.pages.settings.with_fields', ['groups' => $field_groups, 'page'=>$this]);
    }

    public function getRules()
    {
        $rs = [];
        $groups = $this->fieldGroups();
        foreach ($groups as $group){
            /** @var FieldGroup $group */
            $fields = $group->getFields();
            foreach ($fields as $field){
                /** @var FieldInput $field */
                $rules = $field->getRules();
                $rs = array_merge($rs, $rules);
            }
        }
        return $rs;
    }

    public function loadSettings()
    {
        $groups = $this->fieldGroups();
        foreach ($groups as $group){
            $fields = $group->getFields();
            foreach ($fields as $field){
                $name = $field->getFieldName();
                $extra = $field->getFieldExtra();
                $autoload = isset($extra['autoload'])?$extra['autoload']:0;
                $default = null;
                $this->settings[$field->getFieldName()] = [
                    $name,
                    $autoload,
                    $default
                ];
            }
        }
    }

    public function saveSettings($input_fields = [])
    {
        $this->loadSettings();
        parent::saveSettings($input_fields);
    }

    public function getSettings()
    {
        $this->loadSettings();
        $rs =  parent::getSettings();
        return $rs;
    }

    public function getMessages()
    {
        $rs = [];
        $groups = $this->fieldGroups();
        foreach ($groups as $group){
            /** @var FieldGroup $group */
            $fields = $group->getFields();
            foreach ($fields as $field){
                /** @var FieldInput $field */
                $messages = $field->getMessages();
                $rs = array_merge($rs, $messages);
            }
        }
        return $rs;
    }
}