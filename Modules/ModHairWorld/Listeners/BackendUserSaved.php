<?php

namespace Modules\ModHairWorld\Listeners;

use App\Events\User\UserSaved;

class BackendUserSaved
{
    public function handle(UserSaved $event)
    {
        if(\Route::currentRouteName() == 'backend.user.update'
            || \Route::currentRouteName() == 'backend.user.store'
            || \Route::currentRouteName() == 'backend.profile.update'
        ){
            if(\Route::currentRouteName() == 'backend.profile.update'){
                $model = me();
            }
            else{
                $model = $event->model;
            }
            $phone = request('phone');
        }
    }
}
