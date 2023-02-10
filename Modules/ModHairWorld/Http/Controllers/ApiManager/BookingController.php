<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 8/23/18
 * Time: 23:06
 */

namespace Modules\ModHairWorld\Http\Controllers\ApiManager;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\ModHairWorld\Entities\Salon;
use Modules\ModHairWorld\Entities\SalonOrder;
use Modules\ModHairWorld\Entities\SalonOrderChangeTimeRequest;
use Modules\ModHairWorld\Entities\SalonOrderItem;
use Modules\ModHairWorld\Events\SalonOrderProcessed;
use Modules\ModHairWorld\Notifications\CommonNotify;
use Modules\ModHairWorld\Handlers\SalonWalletHandler;

/**
 * @resource Đặt chỗ
 *
 * Những request này yêu cầu token ở header
 */
class BookingController extends Controller
{
    use SalonWalletHandler;

    public static function getNextMayDoneBooking(Salon $salon)
    {
        $salon_id = $salon->id;
        $salon_order_query =
            SalonOrder::where('service_time', '<=', \Carbon\Carbon::now())
                ->with(['user', 'user.avatar', 'items'])
                ->has('user')
                ->orderBy('service_time', 'asc')
                ->orderBy('created_at', 'asc')
                ->where(['salon_id' => $salon_id, 'status' => SalonOrder::_CHO_THUC_HIEN_]);
        $salon_order = (clone $salon_order_query)->first();
        $salon_order_count = (clone $salon_order_query)->count();
        $data = false;
        if ($salon_order) {
            $data['id'] = $salon_order->id;
            $data['total_count'] = $salon_order_count;
            $data['amount_coin'] = $salon_order->amount_coin;
            $data['amount_money'] = $salon_order->amount_money;
            $user_info = $salon_order->user;
            if ($user_info) {
                $cover = $user_info->avatar;
                $data['customer']['id'] = $user_info->id;
                $data['customer']['name'] = $user_info->name;
                $data['customer']['rating'] = 0.0;
                $data['customer']['phone'] = $user_info->phone;
                $data['customer']['avatar'] = $cover ? $cover->getThumbnailUrl('default', getNoThumbnailUrl()) : getNoThumbnailUrl();
            }
            $data['date'] = date_format($salon_order->service_time, "d/m/Y");
            $data['time'] = date_format($salon_order->service_time, "H:i");
            $services = $salon_order->items->map(function ($item) {
                return [
                    'id' => $item->service_id,
                    'name' => $item->service_name,
                    'qty' => $item->quatity,
                    'sum' => $item->quatity * $item->price
                ];
            });
            $data['services'] = $services;
        }
        return $data;
    }

    /**
     * @param Salon $salon
     * @return bool
     */
    public static function getNewBooking(Salon $salon)
    {
        $salon_id = $salon->id;
        $salon_order_query = SalonOrder::where('service_time', '>', \Carbon\Carbon::now())
            ->with(['user', 'user.avatar', 'items'])
            ->has('user')
            ->orderBy('created_at', 'asc')
            ->where(['salon_id' => $salon_id, 'status' => SalonOrder::_CHO_XU_LY_]);
        $salon_order =
            (clone $salon_order_query)->first();
        $salon_order_count = (clone $salon_order_query)->count();
        $data = false;
        if ($salon_order) {
            $data['total_count'] = $salon_order_count;
            $data['id'] = $salon_order->id;
            $data['amount_coin'] = $salon_order->amount_coin;
            $data['amount_money'] = $salon_order->amount_money;
            $user_info = $salon_order->user;
            if ($user_info) {
                $cover = $user_info->avatar;
                $data['customer']['id'] = $user_info->id;
                $data['customer']['name'] = $user_info->name;
                $data['customer']['rating'] = 0.0;
                $data['customer']['phone'] = $user_info->phone;
                $data['customer']['avatar'] = $cover ? $cover->getThumbnailUrl('default', getNoThumbnailUrl()) : getNoThumbnailUrl();
            }
            $data['date'] = date_format($salon_order->service_time, "d/m/Y");
            $data['time'] = date_format($salon_order->service_time, "H:i");
            $services = $salon_order->items->map(function ($item) {
                return [
                    'id' => $item->service_id,
                    'name' => $item->service_name,
                    'qty' => $item->quatity,
                    'sum' => $item->quatity * $item->price
                ];
            });
            $data['services'] = $services;
        }
        return $data;
    }

