<?php

namespace Modules\ModHairWorld\Http\Requests;

use App\Classes\FormRequestExtended;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Modules\ModHairWorld\Entities\Salon;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MySalonRequest extends FormRequestExtended
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    public function authorize()
    {
        /** @var Salon $salon */
        $salon = $this->route()->parameter('salon');
        $rs = $salon->managers()->where('user_id', me()->id)->exists();
        if(!$rs){
            return new NotFoundHttpException();
        }
        return true;
    }
}
