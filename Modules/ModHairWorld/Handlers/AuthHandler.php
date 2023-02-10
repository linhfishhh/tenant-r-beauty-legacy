<?php
namespace Modules\ModHairWorld\Handlers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Modules\ModHairWorld\Entities\UserExtra;
use Modules\ModHairWorld\Entities\UserAddress;

trait AuthHandler {
    public function loginStg($user, $uuid, $profileType, $retry = true)
    {
        $user = $user ? $user : me();
        $body = '{
            "tenantId":"' . env('API_TENANT_ID') . '",
            "password":"' . env('API_PASS') . '",
            "grantType":"password",
            "profileType": "' . $profileType . '",
            "externalId":"' . $user->id . '",
            "uuid":"' . $uuid. '"
            }';
        try {
            $requestClient = new RequestClient();
            $client = $requestClient->create(false);
            $response = $client->request('POST', '/uaa/user/login', ['body' => $body]);
            $apiToken = json_decode($response->getBody())->data->access_token;
            return $apiToken;
        } catch (\Exception $ex) {
            \Log::info('SYNC process stoped by error: ' . $ex->getMessage() . '; code: ' . $ex->getCode() .
                '; line: ' . $ex->getLine() . '; in file: ' . $ex->getFile());
            if (isset($user) && $user) {
                \Log::info('post data:');
                \Log::info(json_encode($user));
                if ($retry) {
                    if ($this->syncData($user->password, $user)) {
                        return $this->loginStg($user, $uuid, $profileType, false);
                    }
                }
            }
        }
        return null;
    }
    
    function syncData($password, $account = null) {
        $status = env('SYNC_FLAG') ? env('SYNC_FLAG') : false;
        if ($status) {
            try {
                $account = $account ? $account : me();
                $info = UserExtra::fromUserID($account->id);
                $gender = null;
                $password = $password ? $password : $account->password;
                switch ($account->role_id) {
                    case 1: $type = 'ADMIN'; break;
                    case 3: $type = 'PROVIDER'; break;
                    default: $type = 'CONSUMER'; break;
                }
                if ($info->gender == 0) {
                    $gender = 'FEMALE';
                } else {
                    $gender = 'MALE';
                }
                $getAddresses = UserAddress::whereUserId($account->id)->with([
                    'lv1',
                    'lv2',
                    'lv3'
                ])->get();

                $addresses = array();

                if ($getAddresses) {
                    foreach ($getAddresses as $item) {
                        $addresses[] = [
                            'description' => $item->address,
                            'provinceId' => $item->address_lv1,
                            'districtId' => $item->address_lv2,
                            'communeId' => $item->address_lv3,
                            'legacyAddressId' => $item->id
                        ];
                    }
                }

                $avatar = $account->avatar ? $account->avatar->getUrl() : '';
                $user = [
                    "legacyUserId" => $account->id,
                    "type" => $type,
                    "fullName"=> $account->name,
                    "firstName"=> $account->name,
                    "lastName"=> $account->name,
                    "password"=> $password,
                    "birthday"=> $info->birthday ? date_format($info->birthday, "d/m/Y") : null,
                    "email"=> $account->email,
                    "phone"=> $account->phone,
                    "gender"=> $gender,
                    "avatar"=> $avatar,
                    "addresses"=> $addresses
                ];

                $requestClient = new RequestClient();
                $client = $requestClient->create();
                $postUser = $client->request('POST', '/profile/profile/createORupdate', ['body' => json_encode($user)]);
                if ($postUser) {
                    \Log::info('sync user profile done');
                    if ($user) {
                        \Log::info('post data:');
                        \Log::info(json_encode($user));
                    }
                    return true;
                } else {
                    \Log::info('sync user profile not complete without any errors');
                    if ($user) {
                        \Log::info('post data:');
                        \Log::info(json_encode($user));
                    }
                    return false;
                }
            } catch (\Exception $ex) {
                \Log::info('SYNC process stoped by error: ' . $ex->getMessage() . '; code: ' . $ex->getCode() .
                    '; line: ' . $ex->getLine() . '; in file: ' . $ex->getFile());
                if (isset($user) && $user) {
                    \Log::info('post data:');
                    \Log::info(json_encode($user));
                }
            }
        }
        return false;
    }
}