    /**
     * @param Salon $salon
     * @return bool
     */
    public static function getNextWaitingBooking(Salon $salon)
    {
        $salon_id = $salon->id;
        $salon_order =
            SalonOrder::where('service_time', '>', \Carbon\Carbon::now())
                ->with(['user', 'user.avatar', 'items'])
                ->has('user')
                ->orderBy('service_time', 'asc')
                ->orderBy('created_at', 'asc')
                ->where(['salon_id' => $salon_id, 'status' => SalonOrder::_CHO_THUC_HIEN_])->first();
        $data = false;
        if ($salon_order) {
            $user_info = $salon_order->user;
            if ($user_info) {
                $cover = $user_info->avatar;
                $data['customer']['id'] = $user_info->id;
                $data['customer']['name'] = $user_info->name;
                $data['customer']['rating'] = 0.0;
                $data['customer']['phone'] = $user_info->phone;
                $data['customer']['avatar'] = $cover ? $cover->getThumbnailUrl('default', getNoThumbnailUrl()) : getNoThumbnailUrl();
            }
            $data['date'] = date_format($salon_order->service_time, "d/m/Y");
            $data['time'] = date_format($salon_order->service_time, "H:i");
            $data['id'] = $salon_order->id;
            $data['amount_coin'] = $salon_order->amount_coin;
            $data['amount_money'] = $salon_order->amount_money;
            $services = $salon_order->items->map(function ($item) {
                return [
                    'id' => $item->service_id,
                    'name' => $item->service_name,
                    'qty' => $item->quatity,
                    'sum' => $item->quatity * $item->price
                ];
            });
            $data['services'] = $services;
        }
        return $data;
    }

    function cancelNewBooking(Request $request, Salon $salon){
        $salon_id = $salon->id;
        $id = $request->get('id');
        $note = $request->get('note', 'Lý do riêng tư');
        $salon_order = SalonOrder
            ::where('service_time','>', \Carbon\Carbon::now())
            ->where('id', $id)
            ->has('user')
            ->orderBy('created_at', 'asc')
            ->where(['salon_id' => $salon_id, 'status' => SalonOrder::_CHO_XU_LY_])->first();
        if(!$salon_order){
            abort(422,'Đơn đặt chỗ không còn hoặc quá hạn để xử lý');
        }
        $salon_order->status = SalonOrder::_HUY_BOI_SALON_;
        $salon_order->note = $note;
        $this->updatePaymentStatus($salon_order, false);
        $salon_order->save();
        return \Response::json(true);
    }

    function finish(Request $request, Salon $salon){
        $salon_id = $salon->id;
        $id = $request->get('id');
        $salon_order = SalonOrder
            ::where('service_time','<=', \Carbon\Carbon::now())
            ->where('id', $id)
            ->has('user')
            ->where(['salon_id' => $salon_id, 'status' => SalonOrder::_CHO_THUC_HIEN_])->first();
        if(!$salon_order){
            abort(422,'Đơn đặt chỗ không hợp lệ');
        }
        $salon_order->status = SalonOrder::_DA_HOAN_THANH_;
        $this->updatePaymentStatus($salon_order, true);
        $salon_order->save();
        return \Response::json(true);
    }

    function notCome(Request $request, Salon $salon){
        $salon_id = $salon->id;
        $id = $request->get('id');
        $salon_order = SalonOrder
            ::where('service_time','<=', \Carbon\Carbon::now())
            ->where('id', $id)
            ->has('user')
            ->where(['salon_id' => $salon_id, 'status' => SalonOrder::_CHO_THUC_HIEN_])->first();
        if(!$salon_order){
            abort(422,'Đơn đặt chỗ không hợp lệ');
        }
        $salon_order->status = SalonOrder::_KHACH_KHONG_DEN_;
        $this->updatePaymentStatus($salon_order, false);
        $salon_order->save();
        return \Response::json(true);
    }

    function acceptNewBooking(Request $request, Salon $salon){
        $salon_id = $salon->id;
        $id = $request->get('id');
        $salon_order = SalonOrder
            ::where('service_time','>', \Carbon\Carbon::now())
            ->where('id', $id)
            ->has('user')
            ->orderBy('created_at', 'asc')
            ->where(['salon_id' => $salon_id, 'status' => SalonOrder::_CHO_XU_LY_])->first();
        if(!$salon_order){
            abort(422,'Đơn đặt chỗ không còn hoặc quá hạn để xử lý');
        }
        if($salon_order->payment_method == 'salon'){
            $salon_order->status = SalonOrder::_CHO_THUC_HIEN_;
        }
        else{
            $salon_order->status = SalonOrder::_CHO_THANH_TOAN_;
        }
        $salon_order->save();
        return \Response::json(true);
    }

