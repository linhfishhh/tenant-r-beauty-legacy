<?php
namespace Modules\ModHairWorld\Http\Controllers\frontend;


use App\Http\Controllers\Controller;
use App\Http\Requests\Ajax;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\DatabaseNotification;
use Modules\ModHairWorld\Entities\Api\PhoneVerify;
use Modules\ModHairWorld\Entities\SalonLike;
use Modules\ModHairWorld\Entities\SalonOrder;
use Modules\ModHairWorld\Entities\SalonShowcaseLike;
use Modules\ModHairWorld\Entities\UserAddress;
use Modules\ModHairWorld\Entities\UserExtra;
use Modules\ModHairWorld\Entities\UserSocialTemp;
use Modules\ModHairWorld\Http\Requests\Frontend\Account\AddressesSave;
use Modules\ModHairWorld\Http\Requests\Frontend\Account\AvatarSave;
use Modules\ModHairWorld\Http\Requests\Frontend\Account\BasicInfoSave;
use Modules\ModHairWorld\Http\Requests\Frontend\Account\NewPasswordSave;
use Modules\ModHairWorld\Http\Requests\Frontend\Account\RegisterStepOneForm;
use Modules\ModHairWorld\Http\Requests\Frontend\Account\RegisterStepTwoForm;
use Modules\ModHairWorld\Http\Requests\Frontend\Account\ResetPasswordForm;
use Modules\ModHairWorld\Notifications\CommonNotify;
use Modules\ModHairWorld\Handlers\AuthHandler;

class AccountController extends Controller
{
    use AuthHandler;

    function testNotify(Request $request)
    {
        $color = [
            '#00A69C',
            '#FF5C39'
        ];
        me()->notify(new CommonNotify(
            false,
            'Bạn có một mã Coupon code quy đổi từ việc huỷ dịch vụ của bạn - ' . random_int(11111, 99999),
            '#',
            $color[random_int(0, 1)]
        ));

        return \Redirect::route('frontend.account.notification');
    }

    function socialCreateAccount(Request $request)
    {
        \Validator::validate($request->all(),
            [
                'token' => ['required', 'exists:user_social_temps,token'],
                'phone' => ['required', 'numeric', 'unique:users,phone'],
                'code' => ['required', 'numeric'],
            ],
            [
                'code.required' => 'Vui lòng nhập mã xác nhận',
                'code.numeric' => 'Sai mã xác nhận',
            ]
        );
        $code = $request->get('code');
        $phone = $request->get('phone');
        $token = $request->get('token');
        $rs = PhoneVerify::verify($phone, $code);
        if($rs instanceof \Exception){
            abort(400,$rs->getMessage());
        }
        /** @var UserSocialTemp $temp */
        $temp = UserSocialTemp::where('token', $token)->first();
        if (!$temp) {
            return \Response::json(-2);
        }

        $user = new User();
        $user->name = $temp->name;
        $user->email = $temp->email;
        $user->phone = $phone;
        $user->role_id = config('app.register_role_id');
        $password = rand(11111, 99999);
        $user->password = \Hash::make($password);
        $user->save();
        \Auth::attempt(
            [
                'email' => $temp->email,
                'password' => $password
            ],
            true
        );

        $this->syncData($password);

        return \Response::json(true);
    }

    function socialAddPhone(Request $request)
    {
        \Validator::validate($request->all(), [
            'token' => ['required', 'exists:user_social_temps,token'],
            'phone' => ['required', 'numeric', 'unique:users,phone']
        ], [
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'phone.numeric' => 'Số điện thoại không hợp lệ',
            'phone.unique' => 'Số điện thoại đã tồn tại'
        ]);
        $phone = $request->get('phone');
        $token = $request->get('token');
        $vrs = PhoneVerify::newVerify($phone);
        if($vrs instanceof \Exception){
            abort(400,$vrs->getMessage());
        }
        $this->syncData(null);
        return \Response::json(true);
    }

