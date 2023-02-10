<?php

namespace App\Http\Requests;

use App\Classes\Taxonomy;
use Illuminate\Foundation\Http\FormRequest;

class TaxonomyStoreUpdate extends FormRequest
{
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        /** @var Taxonomy $taxonomy */
        $taxonomy = $this->route()->parameter('taxonomy');
    	$rules = [];
    	$rules['title'] = [
    		'required'
	    ];
	    $rules['slug'] = [
		    'nullable'
	    ];
	    $rules['language'] = [
		    'required'
	    ];
	    $rules['parent_id'] = [
		    'nullable'
	    ];
	    if($this->route()->getName() == 'backend.taxonomy.store'){
            $rules = $taxonomy::getStoreRules($rules);
        }
        else{
            $rules = $taxonomy::getUpdateRules($rules);
        }

        return $rules;
    }

	public function messages()
	{
        /** @var Taxonomy $taxonomy */
        $taxonomy = $this->route()->parameter('taxonomy');
		$messages = [];
        if($this->route()->getName() == 'backend.taxonomy.update'){
            $messages = $taxonomy::getStoreMessages($messages);
        }
        else{
            $messages = $taxonomy::getUpdateMessages($messages);
        }
		return $messages;
	}
}
