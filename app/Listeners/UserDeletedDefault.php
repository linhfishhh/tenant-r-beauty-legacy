<?php

namespace App\Listeners;


use App\Classes\PostType;
use App\Events\User\UserDeleted;

class UserDeletedDefault
{
    public function handle(UserDeleted $event)
    {
        $user = $event->user;
        $default_user_id = getSetting('default_user', 1);
        $post_types = getPostTypes();
        /** @var PostType $type */
        foreach ($post_types as $type){
            $type::whereUserId($user->id)->update([
                                                    'user_id' => $default_user_id
                                                  ]);
        }
    }
}