<?php
namespace App\Classes\FieldInput;


use App\Classes\FieldInput;

class FieldInputMapLocation extends FieldInput
{

    public function getViewName(): string
    {
        return 'backend.includes.field_inputs.map_location';
    }
}