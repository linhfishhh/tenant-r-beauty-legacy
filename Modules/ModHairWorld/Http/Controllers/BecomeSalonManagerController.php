<?php

namespace Modules\ModHairWorld\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Requests\Ajax;
use DataTables;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Modules\ModHairWorld\Entities\ManagerRegister;

class BecomeSalonManagerController extends Controller
{
    function register(Request $request){
        return view('modhairworld::become_salon_manager', []);
    }

    function submitRegister(Request $request){
        \Validator::validate(
            $request->all(),
            [
                'salon_name' => ['required'],
                'manager_name' => ['required'],
                'phone' => ['required', 'numeric', 'unique:manager_registers,manager_phone'],
                'email' => ['required', 'email', 'unique:manager_registers,manager_email'],
                'address' => ['required'],
                'captcha' => ['required', 'captcha']
            ],
            [
                'salon_name.required' => 'Vui lòng cho biết tên salon',
                'manager_name.required' => 'Vui lòng cho biết tên quản lý salon',
                'phone.required' => 'Vui lòng nhập số điện thoại',
                'phone.numeric' => 'Số điện thoại không hợp lệ',
                'phone.unique' => 'Số điện thoại này đã được sử dụng',
                'email.unique' => 'Email này đã được sử dụng.',
                'email.required' => 'Vui lòng nhập email liên hệ',
                'email.email' => 'Email không hợp lệ',
                'address.required' => 'Vui lòng cung cấp địa chỉ salon',
                'captcha.required' => __('Vui lòng nhập mã bảo mật'),
                'captcha.captcha' => __('Mã bảo mật nhập không đúng'),
            ]
        );
        $new = new ManagerRegister();
        $new->salon_name = $request->get('salon_name');
        $new->manager_name = $request->get('manager_name');
        $new->manager_email = $request->get('email');
        $new->manager_phone = $request->get('phone');
        $new->salon_address = $request->get('address');
        $new->save();
        return \Response::json(true);
    }

    function backendIndex(Request $request){
        if ($request->ajax()) {
            $subs = ManagerRegister::query();
            $rs = DataTables::eloquent($subs);
            $rs->filter(
                function ($query) use
                (
                    $request
                ) {
                    /** @var Builder $query */
                    $keyword = "%{$request->get( 'search')['value']}%";
                    if ($keyword) {
                        $query->where(
                            function ($query) use
                            (
                                $keyword
                            ) {
                                /** @var Builder $query */
                                $query->where(
                                    'manager_name',
                                    'like',
                                    $keyword
                                );
                                $query->orWhere(
                                    'manager_email',
                                    'like',
                                    $keyword
                                );
                                $query->orWhere(
                                    'manager_phone',
                                    'like',
                                    $keyword
                                );
                            }
                        );
                    }

                    /** @var Builder $query */
                    $date_start = $request->get('date_start', '');
                    $date_end = $request->get('date_end', '');
                    if($date_end && $date_start){
                        $query->whereBetween(
                            'created_at',
                            [
                                $date_start,
                                $date_end
                            ]
                        );
                    }
                    $handled = $request->get('handled', -1);
                    if($handled != -1){
                        $query->where('handled', $handled);
                    }
                }
            );
            $rs = $rs->make();
            return $rs;
        }
        return view('modhairworld::backend.pages.register.index');
    }

    function backendDestroy(Ajax $request){
        $ids = $request->get('ids', []);
        ManagerRegister::whereIn('id', $ids)->delete();
        return \Response::json();
    }

    function backendHandle(Ajax $request){
        $ids = $request->get('ids', []);
        $handled = $request->get('handled', 0);
        ManagerRegister::whereIn('id', $ids)->update([
            'handled' => $handled
        ]);
        return \Response::json();
    }
}