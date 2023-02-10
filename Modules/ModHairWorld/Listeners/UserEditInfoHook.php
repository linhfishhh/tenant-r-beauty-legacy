<?php

namespace Modules\ModHairWorld\Listeners;


use App\Events\AfterHtmlBlock;
use App\User;

class UserEditInfoHook
{
    /**
     * @param AfterHtmlBlock $event
     * @throws \Throwable
     */
    function handle(AfterHtmlBlock $event){
        if($event->block_id == 'content.after_info'){
            if(\Route::is('backend.profile.edit')){
                /** @var User $user */
                $user = me();
                echo view('modhairworld::backend.pages.user.edit', [
                    'model' => $user
                ])->render();
            }
            if(\Route::is('backend.user.edit')){
                /** @var User $user */
                $user = \Route::current()->parameter('user');
                echo view('modhairworld::backend.pages.user.edit', [
                    'model' => $user
                ])->render();
            }
            if(\Route::is('backend.user.create')){
                $user = null;
                echo view('modhairworld::backend.pages.user.edit', [
                    'model' => null
                ])->render();
            }
        }
    }
}