    function refreshVerifyCode(Ajax $request)
    {
        \Validator::validate($request->all(), [
            'phone' => [
                'required',
                'numeric',
            ]
        ]);
        $phone = $request->get('phone');
        $vrs = PhoneVerify::newVerify($phone);
        if($vrs instanceof \Exception){
            abort(400,$vrs->getMessage());
        }
        return \Response::json(true);
    }

    function registerStepOne(RegisterStepOneForm $form)
    {
        $phone = $form->get('phone');
        $email = $form->get('email');
        $password = $form->get('password');
        $vrs = PhoneVerify::newVerify($phone);
        if($vrs instanceof \Exception){
            abort(400,$vrs->getMessage());
        }
        return \Response::json(true);
    }

    function registerStepTwo(RegisterStepTwoForm $form)
    {
        $code = $form->get('code');
        $phone = $form->get('phone');
        $email = $form->get('email');
        $password = $form->get('password');
        $name = $form->get('name');
        $rs = PhoneVerify::verify($phone, $code);
        if($rs instanceof \Exception){
            abort(400,$rs->getMessage());
        }
        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->phone = $phone;
        $user->role_id = config('app.register_role_id');
        $user->password = \Hash::make($password);
        $user->save();
        \Auth::attempt(
            [
                'email' => $email,
                'password' => $password
            ],
            true
        );
        return \Response::json($form->url());
    }

    function requestResetPassword(ResetPasswordForm $form)
    {
        $phone = $form->get('phone');
        $vrs = PhoneVerify::newVerify($phone);
        if($vrs instanceof \Exception){
            abort(400,$vrs->getMessage());
        }
        return \Response::json(true);
    }

    function requestResetPasswordCheck(Ajax $request){
        \Validator::validate($request->all(),[
            'code' => ['required', 'numeric'],
            'phone' => [
                'required',
                'numeric',
                'exists:users,phone'
            ],
        ], [
            'code.required' => 'Vui lòng nhập mã xác nhận',
            'code.numeric' => 'Sai mã xác nhận',
            'phone.required' => 'Thông tin số điện thoại không được trống',
            'phone.numeric' => 'Số điện thoại không hợp lệ',
            'phone.exists' => 'Số điện thoại không tồn tại trong hệ thống'
        ]);
        $code = $request->get('code');
        $phone = $request->get('phone');
        $rs = PhoneVerify::verify($phone, $code);
        if($rs instanceof \Exception){
            abort(400,$rs->getMessage());
        }
        return \Response::json(true);
    }

    function requestResetPasswordSave(Ajax $request){
        \Validator::validate($request->all(),[
            'code' => ['required', 'numeric'],
            'phone' => [
                'required',
                'numeric',
                'exists:users,phone'
            ],
            'password' => [
                'required',
                'min:6',
                'confirmed'
            ],
            'password_confirmation' => [
                'required'
            ]
        ], [
            'code.required' => 'Vui lòng nhập mã xác nhận',
            'code.numeric' => 'Sai mã xác nhận',
            'phone.required' => 'Thông tin số điện thoại không được trống',
            'phone.numeric' => 'Số điện thoại không hợp lệ',
            'phone.exists' => 'Số điện thoại không tồn tại trong hệ thống',
            'password.required' => 'Vui lòng nhập mật khẩu mới',
            'password.confirmed' => 'Mật khẩu mới không khớp với xác nhận bên dưới',
            'password_confirmation.required' => 'Vui lòng nhập xác nhận mật khẩu',
            'password.min'       => __('Mật khẩu phải từ 6 ký tự'),
        ]);
        $phone = $request->get('phone');
        $code = $request->get('code');
        $password = $request->get('password');
        $verified = PhoneVerify::verify($phone, $code);
        if($verified instanceof \Exception){
            abort(400,$verified->getMessage());
        }
        /** @var User $user */
        $user = User::wherePhone($phone)->first();
        if(!$user){
            abort(400, 'Tài khoản không tồn tại');
        }
        $user->password = \Hash::make($password);
        $user->save();
        return \Response::json(true);
    }

    function removeFavSalon(Ajax $request)
    {
        $id = $request->get('id');
        $like = SalonLike::whereUserId(me()->id)->where('id', $id)->first();
        if ($like) {
            $like->delete();
            return \Response::json(1);
        } else {
            return \Response::json(0);
        }
    }