    function newBookingDetail(Request $request, Salon $salon){
        $salon_id = $salon->id;
        $id = $request->get('id');
        $salon_order = SalonOrder
            ::where('service_time','>', \Carbon\Carbon::now())
            ->where('id', $id)
            ->with(['user', 'user.avatar', 'items'])
            ->has('user')
            ->orderBy('created_at', 'asc')
            ->where(['salon_id' => $salon_id, 'status' => SalonOrder::_CHO_XU_LY_])->first();
        if(!$salon_order){
            abort(422,'Đơn đặt chỗ không còn hoặc quá hạn để xử lý');
        }
        $data = false;
        if($salon_order){
            $data['id'] = $salon_order->id;
            $timeout = SalonOrder::getProcessTimeOut();
            $limit = $timeout;
            $timeout = $salon_order->created_at->diffInMinutes(Carbon::now(), false);

            $canAccept = $salon_order->canAcceptBySalon();
            if ($canAccept[2]) {
                $this->updatePaymentStatus($order, false);
            }
            if(!$canAccept[0]){
                abort(422, $canAccept[1]);
            }

            $data['timeout'] = abs($limit-$timeout)*60;
            $data['created_at'] = $salon_order->created_at->format('H:i d/m/Y');
            $data['amount_coin'] = $salon_order->amount_coin;
            $data['amount_money'] = $salon_order->amount_money;
            $user_info = $salon_order->user;
            if($user_info){
                $cover = $user_info->avatar;
                $data['customer']['id']     = $user_info->id;
                $data['customer']['name']   = $user_info->name;
                $data['customer']['rating']   = 0.0;
                $data['customer']['phone']  = $user_info->phone;
                $data['customer']['avatar'] = $cover?$cover->getThumbnailUrl('default', getNoThumbnailUrl()):getNoThumbnailUrl();
            }
            $data['date'] = date_format($salon_order->service_time,"d/m/Y");
            $data['time'] = date_format($salon_order->service_time,"H:i");
            $services = $salon_order->items->map(function($item){
                return [
                    'id'    => $item->service_id,
                    'name'  => $item->service_name,
                    'qty'   => $item->quatity,
                    'sum'   => $item->quatity * $item->price
                ];
            });
            $data['services'] = $services;
        }
        return \Response::json($data);
    }

    function newBooking(Request $request, Salon $salon){
        $data = self::getNewBooking($salon);
        return \Response::json($data);
    }

    /**
     * Đặt chổ cần làm sắp tới*
     *
     * Đặt chổ cần làm sắp tới
     *
     */
    function nextWaitingBooking(Request $request, Salon $salon){
        $data = self::getNextWaitingBooking($salon);
        return \Response::json($data);
    }

    /**
     * Đếm đặt chỗ chờ làm*
     *
     * Đếm tất cả đặt chỗ chờ làm trong hệ thống
     *
     */
    function countWaitingBooking(Request $request, Salon $salon){
        $salon_id = $salon->id;
        $salon_order_count =  SalonOrder::where('service_time','>', \Carbon\Carbon::now())
            ->where(['salon_id' => $salon_id, 'status' => SalonOrder::_CHO_THUC_HIEN_])->count();
        return \Response::json($salon_order_count);
    }

    function newBookingList(Request $request, Salon $salon){
        $salon_id = $salon->id;
        $salon_orders =  SalonOrder::where('service_time','>', \Carbon\Carbon::now())
            ->with(['user', 'user.avatar', 'items'])
            ->has('user')
            ->orderBy('created_at', 'asc')
            ->where(['salon_id' => $salon_id, 'status' => SalonOrder::_CHO_XU_LY_])
            ->paginate(10)
        ;
        $rs = [
            'currentPage' => $salon_orders->currentPage(),
            'lastPage' => $salon_orders->lastPage(),
            'total' => $salon_orders->total(),
            'items' => array_map(function(SalonOrder $order){
                $data  = [];
                $data['id'] = $order->id;
                $user_info = $order->user;
                $avatar = $user_info->avatar;
                $data['id'] = $order->id;
                $data['time'] = $order->service_time ? $order->service_time->format('H:i') : '';
                $data['date'] = $order->service_time ? $order->service_time->format('d/m/Y') : '';
                $data['status'] = $order->status;
                $data['customer']['id']     = $user_info->id;
                $data['customer']['name']   = $user_info->name;
                $data['customer']['phone']  = $user_info->phone;
                $data['customer']['rating']  = 0.0;
                $data['customer']['avatar'] = $avatar?$avatar->getThumbnailUrl('default', getNoThumbnailUrl()):getNoThumbnailUrl();
                $data['amount_coin'] = $order->amount_coin;
                $data['amount_money'] = $order->amount_money;

                $services = $order->items->map(function($item){
                    return [
                        'id'    => $item->service_id,
                        'name'  => $item->service_name,
                        'qty'   => $item->quatity,
                        'sum'   => $item->quatity * $item->price
                    ];
                });
                $data['services'] = $services;
                return $data;
            }, $salon_orders->items())
        ];
        return \Response::json($rs);
    }

