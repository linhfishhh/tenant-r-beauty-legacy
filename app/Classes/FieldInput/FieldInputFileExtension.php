<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 05-Apr-18
 * Time: 11:17
 */

namespace App\Classes\FieldInput;


use App\Classes\FieldInput;
use App\Classes\FileTypeGroup;
use App\Role;

class FieldInputFileExtension extends FieldInput
{

    public function getViewName(): string
    {
        return 'backend.includes.field_inputs.file_extension';
    }

    public function __construct(string $field_name, $field_value, string $field_label, string $field_help, bool $field_required, array $configs = [], array $extra = [])
    {
        parent::__construct($field_name, $field_value, $field_label, $field_help, $field_required, $configs, $extra);
    }

    public static function buildConfigs($mutiple = false){
        return [
            'multiple' => $mutiple
        ];
    }

    public function getViewData()
    {
        $data = parent::getViewData();
        $extensions = [];
        $groups = getFileTypeGroups();
        /** @var FileTypeGroup $group */
        foreach ($groups as $group){
            $extensions[$group->getId()]['title'] = $group->getTitle();
            foreach ($group->getExtensions() as $extension){
                $extensions[$group->getId()]['items'][$extension] = $extension;
            }
        }
        $data['extensions'] = $extensions;
        return $data;
    }
}