    function removeFavShowcase(Ajax $request)
    {
        $id = $request->get('id');
        $like = SalonShowcaseLike::whereUserId(me()->id)->where('id', $id)->first();
        if ($like) {
            $like->delete();
            return \Response::json(1);
        } else {
            return \Response::json(0);
        }
    }

    function history(Request $request)
    {
        if ($request->ajax()) {
            $per_page = 10;
            $from = $request->get('from');
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');
            $status = $request->get('status');
            $items = SalonOrder::whereUserId(me()->id)
                ->with(
                    'salon',
                    'salon.location_lv1',
                    'salon.location_lv2',
                    'salon.location_lv3',
                    'items'
                )
                ->orderByDesc('id');
            if ($from > -1) {
                $items->where('id', '<', $from);
            }

            if (array_key_exists($status, SalonOrder::getStatusList())) {
                $items->where('status', $status);
            }

            $items = $items->limit($per_page)->get();
            $html = view(getThemeViewName('components.history_item'), [
                'items' => $items,
                'from' => $from
            ])->render();

            if ($items->count()) {
                return \Response::json([
                    'html' => $html,
                    'count' => $items->count(),
                    'next' => $items->last()->id,
                    'last' => $items->count() < $per_page
                ]);
            } else {
                return \Response::json([
                    'html' => $html,
                    'count' => $items->count(),
                    'next' => 0,
                    'last' => 1
                ]);
            }
        }
        return view(getThemeViewName('account.history'));
    }

    function historyDetail(Ajax $request, SalonOrder $order)
    {
        $rs = view(getThemeViewName('components.history_detail'), [
            'order' => $order
        ])->render();
        return \Response::json($rs);
    }

    function listFavSalon(Ajax $request)
    {
        $items = SalonLike::whereUserId(me()->id)
            ->with(
                [
                    'salon',
                    'salon.cover'
                ]
            )
            ->orderByDesc('id');
        $per_page = 10;
        $from = $request->get('from');
        if ($from > -1) {
            $items->where('id', '<', $from);
        }
        $items = $items->limit($per_page)->get();
        $html = view(getThemeViewName('components.fav_salon_ajax'), [
            'items' => $items,
            'from' => $from
        ])->render();
        if ($items->count()) {
            return \Response::json([
                'html' => $html,
                'count' => $items->count(),
                'next' => $items->last()->id,
                'last' => $items->count() < $per_page
            ]);
        } else {
            return \Response::json([
                'html' => $html,
                'count' => $items->count(),
                'next' => 0,
                'last' => 1
            ]);
        }
    }

    function listFavShowcase(Ajax $request)
    {
        $items = SalonShowcaseLike::whereUserId(me()->id)
            ->with(
                [
                    'showcase',
                    'showcase.items',
                    'showcase.items.image',
                ]
            )
            ->orderByDesc('id');
        $per_page = 12;
        $from = $request->get('from');
        if ($from > -1) {
            $items->where('id', '<', $from);
        }
        $items = $items->limit($per_page)->get();
        $html = view(getThemeViewName('components.fav_showcase_ajax'), [
            'items' => $items,
            'from' => $from
        ])->render();
        if ($items->count()) {
            return \Response::json([
                'html' => $html,
                'count' => $items->count(),
                'next' => $items->last()->id,
                'last' => $items->count() < $per_page
            ]);
        } else {
            return \Response::json([
                'html' => $html,
                'count' => $items->count(),
                'next' => 0,
                'last' => 1
            ]);
        }
    }

    function checkUnread(Ajax $request)
    {
        $rs = me()->notifications()->where('read_at', '=', null)->count() > 0;
        return \Response::json($rs);
    }

    function viewNotifications(Request $request)
    {
        return view(getThemeViewName('account.notification'));
    }