    function todayWaitingBooking(Request $request, Salon $salon){
        $salon_id = $salon->id;
        $today = date_format(\Carbon\Carbon::now(),"Y-m-d");
        $salon_orders =  SalonOrder::where('service_time','>', \Carbon\Carbon::now())
            ->where('service_time', '<=', $today.' 23:59:59')
            ->with(['user', 'user.avatar', 'items'])
            ->where(['salon_id' => $salon_id, 'status' => SalonOrder::_CHO_THUC_HIEN_])
            ->orderBy('service_time', 'asc')
            ->has('user')
            ->orderBy('created_at', 'asc')
            ->paginate(10)
        ;
        $rs = [
            'currentPage' => $salon_orders->currentPage(),
            'lastPage' => $salon_orders->lastPage(),
            'total' => $salon_orders->total(),
            'items' => array_map(function(SalonOrder $order){
                $data  = [];
                $user_info = $order->user;
                $avatar = $user_info->avatar;
                $data['id'] = $order->id;
                $data['time'] = $order->service_time ? $order->service_time->format('H:i') : '';
                $data['date'] = $order->service_time ? $order->service_time->format('d/m/Y') : '';
                $data['status'] = $order->status;
                $data['customer']['id']     = $user_info->id;
                $data['customer']['name']   = $user_info->name;
                $data['customer']['phone']  = $user_info->phone;
                $data['customer']['rating']  = 0.0;
                $data['customer']['avatar'] = $avatar?$avatar->getThumbnailUrl('default', getNoThumbnailUrl()):getNoThumbnailUrl();
                $data['amount_coin'] = $order->amount_coin;
                $data['amount_money'] = $order->amount_money;

                $services = $order->items->map(function($item){
                    return [
                        'id'    => $item->service_id,
                        'name'  => $item->service_name,
                        'qty'   => $item->quatity,
                        'sum'   => $item->quatity * $item->price
                    ];
                });
                $data['services'] = $services;
                return $data;
            }, $salon_orders->items())
        ];
        return \Response::json($rs);
    }

    function listMayDoneBooking(Request $request, Salon $salon){
        $salon_id = $salon->id;
        $salon_orders =  SalonOrder::where('service_time','<=', \Carbon\Carbon::now())
            ->with(['user', 'user.avatar', 'items'])
            ->where(['salon_id' => $salon_id, 'status' => SalonOrder::_CHO_THUC_HIEN_])
            ->orderBy('service_time', 'asc')
            ->has('user')
            ->orderBy('created_at', 'asc')
            ->paginate(10)
        ;
        $rs = [
            'currentPage' => $salon_orders->currentPage(),
            'lastPage' => $salon_orders->lastPage(),
            'total' => $salon_orders->total(),
            'items' => array_map(function(SalonOrder $order){
                $data  = [];
                $user_info = $order->user;
                $avatar = $user_info->avatar;
                $data['id'] = $order->id;
                $data['time'] = $order->service_time ? $order->service_time->format('H:i') : '';
                $data['date'] = $order->service_time ? $order->service_time->format('d/m/Y') : '';
                $data['status'] = $order->status;
                $data['amount_coin'] = $order->amount_coin;
                $data['amount_money'] = $order->amount_money;
                $data['customer']['id']     = $user_info->id;
                $data['customer']['name']   = $user_info->name;
                $data['customer']['phone']  = $user_info->phone;
                $data['customer']['rating']  = 0.0;
                $data['customer']['avatar'] = $avatar?$avatar->getThumbnailUrl('default', getNoThumbnailUrl()):getNoThumbnailUrl();

                $services = $order->items->map(function($item){
                    return [
                        'id'    => $item->service_id,
                        'name'  => $item->service_name,
                        'qty'   => $item->quatity,
                        'sum'   => $item->quatity * $item->price
                    ];
                });
                $data['services'] = $services;
                return $data;
            }, $salon_orders->items())
        ];
        return \Response::json($rs);
    }

