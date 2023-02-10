<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileUpload extends FormRequest
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
        $rules = [];
        $max_size = getFileUploadMaxSize();
        $saved_max_size = getSetting('file_upload_max_size', $max_size/(1024*1024));
        $saved_max_size = $saved_max_size *(1024*1024);
        if($saved_max_size > $max_size){
            $saved_max_size = $max_size;
        }
        $saved_max_size = $saved_max_size/1024.0;
        $rules['upload'] = ['file', 'max:'.$saved_max_size];
        return $rules;
    }
}
