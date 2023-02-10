<?php

namespace App\Http\Requests;

use App\Classes\BackendSettingPage;
use Illuminate\Foundation\Http\FormRequest;

class BackendSettingPageSave extends FormRequest
{
    public function authorize()
    {
        if($this->ajax()){
            return true;
        }
        return false;
    }

    public function rules()
    {
        /** @var BackendSettingPage $page */
        $page = $this->route()->parameter('page');
        $rules = $page->getRules();
        return $rules;
    }

    public function messages()
    {
        /** @var BackendSettingPage $page */
        $page = $this->route()->parameter('page');
        $messages = $page->getMessages();
        return $messages;
    }
}