    function listWaitingBooking(Request $request, Salon $salon){
        $salon_id = $salon->id;
        $salon_orders =  SalonOrder::where('service_time','>', \Carbon\Carbon::now())
            ->with(['user', 'user.avatar', 'items'])
            ->where(['salon_id' => $salon_id, 'status' => SalonOrder::_CHO_THUC_HIEN_])
            ->orderBy('service_time', 'asc')
            ->has('user')
            ->orderBy('created_at', 'asc')
            ->paginate(10)
        ;
        $rs = [
            'currentPage' => $salon_orders->currentPage(),
            'lastPage' => $salon_orders->lastPage(),
            'total' => $salon_orders->total(),
            'items' => array_map(function(SalonOrder $order){
                $data  = [];
                $user_info = $order->user;
                $avatar = $user_info->avatar;
                $data['id'] = $order->id;
                $data['time'] = $order->service_time ? $order->service_time->format('H:i') : '';
                $data['date'] = $order->service_time ? $order->service_time->format('d/m/Y') : '';
                $data['status'] = $order->status;
                $data['amount_coin'] = $order->amount_coin;
                $data['amount_money'] = $order->amount_money;
                $data['customer']['id']     = $user_info->id;
                $data['customer']['name']   = $user_info->name;
                $data['customer']['phone']  = $user_info->phone;
                $data['customer']['rating']  = 0.0;
                $data['customer']['avatar'] = $avatar?$avatar->getThumbnailUrl('default', getNoThumbnailUrl()):getNoThumbnailUrl();

                $services = $order->items->map(function($item){
                    return [
                        'id'    => $item->service_id,
                        'name'  => $item->service_name,
                        'qty'   => $item->quatity,
                        'sum'   => $item->quatity * $item->price
                    ];
                });
                $data['services'] = $services;
                return $data;
            }, $salon_orders->items())
        ];
        return \Response::json($rs);
    }

    function todayIncomeList(Request $request, Salon $salon){
        $today = date_format(\Carbon\Carbon::now(),"Y-m-d");

        /** @var SalonOrder[] $done_bookings */
        $done_bookings =  SalonOrder::
        with(['user'])->
        where('service_time', '>=', $today.' 00:00:00')
            ->whereDate('service_time', '<=', \Carbon\Carbon::now())
            ->where('salon_id', $salon->id)->with(['items'])
            ->whereIn('status', [SalonOrder::_CHO_THUC_HIEN_, SalonOrder::_DA_HOAN_THANH_])
            ->orderBy('service_time', 'desc')->orderBy('created_at', 'desc')
            ->get();

        $total = 0;
        $items = [];
        $sum = 0;
        foreach ($done_bookings as $booking){
            $item = $this->formatOutputData($booking);
            $items[] = $item;
            $sum += $item['sum'];
            $total++;
        }
        return \Response::json([
            'currentPage' => 1,
            'lastPage' => 1,
            'total' => $total,
            'sum' => $sum,
            'items' => $items
        ]);
    }


    function todayDoneList(Request $request, Salon $salon){
        $today = date_format(\Carbon\Carbon::now(),"Y-m-d");

        /** @var SalonOrder[] $done_bookings */
        $done_bookings =  SalonOrder::
        with(['user'])->
        where('service_time', '>=', $today.' 00:00:00')
            ->whereDate('service_time', '<=', \Carbon\Carbon::now())
            ->where(['salon_id' => $salon->id, 'status' => SalonOrder::_DA_HOAN_THANH_])->with(['items'])
            ->orderBy('service_time', 'desc')->orderBy('created_at', 'desc')
            ->get();

        $total = 0;
        $items = [];
        $sum = 0;
        foreach ($done_bookings as $booking){
            $item = $this->formatOutputData($booking);
            $items[] = $item;
            $sum += $item['sum'];
            $total++;
        }
        return \Response::json([
            'currentPage' => 1,
            'lastPage' => 1,
            'total' => $total,
            'sum' => $sum,
            'items' => $items
        ]);
    }

