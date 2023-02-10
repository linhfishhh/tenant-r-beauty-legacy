<?php

namespace Modules\ModHairWorld\Http\Controllers\Backend;

use App\Classes\FieldInput\FieldInputColor;
use App\Classes\FieldInput\FieldInputFile;
use App\Classes\FieldInput\FieldInputFileList;
use App\Classes\FieldInput\FieldInputFontAwesome;
use App\Classes\FieldInput\FieldInputRepeater;
use App\Classes\FieldInput\FieldInputText;
use App\Classes\FieldInput\FieldInputTextArea;
use App\Classes\FieldInput\FieldInputTinyMCE;
use App\Classes\FieldInput\FieldInputTouchSpin;
use App\Http\Requests\Ajax;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ModHairWorld\Entities\DiaPhuongQuanHuyen;
use Modules\ModHairWorld\Entities\DiaPhuongTinhThanhPho;
use Modules\ModHairWorld\Entities\DiaPhuongXaPhuongThiTran;
use Modules\ModHairWorld\Entities\Salon;
use Modules\ModHairWorld\Entities\SalonBrand;
use Modules\ModHairWorld\Entities\SalonExtraInfo;
use Modules\ModHairWorld\Entities\SalonGallery;
use Modules\ModHairWorld\Entities\SalonManager;
use Modules\ModHairWorld\Entities\SalonOpenTime;
use Modules\ModHairWorld\Entities\SalonOrder;
use Modules\ModHairWorld\Entities\SalonOrderIncludedItem;
use Modules\ModHairWorld\Entities\SalonOrderItem;
use Modules\ModHairWorld\Entities\SalonService;
use Modules\ModHairWorld\Entities\SalonServiceCategory;
use Modules\ModHairWorld\Entities\SalonServiceImage;
use Modules\ModHairWorld\Entities\SalonServiceIncludedOption;
use Modules\ModHairWorld\Entities\SalonServiceLogo;
use Modules\ModHairWorld\Entities\SalonServiceOption;
use Modules\ModHairWorld\Entities\SalonServiceSale;
use Modules\ModHairWorld\Entities\SalonShowcase;
use Modules\ModHairWorld\Entities\SalonShowcaseItem;
use Modules\ModHairWorld\Entities\SalonStylist;
use Modules\ModHairWorld\Events\SalonOrderWaitingToProcess;
use Modules\ModHairWorld\Http\Controllers\BrandSmsController;
use Modules\ModHairWorld\Http\Requests\SalonBasicInfoUpdate;
use Modules\ModHairWorld\Http\Requests\SalonBrandStoreUpdate;
use Modules\ModHairWorld\Http\Requests\SalonExtendedInfoUpdate;
use Modules\ModHairWorld\Http\Requests\SalonGalleryStoreUpdate;
use Modules\ModHairWorld\Http\Requests\SalonManagerStore;
use Modules\ModHairWorld\Http\Requests\SalonServiceSaleStoreUpdate;
use Modules\ModHairWorld\Http\Requests\SalonServiceStoreUpdate;
use Modules\ModHairWorld\Http\Requests\SalonShowcaseStoreUpdate;
use Modules\ModHairWorld\Http\Requests\SalonStylistStoreUpdate;
use Modules\ModHairWorld\Handlers\SalonWalletHandler;

class SalonController extends Controller
{
    use SalonWalletHandler;

    function bookingsUpdate(Ajax $request, SalonOrder $order){
        if ($request->has('status')) {
            $status = $request->get('status');
            $old = $order->status;
            $order->status = $status;
        }

        if ($request->has('amount_money')) {
            $amountMoney = $request->get('amount_money');
            $totalChange = 0;
            if ($amountMoney != null) {
                $totalChange = intVal($amountMoney) - $order->amount_money;
                $order->amount_money = intval($amountMoney);
                $order->total = $order->total + $totalChange;
            }
        }
        switch ($order->status) {
            case SalonOrder::_HUY_DO_QUA_HAN_XU_LY:
            case SalonOrder::_HUY_BOI_SALON_:
            case SalonOrder::_HUY_BOI_KHACH_:
            case SalonOrder::_KHACH_KHONG_DEN_:
                $this->updatePaymentStatus($order, false);
                break;
            case SalonOrder::_DA_HOAN_THANH_:
                $this->updatePaymentStatus($order, true);
                break;
            default: {
                $this->updatePaymentStatus($order);
                break;
            }
        }
        $order->save();
//        if($old != $status){
//            if($status == 1){
//                $event = new SalonOrderWaitingToProcess($order);
//                event($event);
//            }
//        }
        if($order->status == SalonOrder::_CHO_XU_LY_){
            $order->created_at = Carbon::now();
            $order->save();
        }
        return \Response::json(1);
    }

    function bookingsDestroy(Ajax $request){
        $ids = $request->get('ids');
        /** @var SalonOrder[] $orders */
        $orders = SalonOrder::whereIn('id', $ids)->get();
        foreach ($orders as $order){
            $order->delete();
        }
        return \Response::json(1);
    }

    function bookings(Request $request){
        if($request->ajax()){
            $list = SalonOrder::with(['user', 'salon', 'items', 'included_items' , 'salon.location_lv1', 'salon.location_lv2', 'salon.location_lv3']);
            $rs = \DataTables::eloquent($list);
			    $rs->addColumn(
                'salon_name',
                function(SalonOrder $order){
                    return $order->salon_name;
                });
            $rs->addColumn(
                'address_line',
                function(SalonOrder $order){
                    return $order->salon_address;
                });
            $rs->addColumn(
                'status_text',
                function(SalonOrder $order){
                    return $order->getStatusText();
                });
            $rs->addColumn(
                'disable_change_status',
                function(SalonOrder $order) {
                    return ($order->status === SalonOrder::_HUY_DO_QUA_HAN_XU_LY ||
                            $order->status === SalonOrder::_HUY_BOI_SALON_ ||
                            $order->status === SalonOrder::_HUY_BOI_KHACH_ ||
                            $order->status === SalonOrder::_KHACH_KHONG_DEN_ ||
                            $order->status === SalonOrder::_DA_HOAN_THANH_);
                });
            $rs->addColumn(
                'sum',
                function(SalonOrder $order){
                    return number_format($order->items->sum(function (SalonOrderItem $item){
                        return $item->quatity*$item->price;
                    }), 0, '',',');
                });
            $rs->addColumn(
                'needToPay',
                function(SalonOrder $order){
                    if (isset($order->amount_money)) {
                        return number_format($order->amount_money, 0, '', ',');
                    }
                    return number_format($order->items->sum(function (SalonOrderItem $item){
                        return $item->quatity*$item->price;
                    }), 0, '',',');
                });
            $rs->addColumn(
                'items',
                function(SalonOrder $order){
                    return $order->items->map(function (SalonOrderItem $item){
                        return [
                            'name' => $item->service_name,
                            'qty' => $item->quatity,
                            'sum' => number_format($item->quatity * $item->price,0, '', ',')
                        ];
                    })->all();
                });
            $rs->addColumn(
                'included_items',
                function(SalonOrder $order){
                    return $order->included_items->map(function (SalonOrderIncludedItem $item){
                        return [
                            'name' => $item->included_options_name,
                            'qty' => $item->quatity,
                            'sum' => number_format($item->quatity * $item->price,0, '', ',')
                        ];
                    })->all();
                });
            $rs->filter(function ($query) use ($request){
                /** @var Builder $query */
                $keyword = $request->get('search')['value'];
                if(trim($keyword)){
                    $query->whereHas('user',function ($query) use ($request, $keyword){
                        /** @var Builder $query */
                        $query->whereNested(
                            function ($query) use($keyword) {
                                /** @var Builder $query */
                                $query->where('users.name','like', "%{$keyword}%");
                                $query->orWhere('users.email', 'like', "%{$keyword}%");
                                $query->orWhere('users.phone', 'like', "%{$keyword}%");
                            });
                    });
                    $query->orWhere('salon_name', 'like', "%{$keyword}%");
                    $query->orWhere('id', '=', $keyword);
                }
            });
            $rs = $rs->make(true);
            return $rs;
        }
        return view('modhairworld::backend.pages.booking.index');
    }

