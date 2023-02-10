<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 03-Apr-18
 * Time: 23:31
 */

namespace App\Classes\BackendSettingPage;


use App\Classes\BackendSettingPageWithFieldInput;
use App\Classes\FieldGroup;
use App\Classes\FieldInput\FieldInputUser;

class BackendSettingPageUserRelations extends BackendSettingPageWithFieldInput
{

    protected function slug(): string
    {
        return 'user-relations';
    }

    protected function menuTitle(): string
    {
        return __('Người dùng và sỡ hữu');
    }

    protected function menuIcon(): string
    {
        return 'icon-users';
    }

    protected function menuOrder(): int
    {
        return 1;
    }

    protected function permissionTitle(): string
    {
        return __('Quản lý cấu hình sở hữu người dùng');
    }

    protected function permissionOrder(): int
    {
        return 1;
    }

    protected function pageTitle(): string
    {
        return __('Người dùng và sỡ hữu');
    }

    /**
     * @return array|FieldGroup[]
     */
    protected function fieldGroups(): array
    {
        $settings = [
            'default_user' => 1,
        ];

        $settings = getSettings($settings);

        $fields = [
            new FieldInputUser(
                'default_user',
                $settings['default_user'],
                __('Người dùng mặc dịnh'),
                __('Khi một người dùng bị xóa thì những sở hữu hiện tại của người dùng đó ví dụ như bài đăng sẽ được chuyển sang người dùng này'),
                true,FieldInputUser::buildConfigs())
        ];

        return [
            new FieldGroup(__('Cấu hình'), $fields)
        ];
    }
}