    function thisWeekDoneList(Request $request, Salon $salon){
        $current_start_of_week = \Carbon\Carbon::now()->startOfWeek();
        $current_end_of_week = \Carbon\Carbon::now()->endOfWeek()->format('Y/m/d');
        $done_bookings_query =  SalonOrder::
            with(['user'])->
        where('service_time', '>=', $current_start_of_week)
            ->whereDate('service_time', '<=', $current_end_of_week)
            ->where(['salon_id' => $salon->id, 'status' => SalonOrder::_DA_HOAN_THANH_])
            ->with(['items'])
            ->orderBy('service_time', 'desc')->orderBy('created_at', 'desc');

        return $this->queryOutput($done_bookings_query);
    }

    function thisWeekWaitingList(Request $request, Salon $salon){
        $current_start_of_week = \Carbon\Carbon::now()->startOfWeek()->format('Y/m/d');
        $current_end_of_week = \Carbon\Carbon::now()->endOfWeek()->format('Y/m/d');
        $done_bookings_query =  SalonOrder::
        with(['user'])->
        whereDate('service_time', '>=', $current_start_of_week)
            ->whereDate('service_time', '<=', $current_end_of_week)
            ->where(['salon_id' => $salon->id, 'status' => SalonOrder::_CHO_THUC_HIEN_])
            ->with(['items'])
            ->orderBy('service_time', 'desc')
            ->orderBy('created_at', 'desc');

        return $this->queryOutput($done_bookings_query);
    }

    function thisWeekList(Request $request, Salon $salon){
        $current_start_of_week = \Carbon\Carbon::now()->startOfWeek();
        $current_end_of_week = \Carbon\Carbon::now()->endOfWeek()->format('Y/m/d');
        $done_bookings_query =  SalonOrder::
        with(['user'])->
        where('service_time', '>=', $current_start_of_week)
            ->whereDate('service_time', '<=', $current_end_of_week)
            ->where('salon_id', $salon->id)
            ->whereIn('status',[SalonOrder::_CHO_THUC_HIEN_, SalonOrder::_DA_HOAN_THANH_])
            ->with(['items'])
            ->orderBy('service_time', 'desc')
            ->orderBy('created_at', 'desc');

        return $this->queryOutput($done_bookings_query);
    }

    function thisMonthList(Request $request, Salon $salon){
        $current_month_year = \Carbon\Carbon::now()->format('Y-m');

        $done_bookings_query =  SalonOrder::
        with(['user'])->
        whereDate('service_time', '>=', $current_month_year.'-01')
            ->whereDate('service_time', '<=', \Carbon\Carbon::now())
            ->where('salon_id', $salon->id)
            ->whereIn('status',[SalonOrder::_CHO_THUC_HIEN_, SalonOrder::_DA_HOAN_THANH_])
            ->with(['items'])
            ->orderBy('service_time', 'desc')
            ->orderBy('created_at', 'desc');

        return $this->queryOutput($done_bookings_query);
    }

    function thisMonthDoneList(Request $request, Salon $salon){
        $current_month_year = \Carbon\Carbon::now()->format('Y-m');

        $done_bookings_query =  SalonOrder::
        with(['user'])->
        whereDate('service_time', '>=', $current_month_year.'-01')
            ->whereDate('service_time', '<=', \Carbon\Carbon::now())
            ->where('salon_id', $salon->id)
            ->whereIn('status',[SalonOrder::_DA_HOAN_THANH_])
            ->with(['items'])
            ->orderBy('service_time', 'desc')
            ->orderBy('created_at', 'desc');

        return $this->queryOutput($done_bookings_query);
    }

    function bookingFilter(Request $request, Salon $salon){
        $query =  SalonOrder::
        with(['user'])
            ->where('salon_id', $salon->id)
            ->with(['items'])
            ->orderBy('id', 'desc');

        $start = $request->get('start_date', null);
        $end = $request->get('end_date', null);
        if($start && $end){
            $start = Carbon::createFromFormat('d/m/Y H:i:s', $start.' 00:00:00');
            $end = Carbon::createFromFormat('d/m/Y H:i:s', $end.' 23:59:59');
            $created_filter = $request->get('created_at', true);
            if($created_filter == '1' || $created_filter == 1){
                $created_filter = true;
            }
            else{
                $created_filter = false;
            }
            if($created_filter){
                $col = 'created_at';
            }
            else{
                $col = 'service_time';
            }
            $query->whereBetween($col, [
                $start,
                $end
            ]);
        }
        $status = $request->get('status', []);
        if($status){
            $query->whereIn('status', $status);
        }

        return $this->queryOutput($query);
    }

