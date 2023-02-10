<?php

namespace App\Classes;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class FormRequestExtended extends FormRequest {
	protected $exception = null;
	protected function authorize(){
		return true;
	}
	protected function passesAuthorization()
	{
		$rs =  $this->authorize();
		if($rs instanceof Exception){
			$this->exception = $rs;
			return false;
		}
		else if(is_bool( $rs) && $rs == true){
			return true;
		}
		else{
			$this->exception = new AuthorizationException();
			return false;
		}
	}

	/**
	 *
	 * @return void
	 *
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 * @throws Exception
	 */
	protected function failedAuthorization()
	{
		if($this->exception instanceof Exception){
			throw $this->exception;
		}
		throw new AuthorizationException('This action is unauthorized.');
	}


}