    public function salonStatCount(Ajax $request, Salon $salon){
        $rs = [
            'extended_info' => SalonExtraInfo::whereSalonId($salon->id)->count(),
            'manager' => SalonManager::whereSalonId($salon->id)->whereHas('user')->count(),
            'time' => SalonOpenTime::whereSalonId($salon->id)->count(),
            'stylist' => SalonStylist::whereSalonId($salon->id)->count(),
            'gallery' => SalonShowcase::whereSalonId($salon->id)->count(),
            'brand' => SalonBrand::whereSalonId($salon->id)->count(),
            'service' => SalonService::whereSalonId($salon->id)->count(),
            'sale' => $salon->saleServices()->count(),
            'images' => SalonGallery::whereSalonId($salon->id)->count()
        ];
        return \Response::json($rs);
    }

    public function index(Request $request)
    {
        if($request->ajax()){
            $list = Salon::with(['managers', 'location_lv1']);
            $rs = \DataTables::eloquent($list);
            $rs->addColumn(
                'address_line',
                function(Salon $salon){
                    return $salon->getAddressLine();
                });
            $rs->addColumn(
                'slug',
                function(Salon $salon){
                    return str_slug($salon->name);
                });
            $rs->addColumn(
                'location_slug',
                function(Salon $salon){
                    if($salon->location_lv1){
                        return str_slug($salon->location_lv1->name);
                    }
                    else{
                        return str_slug('dia-phuong-chua-biet');
                    }
                });
            $rs->addColumn(
                'link',
                function (Salon $salon) {
                    return route('backend.salon.basic_info.edit', ['salon'=>$salon]);
                });
            $rs = $rs->make(true);
            return $rs;
        }
        return view('modhairworld::backend.pages.salon.index');
    }

    public function editManagers(Request $request, Salon $salon){
        if($request->ajax()){
            $managers = SalonManager::whereSalonId($salon->id)->with(
                [
                    'user',
                    'user.avatar'
                ])->whereHas('user');
            $rs = \DataTables::eloquent($managers);
            $rs->addColumn(
                'avatar',
                function (SalonManager $manager){
                    $rs = getNoAvatarUrl();
                    if($manager->user->avatar_id){
                        $rs = $manager->user->avatar->getThumbnailUrl(
                            'default',
                            $rs);
                        if(!$rs){
                            $rs = getNoAvatarUrl();
                        }
                    }
                    return $rs;
                });
            $rs->filter(function ($query) use ($request){
                /** @var Builder $query */
                $keyword = $request->get('search')['value'];
                if(trim($keyword)){
                    $query->whereHas('user',function ($query) use ($request, $keyword){
                        /** @var Builder $query */
                        $query->whereNested(
                            function ($query) use($keyword) {
                                /** @var Builder $query */
                                $query->where('users.name','like', "%{$keyword}%");
                                $query->orWhere('users.email', 'like', "%{$keyword}%");
                            });
                    });
                }
            });
            $rs = $rs->make(true);
            return $rs;
        }
        return view('modhairworld::backend.pages.salon.edit.managers', [
            'salon' => $salon,
        ]);
    }

    public function destroyManager(Ajax $request, Salon $salon){
        $ids = $request->get('ids', []);
        $managers = SalonManager::whereSalonId($salon->id)->whereIn(
            'id',
            $ids)->get();
        foreach ($managers as $manager){
            $manager->delete();
        }
        return \Response::json();
    }