    function allTimeList(Request $request, Salon $salon){
        $done_bookings_query =  SalonOrder::
        with(['user'])->
        where('service_time', '<=', \Carbon\Carbon::now())
            ->where('salon_id', $salon->id)
            ->whereIn('status',[SalonOrder::_CHO_THUC_HIEN_, SalonOrder::_DA_HOAN_THANH_])
            ->with(['items'])
            ->orderBy('service_time', 'desc')
            ->orderBy('created_at', 'desc');

        return $this->queryOutput($done_bookings_query);
    }

    /**
     * @param SalonOrder $booking
     * @return array
     */
    private function formatOutputData($booking): array
    {
        $item = [
            'id' => $booking->id,
            'user' => [
                'id' => $booking->user->id,
                'name' => $booking->user->name,
                'phone' => $booking->user->phone,
                'rating' => 4.5,
                'avatar' => $booking->user->avatar?$booking->user->avatar->getThumbnailUrl('default', getNoAvatarUrl()):getNoAvatarUrl()
            ],
            'amount_coin' => $booking->amount_coin,
            'amount_money' => $booking->amount_money,
            'total' => $booking->items->count(),
            'create_time' => $booking->created_at->format('H:i'),
            'create_date' => $booking->created_at->format('d/m/Y'),
            'time' => $booking->service_time ? $booking->service_time->format('H:i') : '',
            'date' => $booking->service_time ? $booking->service_time->format('d/m/Y') : '',
            'status' => $booking->status,
            'payment' => $booking->getPaymentMethodText(),
            'sum' => $booking->items->sum(function (SalonOrderItem $orderItem) {
                return $orderItem->quatity * $orderItem->price;
            }),
            'services' => $booking->items->map(function (SalonOrderItem $orderItem) {
                return [
                    'id' => $orderItem->service_id,
                    'name' => $orderItem->service_name,
                    'qty' => $orderItem->quatity,
                    'sum' => $orderItem->quatity * $orderItem->price
                ];
            }),
        ];
        return $item;
    }

    private function queryOutput($done_bookings_query): \Illuminate\Http\JsonResponse
    {
        $done_bookings_sum = (clone $done_bookings_query);

        $done_bookings = $done_bookings_query->paginate(20);

        $done_bookings_sum = $done_bookings_sum->get(['id']);

        $total = $done_bookings->total();
        $items = [];

        $sum = $done_bookings_sum->sum(function (SalonOrder $order) {
            return $order->items->sum(function (SalonOrderItem $orderItem) {
                return $orderItem->quatity * $orderItem->price;
            });
        });

        foreach ($done_bookings as $booking) {
            $item = $this->formatOutputData($booking);
            $items[] = $item;
        }
        return \Response::json([
            'currentPage' => $done_bookings->currentPage(),
            'lastPage' => $done_bookings->lastPage(),
            'total' => $total,
            'sum' => $sum,
            'items' => $items
        ]);
    }

    function nextMayDoneBooking(Request $request, Salon $salon){
        $data = self::getNextMayDoneBooking($salon);
        return \Response::json($data);
    }

    function getCancelReasons(Request $request, Salon $salon){
        $rs = [];
        try{
            $ls = getSetting('booking_manager_cancel_reasons', []);
            foreach ($ls as $l){
                $rs[] = $l['content'];
            }
        }
        catch (\Exception $exception){

        }
        if(!$rs){
            $rs[] = 'Lý do riêng tư';
        }
        return \Response::json($rs);
    }

    function detail(Request $request, Salon $salon){
        $id = $request->get('id');
        $order = SalonOrder::has('user')
            ->where('salon_id', $salon->id)
            ->with(['user', 'items'])
            ->where('id', $id)
            ->first();
        $sum = 0;
        if(!$order){
            abort(404, 'Đơn hàng không tồn tại, đã bị xoá hoặc không thuộc về salon bạn!');
        }
        $services = [];
        foreach ($order->items as $item){
            $iSum = $item->price * $item->quatity;
            $sum += $iSum;
            $services[] = [
                'id' => $item->id,
                'name' => $item->service_name,
                'qty' => $item->quatity,
                'sum' => $iSum
            ];
        }
        $acceptChecking = $order->canAcceptBySalon();
        if ($acceptChecking[2]) {
            $this->updatePaymentStatus($order, false);
        }
        $can_reject = $acceptChecking[0];
        $can_accept = $acceptChecking[0];
        $can_finish = $order->canFinishBySalon()[0];
        return \Response::json(
            [
                'id' => $order->id,
                'sum' => $sum,
                'date' => $order->service_time ? $order->service_time->format('d/m/Y') : '',
                'time' => $order->service_time ? $order->service_time->format('H:i') : '',
                'salon' => [
                    'name' => $order->salon_name,
                    'address' => $order->salon_address
                ],
                'payment' => $order->getPaymentMethodText(),
                'status' => $order->status,
                'amount_coin' => $order->amount_coin,
                'amount_money' => $order->amount_money,
                'services' => $services,
                'can_reject' => $can_reject,
                'can_accept' => $can_accept,
                'can_finish' => $can_finish,
                'order_title' => $order->getOrderMessage('salon')
            ]
        );
    }

