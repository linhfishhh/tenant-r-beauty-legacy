<?php

namespace App\Http\Requests;

use App\Classes\MenuType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Input;

class MenuOptionSave extends FormRequest
{
    private $menu_event;
    public function __construct(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->menu_event = app('menu_types');
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [];
        $rules['title'] = [
            'required'
        ];
        /** @var MenuType $type */
        $type = $this->menu_event->getTypes()->get(Input::get('menu_type'));
        if($type){
            $rules = $type->rules($rules);
        }
        return $rules;
    }

    public function messages()
    {
        $messages = [
            'title.required' => __('Vui lòng nhập tiêu đề liên kết'),
        ];
        /** @var MenuType $type */
        $type = $this->menu_event->getTypes()->get(Input::get('menu_type'));
        if($type){
            $messages = $type->messages($messages);
        }
        return $messages;
    }
}
