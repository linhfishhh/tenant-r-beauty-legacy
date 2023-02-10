<?php

namespace Modules\ModHairWorld\Listeners;

use App\Events\User\UserStoreRequestRules;
use App\Events\User\UserUpdateRequestRules;
use Illuminate\Validation\Rule;

class BackendUserStoreUpdateRequestRules
{


    /**
     * @param UserStoreRequestRules|UserUpdateRequestRules $event
     */
    public function handle($event)
    {
        $user = null;
        if(\Route::currentRouteName() == 'backend.user.update'){
            $user = \Route::current()->parameter('user');
        }
        if(\Route::currentRouteName() == 'backend.profile.update'){
            $user = me();
        }

        $event->rules['phone'] = [
            'required',
            'numeric'
        ];



        if($user){
            $event->rules['phone'][] = Rule::unique('users','phone')->ignore($user->id);
            $event->rules['password'] = [
                'min:6',
                'confirmed',
                'nullable'
            ];
        }
        else{
            $event->rules['phone'][] = Rule::unique('users','phone');
            $event->rules['password'] = [
                'min:6',
                'confirmed'
            ];
        }
    }
}