    function changeRequestInfo(Request $request, SalonOrderChangeTimeRequest $change, Salon $salon){
        $order = $change->order;
        if($salon->id != $order->salon_id){
            abort(400, 'Yêu cầu không hợp lệ');
        }
        $order->load(['items']);
        if($order->service_time->lessThanOrEqualTo(Carbon::now())){
            abort(400, 'Quá hạn xử lý');
        }
        return \Response::json([
            'order_id' => $order->id,
            'old_time' => $order->service_time ? $order->service_time->format('H:i') : '',
            'old_date' => $order->service_time ? $order->service_time->format('d/m/Y') : '',
            'new_time' => $change->service_time ? $change->service_time->format('H:i') : '',
            'new_date' => $change->service_time ? $change->service_time->format('d/m/Y') : '',
            'amount_coin' => $order->amount_coin,
            'amount_money' => $order->amount_money,
            'services' => $order->items->map(function(SalonOrderItem $item){
                return [
                    'name' => $item->service_name,
                    'qty' => $item->quatity,
                    'sum' => $item->quatity * $item->price
                ];
            })
        ]);
    }

    function changeRequestAccept(Request $request, SalonOrderChangeTimeRequest $change, Salon $salon){
        $order = $change->order;
        if($salon->id != $order->salon_id){
            abort(400, 'Yêu cầu không hợp lệ');
        }
        $order->load(['items', 'user']);
        if($order->service_time->lessThanOrEqualTo(Carbon::now())){
            abort(400, 'Quá hạn xử lý');
        }
        $order->service_time = $change->service_time;
        $count = $order->change_count + 1;
        $change->delete();
        $order->change_count = $count;
        $order->save();
        $user = $order->user;
        $user->notify(new CommonNotify(
            $salon->cover_id?$salon->cover_id:false,
            "{$salon->name} đã từ chấp thuận yêu cầu đổi thời gian thực hiện dịch của cho đơn hàng #{$order->id}. Thời gian thực hiện dịch vụ mới sẽ là {$order->service_time->format('H:i d/m/Y')}",
            route('frontend.account.history'),
            '#FF5C39',
            true,
            'request_approved',
            'Yêu cầu được chấp nhận',
            false,
            '',
            [
                'salon_id' => $salon->id,
                'order_id' => $order->id,
                'route' => [
                    'home_order_detail',
                    [
                        'id' => $order->id,
                        'title' => '',
                    ]
                ],
            ]
        ));
        return \Response::json($change);
    }

    function changeRequestCancel(Request $request, SalonOrderChangeTimeRequest $change, Salon $salon){
        $order = $change->order;
        if($salon->id != $order->salon_id){
            abort(400, 'Yêu cầu không hợp lệ');
        }
        $order->load(['items', 'user']);
        if($order->service_time->lessThanOrEqualTo(Carbon::now())){
            abort(400, 'Quá hạn xử lý');
        }
        $change->delete();
        $user = $order->user;
        $user->notify(new CommonNotify(
            $salon->cover_id?$salon->cover_id:false,
            "{$salon->name} đã từ chối yêu cầu đổi thời gian thực hiện dịch của cho đơn hàng #{$order->id}. Chúng tôi rất tiếc vì điều này!",
            route('frontend.account.history'),
            '#FF5C39',
            true,
            'request_rejected',
            'Yêu cầu bị từ chối',
            false,
            '',
            [
                'salon_id' => $salon->id,
                'order_id' => $order->id,
                'route' => [
                    'home_order_detail',
                    [
                        'id' => $order->id,
                        'title' => '',
                    ]
                ],
            ]
        ));
        return \Response::json($change);
    }
}