    function removeNotification(Ajax $request, $id)
    {
        /** @var DatabaseNotification $notification */
        $notification = me()->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->delete();
            return \Response::json(1);
        }
        return \Response::json(0);
    }

    function readNotification(Ajax $request, $id)
    {
        /** @var DatabaseNotification $notification */
        $notification = me()->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
            return \Response::json(1);
        }
        return \Response::json(0);
    }

    function listNotification(Ajax $request)
    {
        $rs = [];
        $id = $request->get('from_id');
        /** @var Builder $q */
        $q = me()->notifications()->limit(10);
        if ($id) {
            $rs = $q->where('order_num', '<', $id)->get();
        } else {
            $rs = $q->get();
        }

        $load = [];
        /** @var DatabaseNotification $r */
        foreach ($rs as $r) {
            $data = collect($r->data);
            $cover_id = $data->get('cover', '');
            $r['cover'] = '';
            $r['cover_id'] = $cover_id ? $cover_id : 0;
            if ($cover_id) {
                $load[] = $cover_id;
            }
        }
        /** @var \App\UploadedFile[] $loaded */
        $loaded = \App\UploadedFile::whereIn('id', $load)->get();
        $loaded_url = [];
        foreach ($loaded as $item) {
            $loaded_url[$item->id] = $item->getThumbnailUrl('default', '');
        }
        $loaded_url = collect($loaded_url);
        foreach ($rs as $r) {
            $r['cover'] = $loaded_url->get($r['cover_id']);
        }
        return \Response::json($rs);
    }

    function resetPassword(Request $request)
    {
        return view(getThemeViewName('account.reset_password'), []);
    }

    function saveNewPassword(NewPasswordSave $form)
    {
        $password = $form->get('password');
        $password = \Hash::make($password);
        $user = me();
        $user->password = $password;
        $user->save();

        $this->syncData($form->get('password'));

        return \Response::json();
    }

    function checkNewPhone(Request $request){
        \Validator::validate($request->all(), [
            'phone' => [
                'required',
                'numeric',
                'unique:users,phone'
            ],
        ], [
            'phone.required' => 'Thông tin số điện thoại không được trống',
            'phone.numeric' => 'Số điện thoại không hợp lệ',
            'phone.unique' => 'Số điện thoại này đã được đăng ký',
        ]);
        $phone = $request->get('phone');
        $vrs = PhoneVerify::newVerify($phone);
        if($vrs instanceof \Exception){
            abort(400,$vrs->getMessage());
        }
        return \Response::json();
    }

    function saveNewPhone(Request $request){
        \Validator::validate($request->all(), [
            'phone' => [
                'required',
                'numeric',
                'unique:users,phone'
            ],
            'code' => ['required', 'numeric'],
        ], [
            'phone.required' => 'Thông tin số điện thoại không được trống',
            'phone.numeric' => 'Số điện thoại không hợp lệ',
            'phone.unique' => 'Số điện thoại này đã được đăng ký',
            'code.required' => 'Vui lòng nhập mã xác nhận',
            'code.numeric' => 'Sai mã xác nhận',
        ]);
        $phone = $request->get('phone');
        $code = $request->get('code');
        $verified = PhoneVerify::verify($phone, $code);
        if($verified instanceof \Exception){
            abort(400,$verified->getMessage());
        }
        /** @var User $user */
        $user = me();
        $user->phone = $phone;
        $user->save();

        $this->syncData(null);

        return \Response::json();
    }

    function profile(Request $request)
    {
        $info = UserExtra::fromUserID(me()->id);
        $order_count = SalonOrder::where('user_id', me()->id)->count();
        $waiting_order_count = SalonOrder::where('user_id', me()->id)->where('status', 2)
            ->where('service_time', '>=', Carbon::now())
            ->count();
        $last_waiting_order = SalonOrder::where('user_id', me()->id)->where('status', 2)
            ->where('service_time', '>=', Carbon::now())->orderBy('service_time')->first();
        $salon_like = SalonLike::where('user_id', me()->id)->count();
        $showcase_like = SalonShowcaseLike::where('user_id', me()->id)->count();
        $addresses = UserAddress::whereUserId(me()->id)
            ->with([
                'lv1',
                'lv2',
                'lv3'
            ])
            ->get();
        return view(getThemeViewName('account.profile'), [
            'info' => $info,
            'addresses' => $addresses,
            'order_count' => $order_count,
            'waiting_order_count' => $waiting_order_count,
            'like_count' => $salon_like + $showcase_like,
            'last_waiting_order' => $last_waiting_order
        ]);
    }

    function editProfile(Request $request)
    {
        $info = UserExtra::fromUserID(me()->id);
        $addresses = UserAddress::whereUserId(me()->id)->with([
            'lv1',
            'lv2',
            'lv3'
        ])->get();
        return view(getThemeViewName('account.edit'), [
            'info' => $info,
            'addresses' => $addresses
        ]);
    }

    function deleteAddress(Ajax $ajax, UserAddress $address)
    {
        if ($address->user_id == me()->id) {
            $address->delete();
            $this->syncData(null);
            return \Response::json(1);
        }
        return \Response::json(0);
    }

    function saveAddresses(AddressesSave $form)
    {
        $address = $form->get('address');
        $address_lv1 = $form->get('tinh_thanh_pho_id');
        $address_lv2 = $form->get('quan_huyen_id');
        $address_lv3 = $form->get('phuong_xa_thi_tran_id');
        $id = $form->get('id', null);
        if ($id != null && $id != 'null') {
            $line = UserAddress::find($id);
            $line->address = $address;
            $line->address_lv1 = $address_lv1;
            $line->address_lv2 = $address_lv2;
            $line->address_lv3 = $address_lv3;
            $line->name = \Auth::user()->name;
            $line->email = \Auth::user()->email;
            $line->phone = \Auth::user()->phone;
            $line->save();
        } else {
            $line = new UserAddress();
            $line->user_id = me()->id;
            $line->address = $address;
            $line->address_lv1 = $address_lv1;
            $line->address_lv2 = $address_lv2;
            $line->address_lv3 = $address_lv3;
            $line->name = \Auth::user()->name;
            $line->email = \Auth::user()->email;
            $line->phone = \Auth::user()->phone;
            $line->save();
        }
        $line->load([
            'lv1',
            'lv2',
            'lv3'
        ]);
        $data = $line->attributesToArray();
        $data['text'] = $line->getAddressLine();
        $data['lv1_text'] = $line->lv1->name;
        $data['lv2_text'] = $line->lv2->name;
        $data['lv3_text'] = $line->lv3->name;

        $this->syncData(null);

        return \Response::json($data);
    }

    function saveBasicInfo(BasicInfoSave $form)
    {
        $user = me();
        $info = UserExtra::fromUserID($user->id);
        $user->name = $form->get('name');
        $user->email = $form->get('email');
        $user->save();
        $birthday = Carbon::createFromFormat('d/m/Y', $form->get('birthday'));
        $info->birthday = $birthday;
        $info->gender = $form->get('gender');
        $info->save();

        $this->syncData(null);

        return \Response::json($form);
    }

    function saveAvatar(AvatarSave $form)
    {
        $user = me();
        /** @var UploadedFile $avatar */
        $file = $form->file('avatar');
        $filename = $form->get('filename');
        $avatar = \App\UploadedFile::upload($file, $user->id, User::getFileID(), $filename);
        if ($user->avatar_id) {
            /** @var \App\UploadedFile $old_avatar */
            $old_avatar = \App\UploadedFile::find(me()->avatar_id);
            if ($old_avatar) {
                $old_avatar->delete();
            }
        }
        $user->avatar_id = $avatar->id;
        $user->save();

        $this->syncData(null);

        return \Response::json(mb_strtolower($file->getClientOriginalName()));
    }

    function editPaymentMethod(Request $request)
    {
        $info = UserExtra::fromUserID(me()->id);
        return view(getThemeViewName('account.payment'), [
            'info' => $info
        ]);
    }

    function savePaymentMethod(Ajax $request)
    {
        $method = $request->get('payment_method', null);
        $info = UserExtra::fromUserID(me()->id);
        $info->payment_method = $method;
        $info->save();
        return \Response::json([]);
    }
}
