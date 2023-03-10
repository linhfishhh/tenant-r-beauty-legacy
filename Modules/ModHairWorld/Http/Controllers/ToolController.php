<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/8/18
 * Time: 14:21
 */

namespace Modules\ModHairWorld\Http\Controllers;


use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\ModHairWorld\Entities\Salon;
use Modules\ModHairWorld\Entities\SalonManager;
use Modules\ModHairWorld\Entities\SalonOpenTime;
use Modules\ModHairWorld\Entities\SalonStylist;
use Modules\ModHairWorld\Handlers\SalonWalletHandler;

class ToolController extends Controller
{
    use SalonWalletHandler;

    function importIndex(Request $request){
        return view('modhairworld::backend.pages.tools.import');
    }

    function rollBack(Request $request){
        $records = $request->get('record', []);
        $users = [];
        $salons = [];
        if($records){
            foreach ($records as $record){
                if($record['type'] == 'salon'){
                    $salons[] = $record['id'];
                }

                if($record['type'] == 'user'){
                    $users[] = $record['id'];
                }
            }
        }
        if($salons){
            /** @var Salon[] $deletes */
            $deletes = Salon::whereIn('id', $salons)->get();
            foreach ($deletes as $delete){
                $delete->delete();
            }
        }
        if($users){
            /** @var User[] $deletes */
            $deletes = User::whereIn('id', $users)->get();
            foreach ($deletes as $delete){
                $delete->delete();
            }
        }
        return \Response::json(true);
    }

    function importDo(Request $request){
        \Validator::validate($request->all(),
            [
                'salon_name' => ['required'],
                'manager_phone' => ['required_with_all:manager_name', 'numeric', Rule::unique('users','phone')],
                'manager_email' => ['required_with_all:manager_name', 'email', Rule::unique('users','email')],
                'salon_stylists' => ['array'],
                'salon_times' => ['array'],
            ],
        [
            'salon_name.required' => 'Ch??a nh???p t??n salon',
            'manager_phone.required_with_all' => 'Ch??a nh???p s??? ??i???n tho???i c???a qu???n l?? salon',
            'manager_phone.unique' => 'S??? ??i???n tho???i ???? ???????c s??? d???ng',
            'manager_phone.email' => 'Email ???? ???????c s??? d???ng',
            'manager_phone.numeric' => 'S??? ??i???n tho???i qu???n l?? salon kh??ng h???p l???',
            'manager_email.required_with_all' => 'Ch??a nh???p email c???a qu???n l?? salon',
            'manager_email.email' => 'Email c???a qu???n l?? salon kh??ng h???p l???',
        ]
        );
        $salon_name = $request->get('salon_name');
        $salon_desc = $request->get('salon_desc', '');
        $stylists = $request->get('salon_stylists', []);
        $manager_name = $request->get('manager_name');
        $manager_phone = $request->get('manager_phone');
        $manager_email = $request->get('manager_email');
        $times = $request->get('salon_times', []);
        $roll = [];
        $salon = new Salon();
        $salon->name = $salon_name;
        $salon->info = $salon_desc;
        $salon->address = 'T???ng 3, D2 Gi???ng V??';
        $salon->tinh_thanh_pho_id = 1;
        $salon->quan_huyen_id = 1;
        $salon->phuong_xa_thi_tran_id = 31;
//        $salon->map_lat = 21.0261162;
//        $salon->map_long = 105.82228740000005;
        $salon->map_lat = -1;
        $salon->map_long = -1;
        $salon->map_zoom = 13;
        $salon->save();
        $this->syncSalonWallet($salon->id);
        $roll[] = [
            'type' => 'salon',
            'id' => $salon->id
        ];
        $add_times = 0;
        if($times){
            foreach ($times as $time){
                if($time['start'] === $time['end']){
                    continue;
                }
                $st = new SalonOpenTime();
                $st->salon_id = $salon->id;
                $st->weekday = $time['weekday'];
                $st->start = $time['start'];
                $st->end = $time['end'];
                $st->save();
                $add_times++;
            }
        }
        $add_stylists = 0;
        if($stylists){
            foreach ($stylists as $stylist){
                $ss = new SalonStylist();
                $ss->salon_id = $salon->id;
                $ss->name = $stylist;
                $ss->save();
                $add_stylists++;
            }
        }
        $rs = '<ul>';
        $edit_link = route('backend.salon.basic_info.edit',['salon'=>$salon->id]);
        $view_link = route('frontend.salon', ['salon'=>$salon->id]);
        $rs .= "<li><strong>{$salon_name}</strong> <a target='_blank' href='{$edit_link}'>[S???a]</a> <a target='_blank' href='{$view_link}'>[Xem]</a></li>";
        if($add_times){
            $rs .= "<li>L??m vi???c: {$salon->timeWorkingHours()} / {$salon->timeWeekDays()}</li>";
        }
        if($add_stylists > 0){
            $rs .= "<li>{$add_stylists} stylist</li>";
        }
        if($manager_name && $manager_phone && $manager_email){
            /** @var User[]|Collection $exist_users */
            $exist_users = User::where(function ($query) use($manager_email, $manager_phone, $manager_name){
                /** @var Builder $query */
                $query->where('email', $manager_email)
                    ->orWhere('phone', $manager_phone);
            })->get();
            if($exist_users->count()==0){
                $password = 'isalon@'.$manager_phone;
                $new_user = new User();
                $new_user->email = $manager_email;
                $new_user->phone = $manager_phone;
                $new_user->name = $manager_name;
                $new_user->password = \Hash::make($password);
                $new_user->role_id = config('app.register_role_id');
                $new_user->save();
                $manager = new SalonManager();
                $manager->salon_id = $salon->id;
                $manager->user_id = $new_user->id;
                $manager->save();
                $roll[] = [
                    'type' => 'user',
                    'id' => $new_user->id
                ];
                $rs .= "<li>T??i kho???n login: {$manager_phone}</li>";
                $rs .= "<li>Password login: {$password}</li>";
            }
            else if($exist_users->count()==1){
                /** @var User $exist_user */
                $exist_user = $exist_users->first();
                $exist_manager = SalonManager::where('user_id', $exist_user->id)->first();
                if(!$exist_manager){
                    $manager = new SalonManager();
                    $manager->salon_id = $salon->id;
                    $manager->user_id = $exist_user->id;
                    $manager->save();
                    $rs .= "<li>T??i kho???n login: {$manager_phone}</li>";
                    $rs .= "<li>M???t kh???u: D??ng m???t kh???u hi???n t???i ????? ????ng nh???p</li>";
                }
                else{
                    $rs .= "<li>Kh??ng th??? t???o t??i kho???n qu???n l?? v?? t??i kho???n qu???n l?? ???? t???n t???i v?? ???? c?? salon qu???n l??</li>";
                }
            }
            else{
                $rs .= "<li>Kh??ng th??? t???o t??i kho???n qu???n l??</li>";
            }
        }
        $rs .='</ul>';
        return \Response::json([
            'rollback' => $roll,
            'message' => $rs
        ]);
    }
}