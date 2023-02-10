<?php

namespace Modules\ModHairWorld\Listeners;

use App\Events\User\UserStoreRequestMessages;
use App\Events\User\UserUpdateRequestMessages;

class BackendUserStoreUpdateRequestMessages
{
    /**
     * @param UserStoreRequestMessages|UserUpdateRequestMessages $event
     */
    public function handle($event)
    {
        $event->messages['phone.required'] = __('Vui lòng nhập số điện thoại');
        $event->messages['phone.numeric'] = __('Số điện thoại không hợp lệ');
        $event->messages['phone.unique'] = __('Số điện thoại đã được dùng bởi người khác trong hệ thống');
    }
}