    function vn_str_filter ($str){
        $unicode = array(
            'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd'=>'đ',
            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i'=>'í|ì|ỉ|ĩ|ị',
            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
            'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D'=>'Đ',
            'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
            'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );

        foreach($unicode as $nonUnicode=>$uni){
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }
        return $str;
    }

    public function storeManager(SalonManagerStore $request, Salon $salon){
        $user_id = $request->get('user_id');
        $manager = new SalonManager();
        $manager->user_id = $user_id;
        $manager->salon_id = $salon->id;
        $manager->save();
        $user = $manager->user;
        if($user){
           try{
               $phone = $user->phone;
               $controller = new BrandSmsController();
               $message = 'Tai khoan cua ban duoc cap quyen de quan ly thong tin salon "'.$this->vn_str_filter($salon->name).'" tren ung dung iSalon Manager';
               $message .= "\nIOS APP:";
               $message .= "\nhttp://bit.ly/iSManger";
               $message .= "\nANDROID APP:";
               $message .= "\nhttp://bit.ly/iSManager";
               $controller->sendSms($phone, $message);
           }
           catch (\Exception $exception){

           }
        }
        return \Response::json();
    }

    public function editTime(Request $request, Salon $salon){
        $models = SalonOpenTime::whereSalonId($salon->id)->get();
        $times = [];
        foreach ($models as $model){
            $times[$model->weekday] = [
                'start' => date('H:i', strtotime($model->start)),
                'end' => date('H:i', strtotime($model->end)),
            ];
        }
        return view('modhairworld::backend.pages.salon.edit.time', [
            'salon' => $salon,
            'times' => $times
        ]);
    }

    public function storeTime(Ajax $request, Salon $salon){
        $days = $request->get('day', []);
        SalonOpenTime::whereSalonId($salon->id)->delete();
        foreach ($days as $k=>$day){
            if($day['start'] && $day['end']){
                $time = new SalonOpenTime();
                $time->salon_id = $salon->id;
                $time->weekday = $k;
                $time->start = $day['start'].':00';
                $time->end = $day['end'].':59';
                $time->save();
            }
        }
        return \Response::json();
    }

    public function editExtendedInfo(Request $request, Salon $salon){
        $extra_infos = SalonExtraInfo::whereSalonId($salon->id)->get(['title', 'icon', 'content'])->toArray();
        $extra_info_field = new FieldInputRepeater(
            'extra_infos',
            $extra_infos,
            '<small class="text-semibold">'.__('Danh sách thông tin mở rộng').'</small>',
            '',
            false,
            FieldInputRepeater::buildConfigs([
                                                 new FieldInputFontAwesome(
                                                     'icon',
                                                     '',
                                                     __('Biểu tượng'),
                                                     '',
                                                     true,
                                                     FieldInputFontAwesome::buildConfigs()
                                                 ),
                                                 new FieldInputText(
                                                     'title',
                                                     '',
                                                     __('Tiêu đề'),
                                                     '',
                                                     true,
                                                     FieldInputText::buildConfigs(__('Nhập tiêu đề thông tin'))
                                                 ),
                                                 new FieldInputTinyMCE(
                                                     'content',
                                                     '',
                                                     __('Nội dung thông tin'),
                                                     '',
                                                     true,
                                                     [
                                                         'menubar' => 0,
                                                         'height' => 200
                                                     ]
                                                 )
                                             ],__('Thêm thông tin'),__('Khối thông tin'))
        );
        return view('modhairworld::backend.pages.salon.edit.extended_info', [
            'salon' => $salon,
            'field' => $extra_info_field,
        ]);
    }

    public function updateExtendedInfo(SalonExtendedInfoUpdate $request, Salon $salon){
        $infos = $request->get('extra_infos', []);
        if($infos == null){
            $infos = [];
        }
        SalonExtraInfo::whereSalonId($salon->id)->delete();
        foreach ($infos as $info){
            $model = new SalonExtraInfo();
            $model->salon_id = $salon->id;
            $model->icon = $info['icon'];
            $model->title = $info['title'];
            $model->content = $info['content'];
            $model->save();
        }
        return \Response::json();
    }

    public function updateBasicInfo(SalonBasicInfoUpdate $request, Salon $salon){
        $salon->name = $request->get('name');
        $salon->certified = $request->get('certified');
        $salon->open = $request->get('open');
        $salon->address = $request->get('address');
        $salon->tinh_thanh_pho_id = $request->get('tinh_thanh_pho_id');
        $salon->quan_huyen_id = $request->get('quan_huyen_id');
        $salon->phuong_xa_thi_tran_id = $request->get('phuong_xa_thi_tran_id');
        $salon->map_lat = $request->get('lat');
        $salon->map_long = $request->get('lng');
        $salon->map_zoom = $request->get('zoom');
        $salon->info = $request->get('info');
        $salon->training_info = $request->get('training_info_field');
        $salon->cover_id = $request->get('cover_id', null);
        $salon->meta_keywords = $request->get('meta_keywords');
        $salon->save();
        return \Response::json($salon);
    }

    public function deleteSalon(Ajax $request, Salon $salon){
        $salon->delete();
        return \Response::json();
    }

    function cloneSalon(Ajax $request, Salon $salon){
        \Validator::validate(
            $request->all(),
            [
                'clone_salon_name' => ['required'],
                'clone_salon_manager_name' => ['required_with:clone_salon_create_manager', 'nullable'],
                'clone_salon_manager_phone' => [
                    'required_with:clone_salon_create_manager',
                    'numeric', 'unique:users,phone',
                    'nullable',
                    'digits:10'
                ],
                'clone_salon_manager_email' => [
                    'required_with:clone_salon_create_manager',
                    'email','unique:users,email',
                    'nullable'
                ],
                'clone_salon_manager_password' => [
                    'required_with:clone_salon_create_manager',
                    'min:6',
                    'nullable'
                ],
            ],
            [
                'clone_salon_name.required' => 'Tên salon không được bỏ trống',
                '*.required_with' => 'Vui lòng nhập thông tin này',
                'clone_salon_manager_phone.numeric' => 'Số điện thoại không hợp lệ',
                'clone_salon_manager_phone.digits' => 'Số điện thoại phải 10 số',
                'clone_salon_manager_phone.unique' => 'Số điện thoại đã tồn tại',
                'clone_salon_manager_email.email' => 'Email không hợp lệ',
                'clone_salon_manager_email.unique' => 'Email đã tồn tại',
                'clone_salon_manager_password.min' => 'Mật khẩu phải từ 6 ký tự nhe'
            ]
        );
        $create_manager = $request->has('clone_salon_create_manager');

        $clone_salon_name = $request->get('clone_salon_name');
        $clone_salon_cover = $request->get('clone_salon_cover');
        $clone_salon_status = $request->get('clone_salon_status');
        $clone_salon_verified = $request->get('clone_salon_verified');
        $clone_salon_address = $request->get('clone_salon_address');
        $clone_salon_desc = $request->get('clone_salon_desc');
        $clone_salon_gallery = $request->get('clone_salon_gallery');
        $clone_salon_times = $request->get('clone_salon_times');
        $clone_salon_stylists = $request->get('clone_salon_stylists');
        $clone_salon_showcases = $request->get('clone_salon_showcases');
        $clone_salon_brands = $request->get('clone_salon_brands');
        $clone_salon_services = $request->get('clone_salon_services');
        $clone_salon_sales = $request->get('clone_salon_sales');

        $clone_salon_manager_name = $request->get('clone_salon_manager_name');
        $clone_salon_manager_phone = $request->get('clone_salon_manager_phone');
        $clone_salon_manager_email = $request->get('clone_salon_manager_email');
        $clone_salon_manager_password = $request->get('clone_salon_manager_password');

        $new_salon = new Salon();
        $new_salon->name = $clone_salon_name;

        $new_salon->meta_keywords = $salon->meta_keywords;


        if($clone_salon_cover){
            $new_salon->cover_id = $salon->cover_id;
        }
        if($clone_salon_status){
            $new_salon->open = $salon->open;
        }
        if($clone_salon_verified){
            $new_salon->certified = $salon->certified;
        }
        if($clone_salon_address){
            $new_salon->address = $salon->address;
            $new_salon->tinh_thanh_pho_id = $salon->tinh_thanh_pho_id;
            $new_salon->quan_huyen_id = $salon->quan_huyen_id;
            $new_salon->phuong_xa_thi_tran_id = $salon->phuong_xa_thi_tran_id;
        }
        if($clone_salon_desc){
            $new_salon->info = $salon->info;
        }
        $new_salon->save();
        if($clone_salon_gallery){
            $salon->load('gallery');
            foreach ($salon->gallery as $item){
                /** @var SalonGallery $new_item */
                $new_item = $item->replicate();
                $new_item->salon_id = $new_salon->id;
                $new_item->push();
            }
        }
        if($clone_salon_times){
            $salon->load('times');
            foreach ($salon->times as $time){
                /** @var SalonOpenTime $new_time */
                $new_time = $time->replicate();
                $new_time->salon_id = $new_salon->id;
                $new_time->push();
            }
        }
        if($clone_salon_stylists){
            $salon->load('stylist');
            foreach ($salon->stylist as $item){
                /** @var SalonStylist $new_item */
                $new_item = $item->replicate();
                $new_item->salon_id = $new_salon->id;
                $new_item->push();
            }
        }
        if($clone_salon_showcases){
            $salon->load([
                'showcases',
                'showcases.items'
            ]);
            /** @var SalonShowcase $item */
            foreach ($salon->showcases as $item){
                /** @var SalonShowcase $new_item */
                $new_item = $item->replicate();
                $new_item->salon_id = $new_salon->id;
                $new_item->push();
                /** @var SalonShowcaseItem $sub_item */
                foreach ($item->items as $sub_item){
                    /** @var SalonShowcaseItem $new_sub_item */
                    $new_sub_item = $sub_item->replicate();
                    $new_sub_item->showcase_id = $new_item->id;
                    $new_sub_item->push();
                }
            }
        }
        if($clone_salon_brands){
            $salon->load('brands');
            foreach ($salon->brands as $item){
                /** @var SalonBrand $new_item */
                $new_item = $item->replicate();
                $new_item->salon_id = $new_salon->id;
                $new_item->push();
            }
        }
        if($clone_salon_services){
            $salon->load(['services', 'services.sale_off']);
            /** @var SalonService $item */
            foreach ($salon->services as $item){
                /** @var SalonService $new_item */
                $new_item = $item->replicate();
                $new_item->salon_id = $new_salon->id;
                $new_item->push();
                if($clone_salon_sales){
                    if($item->sale_off){
                        /** @var SalonServiceSale $new_sale */
                        $new_sale = $item->sale_off->replicate();
                        $new_sale->service_id = $new_item->id;
                        $new_sale->push();
                    }
                }
            }
        }

        if($create_manager){
            $manager = new User();
            $manager->role_id = 3;
            $manager->name = $clone_salon_manager_name;
            $manager->phone = $clone_salon_manager_phone;
            $manager->email = $clone_salon_manager_email;
            $manager->password = $clone_salon_manager_password;
            $manager->save();
            $salon_manager = new SalonManager();
            $salon_manager->salon_id = $new_salon->id;
            $salon_manager->user_id = $manager->id;
            $salon_manager->save();
        }
        $this->syncSalonWallet($new_salon->id);

        return \Response::json(route('backend.salon.basic_info.edit', ['salon'=>$new_salon]));
    }

    public function storeSalon(SalonBasicInfoUpdate $request){
        $salon = new Salon();
        $this->updateBasicInfo($request, $salon);
        $this->syncSalonWallet($salon->id);
        return \Response::json(route('backend.salon.basic_info.edit', ['salon'=>$salon]));
    }

    public function createSalon(Request $request){
        $tinh = null;
        $quan = null;
        $xa = null;
        $lat = 21.0227788;
        $lng = 105.8194541;
        $zoom = 13;
        $cover_field = new FieldInputFile(
            'cover_id',
            null,
            '<span class="text-semibold">'.__('Ảnh đại diện').'</span>',
            '',
            false,
            FieldInputFile::buildConfigs(
                __('Chọn ảnh đại diện'),
                'Chọn ảnh đại diện',
                ['salon_cover'],
                ['image']
            )
        );
        $info_field = new FieldInputTinyMCE(
            'info',
            null,
            __('Thông tin salon'),
            '',
            false,
            [
                'height' => 300,
                'menubar' => false,
            ]
        );

        $training_info_field = new FieldInputTinyMCE(
            'training_info',
            null,
            __('Thông tin đào tạo'),
            '',
            false,
            [
                'height' => 300,
                'menubar' => false,
            ]
        );

        $meta_keyword_field =      new FieldInputTextArea(
            'meta_keywords',
            '',
            'Meta keywords',
            '',
            false,
            FieldInputTextArea::buildConfigs('Nhập mỗi keyword một dòng',5)
        );

        return view('modhairworld::backend.pages.salon.edit.basic_info', [
            'salon' => null,
            'tinh' => $tinh,
            'quan' => $quan,
            'xa' => $xa,
            'lat' => $lat,
            'lng' => $lng,
            'zoom' => $zoom,
            'cover_field' => $cover_field,
            'info_field' => $info_field,
            'training_info_field' => $training_info_field,
            'meta_keyword_field' => $meta_keyword_field
        ]);
    }

    public function editBasicInfo(Request $request, Salon $salon)
    {
        $tinh = null;
        $quan = null;
        $xa = null;
        if($salon->tinh_thanh_pho_id){
            $tinh = DiaPhuongTinhThanhPho::find($salon->tinh_thanh_pho_id);
            if($salon->quan_huyen_id){
                $quan = DiaPhuongQuanHuyen::find($salon->quan_huyen_id);
                if($salon->phuong_xa_thi_tran_id){
                    $xa = DiaPhuongXaPhuongThiTran::find($salon->phuong_xa_thi_tran_id);
                }
            }
        }
        $lat = $salon&&$salon->map_lat?$salon->map_lat:21.0227788;
        $lng = $salon&&$salon->map_long?$salon->map_long:105.8194541;
        $zoom = $salon&&$salon->map_zoom?$salon->map_zoom:13;
        $info_field = new FieldInputTinyMCE(
            'info',
            $salon->info,
            __('Thông tin salon'),
            '',
            false,
            FieldInputTinyMCE::buildConfigs([
                'height' => 300,
                'menubar' => false,
                'toolbar2' => ''
            ])
        );
        $cover_field = new FieldInputFile(
            'cover_id',
            $salon->cover_id,
            '<span class="text-semibold">'.__('Ảnh đại diện').'</span>',
            '',
            false,
            FieldInputFile::buildConfigs(
                __('Chọn ảnh đại diện'),
                'Chọn ảnh đại diện',
                ['salon_cover'],
                ['image']
            )
        );
        $training_info_field  = new FieldInputTinyMCE(
            'training_info_field',
            $salon->training_info,
            __('Thông tin đào tạo'),
            '',
            false,
            FieldInputTinyMCE::buildConfigs([
                'height' => 300,
                'menubar' => false,
                'toolbar2' => ''
            ])
        );
        $meta_keyword_field =      new FieldInputTextArea(
            'meta_keywords',
            $salon->meta_keywords,
            'Meta keywords',
            '',
            false,
            FieldInputTextArea::buildConfigs('Nhập mỗi keyword một dòng',5)
        );
        return view('modhairworld::backend.pages.salon.edit.basic_info', [
            'salon' => $salon,
            'tinh' => $tinh,
            'quan' => $quan,
            'xa' => $xa,
            'lat' => $lat,
            'lng' => $lng,
            'zoom' => $zoom,
            'info_field' => $info_field,
            'cover_field' => $cover_field,
            'meta_keyword_field' => $meta_keyword_field,
            'training_info_field' => $training_info_field
        ]);
    }

    public function editStylist(Request $request, Salon $salon){
        if($request->ajax()){
            $models = SalonStylist::whereSalonId($salon->id);
            $rs = \DataTables::eloquent($models);
            $rs->addColumn(
                'avatar',
                function(SalonStylist $stylist){
                    /** @var Builder $query */
                    return $stylist->avatar?$stylist->avatar->getThumbnailUrl(
                        'default',
                        getNoAvatarUrl()):getNoAvatarUrl();
                });
            $rs = $rs->make(true);
            return $rs;
        }
        $avatar_field = new FieldInputFile(
            'avatar_id',
            '',
            __('Ảnh stylist'),
            '',
            false,
            FieldInputFile::buildConfigs(
                __('Chọn ảnh avatar stylist'),
                'Chọn ảnh avatar stylist',
                ['stylist_avatar'],
                ['image']
            )
            );
        return view('modhairworld::backend.pages.salon.edit.stylist', [
            'salon' => $salon,
            'avatar_field' => $avatar_field
        ]);
    }

    public function destroyStylist(Ajax $request, Salon $salon){
        $ids = $request->get('ids', []);
        $stylists = SalonStylist::whereSalonId($salon->id)->whereIn(
            'id',
            $ids)->get();
        foreach ($stylists as $stylist){
            $stylist->delete();
        }
        return \Response::json($ids);
    }

    public function storeStylist(SalonStylistStoreUpdate $request, Salon $salon){
        $stylist = new SalonStylist();
        $stylist->name = $request->get('name');
        $stylist->avatar_id = $request->get('avatar_id', null);
        $stylist->salon_id = $salon->id;
        $stylist->save();
        return \Response::json();
    }

    public function updateStylist(SalonStylistStoreUpdate $request, Salon $salon){
        $id = $request->get('id');
        if($id){
            $stylist = SalonStylist::whereSalonId($salon->id)->where('id', $id)->first();
            if($stylist){
                $stylist->name = $request->get('name');
                $stylist->avatar_id = $request->get('avatar_id', null);
                $stylist->salon_id = $salon->id;
                $stylist->save();
            }
        }
        return \Response::json();
    }

    public function editGallery(Request $request, Salon $salon){
        if($request->ajax()){
            $models = SalonGallery::whereSalonId($salon->id);
            $rs = \DataTables::eloquent($models);
            $rs->addColumn(
                'avatar',
                function(SalonGallery $gallery){
                    /** @var Builder $query */
                    return $gallery->image?$gallery->image->getThumbnailUrl(
                        'default',
                        getNoThumbnailUrl()):getNoThumbnailUrl();
                });
            $rs = $rs->make(true);
            return $rs;
        }
//        $avatar_field = new FieldInputFile(
//            'image_id',
//            '',
//            __('Ảnh giới thiệu'),
//            '',
//            false,
//            FieldInputFile::buildConfigs(
//                __('Ảnh giới thiệu'),
//                'Ảnh giới thiệu',
//                ['gallery'],
//                ['image']
//            )
//        );
        $avatar_field = new FieldInputFileList(
            'image_ids',
            [],
            __('Ảnh giới thiệu'),
            '',
            false,
            FieldInputFileList::buildConfigs(
                __('Ảnh giới thiệu'),
                'Ảnh giới thiệu',
                ['gallery'],
                ['image']
            )
            );
        return view('modhairworld::backend.pages.salon.edit.gallery', [
            'salon' => $salon,
            'avatar_field' => $avatar_field
        ]);
    }

    public function storeGallery(SalonGalleryStoreUpdate $request, Salon $salon){
        $image_ids = $request->get('image_ids');
        foreach ($image_ids as $image_id){
            $image = new SalonGallery();
            $image->salon_id = $salon->id;
            $image->image_id = $image_id;
            $image->save();
        }
        return \Response::json();
    }

    public function updateGallery(SalonGalleryStoreUpdate $request, Salon $salon){
        $id = $request->get('id');
        $image_id = $request->get('image_id');
        /** @var SalonGallery $image */
        $image = SalonGallery::whereSalonId($salon->id)->where('id', $id)->first();
        if($image){
            $image->image_id = $image_id;
            $image->save();
        }
        return \Response::json();
    }

    public function destroyGallery(Ajax $request, Salon $salon){
        $ids = $request->get('ids');
        $images = SalonGallery::whereSalonId($salon->id)->whereIn('id', $ids)->get();
        foreach ($images as $image){
            $image->delete();
        }
        return \Response::json();
    }

    public function editBrand(Request $request, Salon $salon){
        if($request->ajax()){
            $models = SalonBrand::whereSalonId($salon->id);
            $rs = \DataTables::eloquent($models);
            $rs->addColumn(
                'avatar',
                function(SalonBrand $brand){
                    /** @var Builder $query */
                    return $brand->logo?$brand->logo->getThumbnailUrl(
                        'default',
                        getNoThumbnailUrl()):getNoThumbnailUrl();
                });
            $rs = $rs->make(true);
            return $rs;
        }
        $avatar_field = new FieldInputFile(
            'logo_id',
            '',
            __('Logo thương hiệu'),
            '',
            false,
            FieldInputFile::buildConfigs(
                __('Chọn logo thương hiệu'),
                'Chọn logo thương hiệu',
                ['brand_logo'],
                ['image']
            )
        );
        return view('modhairworld::backend.pages.salon.edit.brands', [
            'salon' => $salon,
            'avatar_field' => $avatar_field
        ]);
    }

    public function destroyBrand(Ajax $request, Salon $salon){
        $ids = $request->get('ids', []);
        $brands = SalonBrand::whereSalonId($salon->id)->whereIn(
            'id',
            $ids)->get();
        foreach ($brands as $brand){
            $brand->delete();
        }
        return \Response::json();
    }

    public function storeBrand(SalonBrandStoreUpdate $request, Salon $salon){
        $logo_id = $request->get('logo_id');
        $brand = new SalonBrand();
        $brand->salon_id = $salon->id;
        $brand->logo_id = $logo_id;
        $brand->save();
        return \Response::json();
    }

    public function updateBrand(SalonBrandStoreUpdate $request, Salon $salon){
        $id = $request->get('id');
        if($id){
            $brand = SalonBrand::whereSalonId($salon->id)->where('id', $id)->first();
            if($brand){
                $brand->logo_id = $request->get('logo_id');
                $brand->save();
            }
        }
        return \Response::json();
    }

    //
    public function editShowcase(Request $request, Salon $salon){
        if($request->ajax()){
            $models = SalonShowcase::whereSalonId($salon->id)->with(['items'=>function($query){
                $query->orderBy('id', 'asc');
            }])->withCount(['items']);
            $rs = \DataTables::eloquent($models);
            $rs->addColumn(
                'cover',
                function(SalonShowcase $showcase){
                    /** @var SalonShowcaseItem $first */
                    $first = $showcase->items->first();
                    if($first){
                        $rs = $first->image?$first->image->getThumbnailUrl('default',getNoThumbnailUrl()):getNoThumbnailUrl();
                    }
                    else{
                        $rs = getNoThumbnailUrl();
                    }
                    return $rs;
                });
            $rs = $rs->make(true);
            return $rs;
        }
        $avatar_field = new FieldInputFileList(
            'items',
            [],
            __('Danh sách ảnh'),
            '',
            false,
            FieldInputFileList::buildConfigs(
                __('Thêm ảnh tác phẩm'),
                'Thêm ảnh tác phẩm',
                ['showcase_item'],
                ['image']
            )
        );
        return view('modhairworld::backend.pages.salon.edit.showcase', [
            'salon' => $salon,
            'avatar_field' => $avatar_field
        ]);
    }

    public function destroyShowcase(Ajax $request, Salon $salon){
        $ids = $request->get('ids', []);
        $showcases = SalonShowcase::whereSalonId($salon->id)->whereIn('id', $ids)->get();
        foreach ($showcases as $showcase){
            $showcase->delete();
        }
        return \Response::json();
    }

    public function storeShowcase(SalonShowcaseStoreUpdate $request, Salon $salon){
        $name = $request->get('name');
        $items = $request->get('items',[]);
        $showcase = new SalonShowcase();
        $showcase->name = $name;
        $showcase->salon_id = $salon->id;
        $showcase->save();
        foreach ($items as $item){
            $showcase_item = new SalonShowcaseItem();
            $showcase_item->image_id = $item;
            $showcase_item->showcase_id = $showcase->id;
            $showcase_item->save();
        }
        return \Response::json();
    }

    public function updateShowcase(SalonShowcaseStoreUpdate $request, Salon $salon){
        $id = $request->get('id');
        $showcase = SalonShowcase::whereSalonId($salon->id)->where('id', $id)
            ->first();
        if($showcase){
            $showcase->items()->delete();
            $name = $request->get('name');
            $items = $request->get('items',[]);
            $showcase->name = $name;
            $showcase->save();
            foreach ($items as $item){
                $showcase_item = new SalonShowcaseItem();
                $showcase_item->image_id = $item;
                $showcase_item->showcase_id = $showcase->id;
                $showcase_item->save();
            }
        }
        return \Response::json($showcase);
    }

    public function editService(Request $request, Salon $salon){
        if($request->ajax()){
            $models = SalonService::whereSalonId($salon->id)
            ->with(['cover', 'category', 'logos', 'images', 'options','included_options'])
            ;
            $rs = \DataTables::eloquent($models);
            $rs->addColumn(
                'cover',
                function(SalonService $service){
                    /** @var Builder $query */
                    return $service->cover?$service->cover->getThumbnailUrl(
                        'default',
                        getNoThumbnailUrl()):getNoThumbnailUrl();
                });
            $rs->addColumn(
                'cat',
                function(SalonService $service){
                    /** @var Builder $query */
                    return $service->category?$service->category->title:__('Chưa phân loại');
                });
            $rs->addColumn(
                'price_format',
                function(SalonService $service){
                    /** @var Builder $query */
                    if(!$service->options->count()){
                        return number_format($service->price/1000.0,0,'.','.').'K';
                    }
                    else{
                        $min = $service->options->min('price');
                        $max = $service->options->max('price');
                        if($max == $min){
                            return number_format($max/1000.0,0,'.','.').'K';
                        }
                        else{
                            return number_format($min/1000.0,0,'.','.').'K' . ' - ' . number_format($max/1000.0,0,'.','.').'K';
                        }
                    }
                });
            $rs = $rs->make(true);
            return $rs;
        }

        $option_field = new FieldInputRepeater(
          'options',
          [],
          'Các tuỳ chọn',
          '',
          false,
          FieldInputRepeater::buildConfigs([
            new FieldInputText(
                'name',
                '',
                'Tên',
                '',
                true,
                []
            ),
            new FieldInputTouchSpin(
                'price',
                10000,
                'Giá',
                '',
                true,
                [
                    'postfix' => 'VND',
                    'min' => 0,
                    'max' => 1000000000
                ]
            )
          ])
        );

        $included_option_field = new FieldInputRepeater(
            'included',
            [],
            'Sản phẩm dịch vụ đính kèm',
            '',
            false,
            FieldInputRepeater::buildConfigs([
                new FieldInputText(
                    'name',
                    '',
                    'Tên',
                    '',
                    true,
                    []
                ),
                new FieldInputTouchSpin(
                    'price',
                    10000,
                    'Giá',
                    '',
                    true,
                    [
                        'postfix' => 'VND',
                        'min' => 0,
                        'max' => 1000000000
                    ]
                )
            ])
        );

        $avatar_field = new FieldInputFile(
            'cover_id',
            '',
            __('Ảnh đại diện dịch vụ'),
            '',
            false,
            FieldInputFile::buildConfigs(
                __('Chọn đại diện dịch vụ'),
                'Chọn đại diện dịch vụ',
                ['service_cover'],
                ['image']
            )
        );
        $logo_list = new FieldInputFileList(
            'logos',
            [],
            'Ảnh logo thương hiệu sử dụng',
            '',
            false,
            FieldInputFileList::buildConfigs('Logo thương hiệu', 'Thêm logo', ['service_logo'], ['image'])
        );
//        $image_list =new FieldInputRepeater(
//            'images',
//            [],
//            'Hình ảnh dịch vụ',
//            '',
//            false,
//            FieldInputRepeater::buildConfigs([
//                new FieldInputFile(
//                    'image',
//                    '',
//                    __('Hình ảnh dịch vụ'),
//                    '',
//                    false,
//                    FieldInputFile::buildConfigs(
//                        __('Hình ảnh dịch vụ'),
//                        'Chọn ảnh dịch vụ',
//                        ['service_images'],
//                        ['image']
//                    )
//                )
//            ])
//        );
        $image_list = new FieldInputFileList(
            'images',
            [],
            'Hình ảnh dịch vụ',
            '',
            false,
            FieldInputFileList::buildConfigs('Hình ảnh dịch vụ', 'Thêm hình', ['service_images'], ['image'])
        );
        $time_from_field = new FieldInputTouchSpin(
            'time_from',
            30,
            '<span class="text-semibold">'.__('Thời gian tối thiểu').'</span>',
            '',
            true,
            [
                'postfix' => 'Phút',
                'max' => '999'
            ]
        );
        $time_to_field = new FieldInputTouchSpin(
            'time_to',
            30,
            '<span class="text-semibold">'.__('Thời gian tối đa').'</span>',
            '',
            true,
            [
                'postfix' => 'Phút',
                'max' => '999'
            ]
        );
        $price_field = new FieldInputTouchSpin(
            'price',
            100000,
            '<span class="text-semibold">'.__('Giá dịch vụ').'</span>',
            '',
            true,
            [
                'postfix' => 'VND',
                'max' => '1000000000'
            ]
        );
        $color_field = new FieldInputColor(
            'color',
            '#F2F2F2',
            '<span class="text-semibold">'.__('Đánh dấu màu nền').'</span>',
            '',
            true,
            FieldInputColor::buildConfigs()
        );
        $text_color_field = new FieldInputColor(
            'text_color',
            '#21232C',
            '<span class="text-semibold">'.__('Đánh dấu màu chữ').'</span>',
            '',
            true,
            FieldInputColor::buildConfigs()
        );
        $tiny_cfg = FieldInputTinyMCE::buildConfigs(
            [
                'height' => 200,
            ],
            'service_description'
        );
        $tiny_cfg['wa_image_insert']['categories'] = ['service_description' ];
        $tiny_cfg['wa_link_insert']['categories'] = ['service_description' ];
        $tiny_cfg['wa_media_insert']['categories'] = ['service_description' ];


        $description_field = new FieldInputTinyMCE(
            'description',
            '',
            '<span class="text-semibold">'.__('Mô tả dịch vụ').'</span>',
            '',
            true,
            $tiny_cfg
        );
        $service_cats = SalonServiceCategory::all();
        return view('modhairworld::backend.pages.salon.edit.service', [
            'salon' => $salon,
            'avatar_field' => $avatar_field,
            'price_field' => $price_field,
            'service_cats' => $service_cats,
            'description_field' => $description_field,
            'time_from_field' => $time_from_field,
            'time_to_field' => $time_to_field,
            'color_field' => $color_field,
            'text_color_field' => $text_color_field,
            'logo_field' => $logo_list,
            'image_field' => $image_list,
            'option_field' => $option_field,
            'included_option_field' => $included_option_field
        ]);
    }

    public function storeService(SalonServiceStoreUpdate $request, Salon $salon){
        $name = $request->get('name');
        $category_id = $request->get('service_cat_id');
        $price = $request->get('price');
        $description = $request->get('description');
        $time_from = $request->get('time_from');
        $time_to = $request->get('time_to');
        $cover_id = $request->get('cover_id');
        $color = $request->get('color');
        $text_color = $request->get('text_color');
        $service = new SalonService();
        $service->salon_id  = $salon->id;
        $service->name = $name;
        $service->category_id = $category_id;
        $service->price = $price;
        $service->description = $description;
        $service->time_from = $time_from;
        $service->time_to = $time_to;
        $service->cover_id = $cover_id;
        $service->color = $color;
        $service->text_color = $text_color;
        $service->save();
        $logos = $request->get('logos');
        if($logos){
            foreach ($logos as $logo){
                $new_logo = new SalonServiceLogo();
                $new_logo->service_id = $service->id;
                $new_logo->logo_id = $logo;
                $new_logo->save();
            }
        }
        $images = $request->get('images');
        if($images){
            foreach ($images as $image){
                $new_image = new SalonServiceImage();
                $new_image->service_id = $service->id;
                $new_image->image_id = $image;
                $new_image->save();
            }
        }
        return \Response::json($request->all());
    }

    public function updateService(SalonServiceStoreUpdate $request, Salon $salon){
        $name = $request->get('name');
        $category_id = $request->get('service_cat_id');
        $price = $request->get('price');
        $description = $request->get('description');
        $time_from = $request->get('time_from');
        $time_to = $request->get('time_to');
        $cover_id = $request->get('cover_id');
        $id = $request->get('id');
        $color = $request->get('color');
        $text_color = $request->get('text_color');
        $service = SalonService::whereSalonId($salon->id)
            ->where('id', $id)
            ->first();
        if($service){
            $service->salon_id  = $salon->id;
            $service->name = $name;
            $service->category_id = $category_id;
            $service->price = $price;
            $service->description = $description;
            $service->time_from = $time_from;
            $service->time_to = $time_to;
            $service->cover_id = $cover_id;
            $service->color = $color;
            $service->text_color = $text_color;
            $service->save();
        }
        $service->logos()->delete();
        $logos = $request->get('logos');
        if($logos){
            foreach ($logos as $logo){
                $new_logo = new SalonServiceLogo();
                $new_logo->service_id = $service->id;
                $new_logo->logo_id = $logo;
                $new_logo->save();
            }
        }
        $service->images()->delete();
        $images = $request->get('images');
        if($images){
            foreach ($images as $image){
                $new_image = new SalonServiceImage();
                $new_image->service_id = $service->id;
                $new_image->image_id = $image;
                $new_image->save();
            }
        }
        return \Response::json();
    }

    public function updateServiceOptions(Ajax $request, Salon $salon, SalonService $service){
        \Validator::validate($request->all(), [
            'options.*.name' => ['required'],
            'options.*.price' => ['required', 'numeric', 'min:0']
        ], [
            'options.*.name.required' => 'Tên tuỳ chọn không được bỏ trống',
            'options.*.price.required' => 'Giá tuỳ chọn không hợp lệ',
            'options.*.price.numeric' => 'Giá tuỳ chọn không hợp lệ',
            'options.*.price.min' => 'Giá tuỳ chọn không hợp lệ',
        ]);

        $options = $request->get('options', []);

        $service->options()->delete();

        if($options){
            foreach ($options as $option){
                $new_option = new SalonServiceOption();
                $new_option->service_id = $service->id;
                $new_option->name = $option['name'];
                $new_option->price = $option['price'];
                $new_option->save();
            }
        }

        return \Response::json($request->all());
    }

    public function updateServiceIncludedOptions(Ajax $request, Salon $salon, SalonService $service){
        \Validator::validate($request->all(), [
            'included.*.name' => ['required'],
            'included.*.price' => ['required', 'numeric', 'min:0']
        ], [
            'included.*.name.required' => 'Tên tuỳ chọn không được bỏ trống',
            'included.*.price.required' => 'Giá tuỳ chọn không hợp lệ',
            'included.*.price.numeric' => 'Giá tuỳ chọn không hợp lệ',
            'included.*.price.min' => 'Giá tuỳ chọn không hợp lệ',
        ]);

        $included_options = $request->get('included', []);

        $service->included_options()->delete();

        if($included_options){
            foreach ($included_options as $option){
                $new_option = new SalonServiceIncludedOption();
                $new_option->service_id = $service->id;
                $new_option->name = $option['name'];
                $new_option->price = $option['price'];
                $new_option->save();
            }
        }

        return \Response::json($request->all());
    }

    public function destroyService(Ajax $request, Salon $salon){
        $ids = $request->get('ids', []);
        $services = SalonService::whereSalonId($salon->id)->whereIn(
            'id',
            $ids)->get();
        foreach ($services as $service){
            $service->delete();
        }
        return \Response::json(1);
    }

    public function editSales(Request $request, Salon $salon){
        if($request->ajax()){
            $models = $salon->saleServices()
                ->with(['service', 'service.options'])
                ->getQuery()
                ->select(
                [
                    'service_sales.id',
                    'service_sales.service_id',
                    'service_sales.sale_type',
                    'service_sales.sale_amount',
                    'service_sales.sale_percent',
                    'service_sales.description'
                ]
            );
            $rs = \DataTables::eloquent($models);

            $rs->addColumn('price_html', function (SalonServiceSale $sale){
                $options = $sale->service->options;
                if($options->count()){
                    $min = $options->min('price');
                    $max = $options->max('price');
                    if($min == $max){
                        return number_format($min/1000.0,0,'.','.').'K';
                    }
                    else{
                        return number_format($min/1000.0,0,'.','.').'K'.' - '.number_format($max/1000.0,0,'.','.').'K';
                    }
                }
                else{
                    return number_format($sale->service->price/1000.0,0,'.','.').'K';
                }
            });
            $rs->addColumn('sale_price_html', function (SalonServiceSale $sale){
                $options = $sale->service->options;
                if($options->count()){
                    $min = $options->min('price');
                    $max = $options->max('price');
                    if($min == $max){
                        return number_format($sale->applySale($min)/1000.0,0,'.','.').'K';
                    }
                    else{
                        return number_format($sale->applySale($min)/1000.0,0,'.','.').'K'.' - '.number_format($sale->applySale($max)/1000.0,0,'.','.').'K';
                    }
                }
                else{
                    return number_format($sale->applySale($sale->service->price)/1000.0,0,'.','.').'K';
                }

            });
            $rs->addColumn('sale_amount_desc', function (SalonServiceSale $sale){
                if($sale->sale_type == 1){
                    return number_format($sale->sale_amount/1000.0,0,'.','.').'K';
                }
                else{
                    return $sale->sale_percent.'%';
                }
            });
            $rs = $rs->make(true);
            return $rs;
        }

        $sale_amount_field = new FieldInputTouchSpin(
            'sale_amount',
            10000,
            '<span class="text-semibold">'.__('Số tiền giảm').'</span>',
            '',
            true,
            [
                'postfix' => 'VND',
                'min' => 0,
                'max' => 1000000000
            ]
        );
        $sale_percent_field = new FieldInputTouchSpin(
            'sale_percent',
            5,
            '<span class="text-semibold">'.__('Số % giảm').'</span>',
            '',
            true,
            [
                'postfix' => '%',
                'min' => 0,
                'max' => 100
            ]
        );
        /** @var SalonService[] $services */
        $services = SalonService::whereSalonId($salon->id)->with(['category', 'options'])->get();
        $service_groups = [];
        foreach ($services as $service){
            if(!isset($service_groups[$service->category->id])){
                $service_groups[$service->category->id] = [
                    'name' => $service->category->title,
                    'services' => [],
                ];
            }
            $service_groups[$service->category->id]['services'][] = $service;
        }

        return view('modhairworld::backend.pages.salon.edit.sales', [
            'salon' => $salon,
            'service_groups' => $service_groups,
            'sale_amount_field' => $sale_amount_field,
            'sale_percent_field' => $sale_percent_field
        ]);
    }

    public function storeSales(SalonServiceSaleStoreUpdate $request, Salon $salon){
        $service_id = $request->get('service_id');
        $amount = $request->get('sale_amount');
        $description = $request->get('description');
        $type = $request->get('sale_type', 1);
        $cat = $request->get('sale_cat');
        $amount_type = $request->get('amount_type', 1);
        $sale_percent = $request->get('sale_percent');
        if($type == 1){
            $salon->saleServices()->where('service_id',$service_id)->delete();
            /** @var SalonService $service */
            $service =  $salon->services()->where('salon_services.id', $service_id)->first();
//            if($amount > $service->price){
//                $amount = $service->price;
//            }
            if($service){
                $sale = new SalonServiceSale();
                $sale->service_id = $service_id;
                $sale->sale_amount = $amount;
                $sale->sale_percent = $sale_percent;
                $sale->sale_type = $amount_type;
                $sale->description = $description;
                $sale->save();
            }
        }
        else if($type == 2 && $cat){
            $salon->load(['services'=>function($query) use($cat){
                $query->where('category_id', $cat);
            }, 'services.sale_off']);
            /** @var SalonService $service */
            foreach ($salon->services as $service){
                if($service->sale_off){
                    $service->sale_off->delete();
                }
            }
            $services = $salon->services()->where('category_id', $cat)->get();
            foreach ($services as $service){
                $sale = new SalonServiceSale();
                $sale->service_id = $service->id;
                $sale->sale_type = $amount_type;
                $sale->sale_percent = $sale_percent;
                $sale->sale_amount = $amount;
                $sale->description = $description;
                $sale->save();
            }
        }
        else if($type == 3){
            $salon->saleServices()->delete();
            $salon->load(['services']);
            foreach ($salon->services as $service){
                $sale = new SalonServiceSale();
                $sale->service_id = $service->id;
                $sale->sale_amount = $amount;
                $sale->sale_type = $amount_type;
                $sale->sale_percent = $sale_percent;
                $sale->description = $description;
                $sale->save();
            }
        }
        return \Response::json();
    }

    public function updateSales(SalonServiceSaleStoreUpdate $request, Salon $salon){
        $id = $request->get('id');
        $sale_amount = $request->get('sale_amount');
        $description = $request->get('description');
        $amount_type = $request->get('amount_type', 1);
        $sale_percent = $request->get('sale_percent');
        /** @var SalonServiceSale $sale */
        $sale = $salon->saleServices()->where('service_sales.id', $id)->first();
        if($sale){
            $sale->sale_amount = $sale_amount;
            $sale->sale_percent = $sale_percent;
            $sale->sale_type = $amount_type;
            $sale->description = $description;
            $sale->save();
        }
        return \Response::json();
    }

    public function destroySales(Ajax $request, Salon $salon){
        $ids = $request->get('ids', []);
        $services = $salon->saleServices()->whereIn('service_sales.id', $ids)->get();
        foreach ($services as $service){
            $service->delete();
        }
        return \Response::json();
    }

    public function destroySalesAvd(Ajax $request, Salon $salon){
        $id = $request->get('remove_sale_type', -1);
        if($id == -1){
            foreach ($salon->services as $service){
                if($service->sale_off){
                    $service->sale_off->delete();
                }
            }
        }
        else{
            $salon->load(['services'=> function($query) use ($id){
                $query->where('category_id', $id);
            }, 'services.sale_off']);
            foreach ($salon->services as $service){
                if($service->sale_off){
                    $service->sale_off->delete();
                }
            }
        }
        return \Response::json();
    }
}
