<?php

namespace Modules\ModHairWorld\Http\Controllers\api;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\UploadedFile;
use Illuminate\Http\Request;
use Modules\ModHairWorld\Entities\Api\PhoneVerify;
use Modules\ModHairWorld\Entities\SalonLike;
use Modules\ModHairWorld\Entities\SalonOrder;
use Modules\ModHairWorld\Entities\SalonShowcaseLike;
use Modules\ModHairWorld\Entities\UserAddress;
use Modules\ModHairWorld\Entities\UserExtra;
use Modules\ModHairWorld\Http\Requests\Api\Profile\ProfileUpdateRequest;
use Modules\ModHairWorld\Rules\PasswordExist;
use Modules\ModHairWorld\Handlers\AuthHandler;
use App\User;

class ProfileController extends Controller
{
    use AuthHandler;

    function get(){
        $user = me();
        $addresses = UserAddress::with(['lv1', 'lv2', 'lv3'])->whereUserId($user->id)->get();
        $user_extra = UserExtra::where('id', '=', $user->id)->first();
        $gender = $user_extra ? $user_extra->genderText() : '';
        $birthday = $user_extra && $user_extra->birthday ? $user_extra->birthday->format('d/m/Y') : '';
        $booking_order_count = SalonOrder::where(['user_id' => $user->id])->count();
        $fav_count = SalonLike::whereUserId($user->id)->count() + SalonShowcaseLike::whereUserId($user->id)->count();
        return \Response::json([
            'avatar' => [
                'uri' => $user->avatar?$user->avatar->getThumbnailUrl('default', getNoThumbnailUrl()):getNoThumbnailUrl()
            ],
            'name' => $user->name,
            'phone' => $user->phone,
            'email' => $user->email,
            'gender' => $gender,
            'birthday' => $birthday,
            'addresses' => $addresses->map(function (UserAddress $address){
                return [
                    'text' => $address->getAddressLine(),
                    'address' => $address->address,
                    'lv1' => $address->address_lv1,
                    'lv2' => $address->address_lv2,
                    'lv3' => $address->address_lv3
                ];
            }),
            'statistic' => [
                'bookingOrderCount' => $booking_order_count,
                'shoppingOrderCount' => 0,
                'favoriteCount' => $fav_count,
            ],
        ]);
    }

    function update(ProfileUpdateRequest $request){
        $name = $request->get('name');
        $email = $request->get('email');
        $avatar = $request->file('avatar');
        $user = me();
        $user->name = $name;
        $user->email = $email;
        $user->save();
        if($avatar){
            $uploaded_avatar = UploadedFile::upload($avatar, $user->id,'user_avatar');
            $user->avatar_id = $uploaded_avatar->id;
            $user->save();
        }
        if ($request->has('gender')) {
            $gender = $request->get('gender');
            $user_extra = UserExtra::fromUserID($user->id);
            $user_extra->gender = $gender;
            $user_extra->save();
        }
        if ($request->has('dob')) {
            $dob = $request->get('dob');
            $user_extra = UserExtra::fromUserID($user->id);
            $user_extra->birthday = Carbon::createFromFormat('d/m/Y', $dob);
            $user_extra->save();
        }
        $addresses = $request->get('addresses', []);
        $old_addresses = UserAddress::whereUserId($user->id)->get();
        foreach ($old_addresses as $old_address){
            $old_address->delete();
        }
        foreach ($addresses as $address){
            $new_address = new UserAddress();
            $new_address->user_id = $user->id;
            $new_address->address = $address['address'];
            $new_address->address_lv1 = $address['lv1'];
            $new_address->address_lv2 = $address['lv2'];
            $new_address->address_lv3 = $address['lv3'];
            $new_address->name = $user->name;
            $new_address->phone = $user->phone;
            $new_address->email = $user->email;
            $new_address->save();
        }
        $this->syncData(null);

        return $this->get();
    }

    function updatePhone(Request $request){
        $code = $request->get('code');
        $phone = $request->get('phone');
        $verified = PhoneVerify::verify($phone, $code);
        if($verified instanceof \Exception){
            abort(400,$verified->getMessage());
        }
        $user = \Auth::user();
        $user->phone = $phone;
        $user->save();

        $this->syncData(null);
        return \Response::json(true);
    }

    function updateEmail(Request $request){
        $name = $request->get('name');
        $email = $request->get('email');
        if (empty($name)) {
            return [
                'status' => false,
                'message' => 'Vui lòng nhập tên của bạn.',
            ];
        }
        if (empty($email)) {
            return [
                'status' => false,
                'message' => 'Vui lòng nhập email của bạn.',
            ];
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'status' => false,
                'message' => 'Địa chỉ email không đúng định dạng',
            ];
        }
        $existUsers = User::where('email', '=', $email)->get();
        if ($existUsers && count($existUsers) > 0) {
            return [
                'status' => false,
                'message' => 'Địa chỉ email đã tồn tại',
            ];
        }
        $user = \Auth::user();
        $user->name = $name;
        $user->email = $email;
        $user->save();

        $this->syncData(null);
        return \Response::json([
            'status' => true,
            'data' => $this->get(),
        ]);
    }

    function changePassword(Request $request){
        $user = me();
        \Validator::validate($request->all(), [
           'old_password' => ['required', new PasswordExist($user)],
           'password' => ['required', 'string', 'min:6', 'confirmed']
        ], [
            'old_password.required' => 'Vui lòng nhập mật khẩu hiện tại',
            'old_password.PasswordExist' => 'Mật khẩu hiện tại không đúng',
            'password.required' => 'Vui lòng nhập mật khẩu mới',
            'password.min' => 'Mật khẩu mới phải từ 6 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp'
        ]);

        $user->password = \Hash::make($request->get('password'));
        $user->save();

        $this->syncData($request->get('password'));
        return \Response::json(true);
    }
}