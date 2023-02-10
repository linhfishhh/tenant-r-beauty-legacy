<?php

namespace Modules\ModHairWorld\Http\Controllers\api;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\ModHairWorld\Entities\Badge;
use Modules\ModHairWorld\Entities\Salon;
use Modules\ModHairWorld\Entities\SalonOrder;
use Modules\ModHairWorld\Entities\SalonOrderChangeTimeRequest;
use Modules\ModHairWorld\Entities\SalonOrderIncludedItem;
use Modules\ModHairWorld\Entities\SalonOrderItem;
use Modules\ModHairWorld\Entities\SalonService;
use Modules\ModHairWorld\Entities\SalonServiceOption;
use Modules\ModHairWorld\Entities\SalonServiceReview;
use Modules\ModHairWorld\Entities\SalonServiceReviewCriteria;
use Modules\ModHairWorld\Entities\UserAddress;
use Modules\ModHairWorld\Entities\SalonServiceIncludedOption;
use Modules\ModHairWorld\Events\SalonOrderWaitingToProcess;
use Modules\ModHairWorld\Handlers\SalonWalletHandler;

class BookingController extends Controller
{
    use SalonWalletHandler;

    function checkStatus(Request $request)
    {
        $status = 99;
        $id = $request->get('id');
        $user = \Auth::user();
        $booking = SalonOrder::where('id', $id)->where('user_id', $user->id)->first();
        if ($booking) {
            $status = $booking->status;
        }
        return \Response::json($status);
    }

    function add(Request $request) {
        \Validator::validate($request->all(), [
            'salon_id' => ['required'],
            'items' => ['required'],
        ], [

        ]);

        $salon = Salon::find($request->get('salon_id'));
        if (!$salon) {
            throw new \Exception('Lỗi');
        }
        if(!$salon->open){
            abort(400, 'Salon hiện tại đã ngoại tuyến không tiếp nhận đơn đặt chỗ mới, vui lòng quay lại sau nhé!');
        }

        SalonOrder::preventSpam();

        $booking = new SalonOrder();
        $booking->user_id = me()->id;
        $booking->salon_id = $request->get('salon_id');
        $booking->salon_address = $salon->getAddressLine();
        $booking->salon_name = $salon->name;

        $booking->status = SalonOrder::_CHO_THANH_TOAN_; // Chờ thanh toán

        $booking->info_name = me()->name;
        $booking->info_email = me()->email;
        $booking->info_phone = me()->phone;

        $booking->save();

        $items = $request->get('items', []);
        $item_list = [];
        foreach ($items as $item){
            $item_list[$item['id']] = $item;
        }

        $included_items = $request->get('included_items', []);
        $included_item_list = [];
        foreach ($included_items as $i_item) {
            if (!isset($included_item_list[$i_item['service_id']])) {
                $included_item_list[$i_item['service_id']] = [$i_item['id']=> $i_item];
            } else {
                $included_item_list[$i_item['service_id']][] = $i_item;
            }
        }

        /** @var SalonService[] $services */
        $services = SalonService::with(['sale_off', 'options','included_options'])->whereIn('id', array_keys($item_list))->get();
        foreach ($services as $service){
            $item = $item_list[$service->id];
            $option_id = null;
            $included_option_id = null;
            $included_option_qty = null;
            if(isset($item['option_id'])){
                $option_id = $item['option_id'];
            }
            $options = $service->options;

            $selected_option = null;
            if($option_id){
                foreach ($options as $option){
                    if($option->id == $option_id){
                        $selected_option = $option;
                        break;
                    }
                }
            }
            if($options->count()>0 && !$selected_option){
                $booking->delete();
                throw new \Exception('Lỗi');
            }
            if($options->count() == 0 && $option_id){
                $booking->delete();
                throw new \Exception('Lỗi');
            }
            $new_item = new SalonOrderItem();
            $new_item->order_id = $booking->id;
            $new_item->service_id = $service->id;
            $new_item->service_name = $service->getOptionName($option_id);
            $new_item->quatity = $item['qty'];
            $new_item->price = $service->getOptionFinalPrice($option_id);
            $new_item->save();
            if (isset($included_item_list[$service->id])) {
                /** @var SalonServiceIncludedOption[] $available_included_option_ids */
                $available_included_option_ids = [];
                foreach($service->included_options as $included_option) {
                    $available_included_option_ids[$included_option->id] = $included_option;
                }
                foreach ($included_item_list[$service->id] as $included_option_id => $i_item) {
                    if (isset($available_included_option_ids[$included_option_id])) {
                        //code save

                        $salon_include_option = $available_included_option_ids[$included_option_id];
                        $new_included_items = new SalonOrderIncludedItem();
                        $new_included_items->order_id = $booking->id;
                        $new_included_items->service_id = $service->id;
                        $new_included_items->included_options_id = $i_item['id'];
                        $new_included_items->included_options_name = $salon_include_option->name;
                        $new_included_items->quatity = $i_item['quatity'];
                        $new_included_items->price = $salon_include_option->price;
                        $new_included_items->save();
                    } else {
                        // TODO: raise error
//                        throw new \Exception('Lỗi');
                    }
                }

            }
        }
        $booking->load('items');
        if($booking->items->count() == 0){
            $booking->delete();
            throw new \Exception('Lỗi');
        }

        $booking->save();

        return \Response::json([
            'id' => $booking->id,
            'salon_name' => $booking->salon_name,
            'salon_address' => $booking->salon_address,
        ]);
    }

    function addV2(Request $request) {
        \Validator::validate($request->all(), [
            'salon_id' => ['required'],
            'items' => ['required'],
            'payment_method' => ['required'],
            'service_time' => ['required'],
            'amount_coin' => ['nullable', 'numeric'],
            'spend_max_coin' => ['nullable'],
        ], [

        ]);

        $salon = Salon::find($request->get('salon_id'));
        if (!$salon) {
            throw new \Exception('Lỗi');
        }
        if(!$salon->open){
            abort(400, 'Salon hiện tại đã ngoại tuyến không tiếp nhận đơn đặt chỗ mới, vui lòng quay lại sau nhé!');
        }

        SalonOrder::preventSpam();

        $spendMaxCoin = $request->get('spend_max_coin', false);
        $amountCoin = $request->get('amount_coin', 0);
        $paymentMethod = $request->get('payment_method');
        $serviceTime = $request->get('service_time');

        $booking = new SalonOrder();
        $booking->user_id = me()->id;
        $booking->salon_id = $request->get('salon_id');
        $booking->salon_address = $salon->getAddressLine();
        $booking->salon_name = $salon->name;

        $booking->status = SalonOrder::_CHO_THANH_TOAN_;

        $booking->info_name = me()->name;
        $booking->info_email = me()->email;
        $booking->info_phone = me()->phone;

        $booking->service_time = $serviceTime;
        $booking->payment_method = $paymentMethod;

        $booking->save();

        $items = $request->get('items', []);
        $item_list = [];
        foreach ($items as $item){
            $item_list[$item['id']] = $item;
        }

        $included_items = $request->get('included_items', []);
        $included_item_list = [];
        foreach ($included_items as $i_item) {
            if (!isset($included_item_list[$i_item['service_id']])) {
                $included_item_list[$i_item['service_id']] = [$i_item['id']=> $i_item];
            } else {
                $included_item_list[$i_item['service_id']][] = $i_item;
            }
        }

        /** @var SalonService[] $services */
        $services = SalonService::with(['sale_off', 'options','included_options'])->whereIn('id', array_keys($item_list))->get();
        $total = 0;
        foreach ($services as $service){
            $item = $item_list[$service->id];
            $option_id = null;
            $included_option_id = null;
            $included_option_qty = null;
            if(isset($item['option_id'])){
                $option_id = $item['option_id'];
            }
            $options = $service->options;

            $selected_option = null;
            if($option_id){
                foreach ($options as $option){
                    if($option->id == $option_id){
                        $selected_option = $option;
                        break;
                    }
                }
            }
            if($options->count()>0 && !$selected_option){
                $booking->delete();
                throw new \Exception('Lỗi');
            }
            if($options->count() == 0 && $option_id){
                $booking->delete();
                throw new \Exception('Lỗi');
            }
            $new_item = new SalonOrderItem();
            $new_item->order_id = $booking->id;
            $new_item->service_id = $service->id;
            $new_item->service_name = $service->getOptionName($option_id);
            $new_item->quatity = $item['qty'];
            $new_item->price = $service->getOptionFinalPrice($option_id);
            $new_item->save();
            $total += $new_item->price * $new_item->quatity;
            if (isset($included_item_list[$service->id])) {
                /** @var SalonServiceIncludedOption[] $available_included_option_ids */
                $available_included_option_ids = [];
                foreach($service->included_options as $included_option) {
                    $available_included_option_ids[$included_option->id] = $included_option;
                }
                foreach ($included_item_list[$service->id] as $included_option_id => $i_item) {
                    if (isset($available_included_option_ids[$included_option_id])) {
                        //code save

                        $salon_include_option = $available_included_option_ids[$included_option_id];
                        $new_included_items = new SalonOrderIncludedItem();
                        $new_included_items->order_id = $booking->id;
                        $new_included_items->service_id = $service->id;
                        $new_included_items->included_options_id = $i_item['id'];
                        $new_included_items->included_options_name = $salon_include_option->name;
                        $new_included_items->quatity = $i_item['quatity'];
                        $new_included_items->price = $salon_include_option->price;
                        $new_included_items->save();
                    } else {
                        // TODO: raise error
                       throw new \Exception('Lỗi');
                    }
                }

            }
        }
        $booking->load('items');
        if($booking->items->count() == 0){
            $booking->delete();
            throw new \Exception('Lỗi');
        }

        // $payment_link = $booking->getPaymentLink($payment_method);
        $payment_link = '';

        $booking->total = $total;
        $response = [
            'id' => $booking->id,
            'payment_method' => $booking->payment_method,
            'salon_name' => $booking->salon_name,
            'salon_address' => $booking->salon_address,
            'service_time' => $booking->service_time->format('H:i'),
            'service_date' => $booking->service_time->format('d/m/Y'),
            'payment_link' => $payment_link,
            //'debug' => $request->all()
        ];

        $coinCalculation = $this->bookService($booking, $total, $spendMaxCoin, $amountCoin);
        if ($coinCalculation != null) {
            $booking->amount_coin = $coinCalculation->amountCoin;
            $booking->amount_money = $coinCalculation->amountMoney;
            $response['amount_coin'] = $coinCalculation->amountCoin;
            $response['amount_money'] = $coinCalculation->amountMoney;
        } else {
            $booking->delete();
            throw new \Exception('Lỗi khởi tạo giao dịch');
        }

        $booking->status = SalonOrder::_CHO_XU_LY_;
        $booking->save();

        return \Response::json($response);
    }

    function update(Request $request) {
        \Validator::validate($request->all(), [
            'booking_id' => ['required'],
            'payment_method' => ['required'],
            'service_time' => ['required'],
            'amount_coin' => ['nullable', 'numeric'],
            'spend_max_coin' => ['nullable'],
        ], [

        ]);

        // SalonOrder::preventSpam();

        $bookingId = $request->get('booking_id');
        $spendMaxCoin = $request->get('spend_max_coin', false);
        $amountCoin = $request->get('amount_coin', 0);

        $booking = SalonOrder::find($bookingId);
        if (!$booking) {
            throw new \Exception('Lỗi');
        }

        $salon = Salon::find($booking->salon_id);
        if (!$salon) {
            throw new \Exception('Lỗi');
        }
        if(!$salon->open){
            abort(400, 'Salon hiện tại đã ngoại tuyến không tiếp nhận đơn đặt chỗ mới, vui lòng quay lại sau nhé!');
        }

        $payment_method = $request->get('payment_method');

        $booking->service_time = $request->get('service_time');
        $booking->payment_method = $payment_method;

        $booking->save();

        $items = $request->get('items', []);
        $item_list = [];
        foreach ($items as $item){
            $item_list[$item['id']] = $item;
        }

        $included_items = $request->get('included_items', []);
        $included_item_list = [];
        foreach ($included_items as $i_item) {
            if (!isset($included_item_list[$i_item['service_id']])) {
                $included_item_list[$i_item['service_id']] = [$i_item['id']=> $i_item];
            } else {
                $included_item_list[$i_item['service_id']][] = $i_item;
            }
        }

        // delete all current items then add new
        try {
            if (!SalonOrderItem::where(['order_id' => $booking->id])->delete()) {
                throw new \Exception('Lỗi');
            }
        } catch (\Exception $e) {};
        /** @var SalonService[] $services */
        $services = SalonService::with(['sale_off', 'options','included_options'])->whereIn('id', array_keys($item_list))->get();
        $total = 0;
        foreach ($services as $service){
            $item = $item_list[$service->id];
            $option_id = null;
            $included_option_id = null;
            $included_option_qty = null;
            if(isset($item['option_id'])){
                $option_id = $item['option_id'];
            }
            $options = $service->options;

            $selected_option = null;
            if($option_id){
                foreach ($options as $option){
                    if($option->id == $option_id){
                        $selected_option = $option;
                        break;
                    }
                }
            }
            if($options->count()>0 && !$selected_option){
                throw new \Exception('Lỗi');
            }
            if($options->count() == 0 && $option_id){
                throw new \Exception('Lỗi');
            }
//            $existing_items = SalonOrderItem::where(['service_id' => $service->id, 'order_id' => $bookingId])->get();
//            $new_item = null;
//            if ($existing_items && $existing_items->count() > 0) {
//                $new_item = $existing_items[0];
//            } else {
//                $new_item = new SalonOrderItem();
//            }
            $new_item = new SalonOrderItem();
            $new_item->order_id = $booking->id;
            $new_item->service_id = $service->id;
            $new_item->service_name = $service->getOptionName($option_id);
            $new_item->quatity = $item['qty'];
            $new_item->price = $service->getOptionFinalPrice($option_id);
            $new_item->save();
            $total += $new_item->price * $new_item->quatity;
            if (isset($included_item_list[$service->id])) {
                /** @var SalonServiceIncludedOption[] $available_included_option_ids */
                $available_included_option_ids = [];
                foreach($service->included_options as $included_option) {
                    $available_included_option_ids[$included_option->id] = $included_option;
                }
                foreach ($included_item_list[$service->id] as $included_option_id => $i_item) {
                    if (isset($available_included_option_ids[$included_option_id])) {
                        //code save

                        $salon_include_option = $available_included_option_ids[$included_option_id];
                        $new_included_items = null;
                        $existing_included_items = SalonOrderIncludedItem::where([
                            ['order_id', '=', $booking->id],
                            ['service_id', '=', $service->id]])->get();
                        if ($existing_included_items && $existing_included_items->count() > 0) {
                            $new_included_items = $existing_included_items[0];
                        } else {
                            $new_included_items = new SalonOrderIncludedItem();
                        }
                        $new_included_items->order_id = $booking->id;
                        $new_included_items->service_id = $service->id;
                        $new_included_items->included_options_id = $i_item['id'];
                        $new_included_items->included_options_name = $salon_include_option->name;
                        $new_included_items->quatity = $i_item['quatity'];
                        $new_included_items->price = $salon_include_option->price;
                        $new_included_items->save();
                    }
                }

            }
        }
        if ($payment_method === 'salon') {
            $payment_link = '';
        } else {
            //$payment_link = $booking->getPaymentLink($payment_method);
        }

        $booking->status = SalonOrder::_CHO_XU_LY_;
        $booking->total = $total;
        $response = [
            'id' => $booking->id,
            'payment_method' => $booking->payment_method,
            'salon_name' => $booking->salon_name,
            'salon_address' => $booking->salon_address,
            'service_time' => $booking->service_time->format('H:i'),
            'service_date' => $booking->service_time->format('d/m/Y'),
            'payment_link' => $payment_link,
            //'debug' => $request->all()
        ];

        $coinCalculation = $this->bookService($booking, $total, $spendMaxCoin, $amountCoin);
        if ($coinCalculation != null) {
            $booking->amount_coin = $coinCalculation->amountCoin;
            $booking->amount_money = $coinCalculation->amountMoney;
            $response['amount_coin'] = $coinCalculation->amountCoin;
            $response['amount_money'] = $coinCalculation->amountMoney;
        } else {
            throw new \Exception('Lỗi khởi tạo giao dịch');
        }

        $booking->save();
        return \Response::json($response);
    }

    function create(Request $request)
    {
        \Validator::validate($request->all(), [
            'salon_id' => ['required'],
            'items' => ['required'],
            'payment_method' => ['required'],
            'service_time' => ['required'],
        ], [

        ]);

        $salon = Salon::find($request->get('salon_id'));
        if (!$salon) {
            throw new \Exception('Lỗi');
        }
        if(!$salon->open){
            abort(400, 'Salon hiện tại đã ngoại tuyến không tiếp nhận đơn đặt chỗ mới, vui lòng quay lại sau nhé!');
        }

        SalonOrder::preventSpam();

        $booking = new SalonOrder();
        $booking->user_id = me()->id;
        $booking->salon_id = $request->get('salon_id');
        $booking->salon_address = $salon->getAddressLine();
        $booking->salon_name = $salon->name;
        $booking->service_time = $request->get('service_time');
        $payment_method = $request->get('payment_method');

        $booking->status = SalonOrder::_KHOI_TAO_;

        $booking->payment_method = $payment_method;
        $booking->info_name = me()->name;
        $booking->info_email = me()->email;
        $booking->info_phone = me()->phone;
        $booking->save();
        $items = $request->get('items', []);
        $item_list = [];
        foreach ($items as $item){
            $item_list[$item['id']] = $item;
        }

        $included_items = $request->get('included_items', []);
        $included_item_list = [];
        foreach ($included_items as $i_item) {
            if (!isset($included_item_list[$i_item['service_id']])) {
                $included_item_list[$i_item['service_id']] = [$i_item['id']=> $i_item];
            } else {
                $included_item_list[$i_item['service_id']][] = $i_item;
            }
        }

        /** @var SalonService[] $services */
        $services = SalonService::with(['sale_off', 'options','included_options'])->whereIn('id', array_keys($item_list))->get();
        foreach ($services as $service){
            $item = $item_list[$service->id];
            $option_id = null;
            $included_option_id = null;
            $included_option_qty = null;
            if(isset($item['option_id'])){
                $option_id = $item['option_id'];
            }
            $options = $service->options;

            $selected_option = null;
            if($option_id){
                foreach ($options as $option){
                    if($option->id == $option_id){
                        $selected_option = $option;
                        break;
                    }
                }
            }
            if($options->count()>0 && !$selected_option){
                $booking->delete();
                throw new \Exception('Lỗi');
            }
            if($options->count() == 0 && $option_id){
                $booking->delete();
                throw new \Exception('Lỗi');
            }
            $new_item = new SalonOrderItem();
            $new_item->order_id = $booking->id;
            $new_item->service_id = $service->id;
            $new_item->service_name = $service->getOptionName($option_id);
            $new_item->quatity = $item['qty'];
            $new_item->price = $service->getOptionFinalPrice($option_id);
            $new_item->save();
            if (isset($included_item_list[$service->id])) {
                /** @var SalonServiceIncludedOption[] $available_included_option_ids */
                $available_included_option_ids = [];
                foreach($service->included_options as $included_option) {
                    $available_included_option_ids[$included_option->id] = $included_option;
                }
                foreach ($included_item_list[$service->id] as $included_option_id => $i_item) {
                    if (isset($available_included_option_ids[$included_option_id])) {
                        //code save

                        $salon_include_option = $available_included_option_ids[$included_option_id];
                        $new_included_items = new SalonOrderIncludedItem();
                        $new_included_items->order_id = $booking->id;
                        $new_included_items->service_id = $service->id;
                        $new_included_items->included_options_id = $i_item['id'];
                        $new_included_items->included_options_name = $salon_include_option->name;
                        $new_included_items->quatity = $i_item['quatity'];
                        $new_included_items->price = $salon_include_option->price;
                        $new_included_items->save();
                    } else {
                        // TODO: raise error
//                        throw new \Exception('Lỗi');
                    }
                }

            }
        }
        $booking->load('items');
        if($booking->items->count() == 0){
            $booking->delete();
            throw new \Exception('Lỗi');
        }
        if ($payment_method === 'salon') {
            $payment_link = '';
        } else {
            //$payment_link = $booking->getPaymentLink($payment_method);
        }

        $booking->status = SalonOrder::_CHO_XU_LY_;

        $booking->save();

        return \Response::json([
            'id' => $booking->id,
            'payment_method' => $booking->payment_method,
            'salon_name' => $booking->salon_name,
            'salon_address' => $booking->salon_address,
            'service_time' => $booking->service_time->format('H:i'),
            'service_date' => $booking->service_time->format('d/m/Y'),
            'payment_link' => $payment_link,
            //'debug' => $request->all()
        ]);
    }
    function cancel(Request $request)
    {
        $id = $request->get('id');
        /** @var SalonOrder $booking */
        $booking = SalonOrder::where('id', $id)->whereIn('status', [SalonOrder::_CHO_XU_LY_, SalonOrder::_CHO_THANH_TOAN_, SalonOrder::_CHO_THUC_HIEN_])->get()->first();
        if (!$booking) {
            abort(400, 'Đơn đặt chỗ không tồn tại hoặc không thể huỷ');
        }
        $booking->status = SalonOrder::_HUY_BOI_KHACH_;
        $this->updatePaymentStatus($booking, false);
        $booking->save();
        return \Response::json(true);
    }

    function paymentLink(Request $request)
    {
        $id = $request->get('id');
        $booking = SalonOrder::find($id);
        if (!$booking) {
            throw new \Exception('Lỗi');
        }
        $booking->load('items');
        $link = $booking->getPaymentLink($booking->payment_method);
        return \Response::json($link);
    }

    function newAddressInfo(Request $request)
    {
        \Validator::validate($request->all(), [
            'info_name' => ['required'],
            'info_phone' => ['required', 'numeric'],
            'info_email' => ['required', 'email'],
            'info_address' => ['required'],
            'info_lv1' => ['required', 'numeric'],
            'info_lv2' => ['required', 'numeric'],
            'info_lv3' => ['required', 'numeric'],
        ], [
            'info_name.required' => 'Họ tên không được bỏ trống',
            'info_phone.required' => 'Số điện thoại không được bỏ trống',
            'info_phone.numeric' => 'Số điện thoại không hợp lệ',
            'info_email.required' => 'Email không được bỏ trống',
            'info_email.email' => 'Email không hợp lệ',
            'info_lv1.required' => 'Vui lòng chọn tỉnh/thành phố',
            'info_lv2.required' => 'Vui lòng chọn quận huyện',
            'info_lv3.required' => 'Vui lòng chọn phường xã'
        ]);
        $user = \Auth::user();
        $new = new UserAddress();
        $new->address = $request->get('info_address');
        $new->email = $request->get(('info_email'));
        $new->name = $request->get(('info_name'));
        $new->phone = $request->get('info_phone');
        $new->address_lv1 = $request->get('info_lv1');
        $new->address_lv2 = $request->get('info_lv2');
        $new->address_lv3 = $request->get('info_lv3');
        $new->user_id = $user->id;
        $new->save();
        $addresses = UserAddress::with(['lv1', 'lv2', 'lv3'])->where('user_id', $user->id)->get()->map(function (UserAddress $address) {
            return [
                'id' => $address->id,
                'info_address' => $address->getAddressLine(),
                'info_name' => $address->name,
                'info_phone' => $address->phone,
                'info_email' => $address->email,
            ];
        });
        return \Response::json([
            'new_address_id' => $new->id,
            'list' => $addresses
        ]);
    }

    function history(Request $request)
    {
        $per_page = $request->get('per_page');
        if (!$per_page || $per_page == 0) {
            $per_page = 5;
        }
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $status = $request->get('status');

        $orders = SalonOrder::has('user')->where('user_id', \Auth::user()->id);
        if ($start_date) {
            $orders = $orders->where('service_time', '>=', $start_date);
        }
        if ($end_date) {
            $orders = $orders->where('service_time', '<=', $end_date);
        }
        if ($status > -4) {
            $orders = $orders->where('status', '=', $status);
        }
        $orders = $orders->with([
            'user',
            'items',
            'included_items'
        ])
        ->orderBy('id', 'desc')
        ->paginate($per_page);

        $rs = [
            'currentPage' => $orders->currentPage(),
            'isLastPage' => $orders->currentPage() === $orders->lastPage(),
            'items' => array_map(function (SalonOrder $order) {
                $service_time = $order->service_time;
                $order_items = SalonOrderItem::whereOrderId($order->id)->get();
                $price = 0;
                if ($order_items) {
                    foreach ($order_items as $item) {
                        $price += $item->quatity * $item->price;
                    }
                }
                return [
                    'id' => $order->id,
                    'service' => $order->items->count() === 1 ? $order->items->first()->service_name : $order->items->count() . ' dịch vụ',
                    'included_items' => $order->included_items->count() === 0 ? "Không có dịch vụ kèm theo" : $order->included_items->count() . ' dịch vụ kèm theo',
                    'salon' => $order->salon_name,
                    'date' => $service_time ? $service_time->format('H:i - d/m/Y') : '',
                    'status' => $order->status,
                    'price' => $price,
                    'can_cancel' => $order->canCancelByUser()[0],
                    'amount_coin' => $order->amount_coin,
                    'amount_money' => $order->amount_money,
                ];
            }, $orders->items()),
        ];

        return \Response::json($rs);
    }

    function detail(Request $request){
        $id = $request->get('id');
        $order = SalonOrder::has('user')
            ->where('user_id', \Auth::user()->id)
            ->with(['user', 'items','included_items'])
            ->where('id', $id)
            ->first();
        if(!$order){
            abort(404, 'Đơn hàng không tồn tại, đã bị xoá hoặc không thuộc về salon bạn!');
        }
        $sum = 0;
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
        $included_services = array();
        foreach ($order->included_items as $included_items){
            $included_iSum = $included_items->price * $included_items->quatity;
            $sum += $included_iSum;
            array_push($included_services, [
                    'id' => $included_items->id,
                    'options_name' => $included_items->included_options_name,
                    'qty' => $included_items->quatity,
                    'sum' => $included_iSum
            ]);
        }
        $can_change = $order->canChangeByUser()[0];
        $can_pay = $order->canPayOnlineByUser()[0];
        $isTimeout = $order->canAcceptBySalon()[2];
        $can_cancel = $order->canCancelByUser()[0];
        if ($isTimeout) {
            $this->updatePaymentStatus($order, false);
        }
        $service_time = $order->service_time;
        return \Response::json(
            [
                'id' => $order->id,
                'sum' => $sum,
                'date' => $service_time ? $service_time->format('d/m/Y') : '',
                'time' => $service_time ? $service_time->format('H:i') : '',
                'amount_coin' => $order->amount_coin,
                'amount_money' => $order->amount_money,
                'salon' => [
                    'name' => $order->salon_name,
                    'address' => $order->salon_address
                ],
                'payment' => $order->getPaymentMethodText(),
                'status' => $order->status,
                'services' => $services,
                'included_services' => $included_services,
                'can_cancel' => $can_cancel,
                'can_change' => $can_change,
                'can_pay' => $can_pay,
                'order_title' => $order->getOrderMessage('customer')
            ]
        );
    }

    function waiting(){
        $orders = SalonOrder::has('user')
            ->with(['items', 'items.service'])
            ->where('user_id', \Auth::user()->id)
            ->where('status', '=', SalonOrder::_CHO_THUC_HIEN_)
            ->where('service_time', '>=', Carbon::now())
            ->orderBy('service_time', 'asc')
            ->get();

        return \Response::json([
            'total' => $orders->count(),
            'orders' => $orders->map(function (SalonOrder $order){
                return [
                    'id' => $order->id,
                    'time' => $order->service_time->format('H:i'),
                    'date' => $order->service_time->format('d/m/Y'),
                    'service' => $order->items->count() === 1? $order->items->first()->service_name: $order->items->count().' dịch vụ',
                    'salon' => [
                        'name' => $order->salon_name,
                        'address' => $order->salon_address
                    ],
                    'service_times' => $order->items->sum(function (SalonOrderItem $item){
                        return $item->service?($item->service->time_to * $item->quatity):'??';
                    }),
                    'status' => $order->status,
                    'amount_coin' => $order->amount_coin,
                    'amount_money' => $order->amount_money,
                ];
            })
        ]);
    }

    function crits(Request $request){
        $id = $request->get('id');
        $serivce_id = $request->get('service_id');
        /** @var SalonOrder $order */
        $order = SalonOrder::whereUserId(me()->id)->with(['review'])->where('id', $id)->first();
        if(!$order){
            abort(400, 'Đơn hàng không tồn tại!');
        }
        if($order->status !== 3){
            abort(400, 'Chỉ những đơn đặt chỗ hoàn thành mới có thể viết đánh giá');
        }
        $reviews_count = SalonServiceReview::where('user_id', me()->id)
            ->where('order_id', $id)->where('service_id', $serivce_id)
            ->count();
        if($reviews_count>0){
            abort(400, 'Dịch vụ này bạn đã viết đánh giá nhận xét rồi');
        }
        $crits = SalonServiceReviewCriteria::all();
        $badges = Badge::with(['image'])->get();
        return \Response::json([
            'crits' => $crits->map(function (SalonServiceReviewCriteria $criteria){
                return [
                    'id' => $criteria->id,
                    'name' => $criteria->name
                ];
            }),
            'badges' => $badges->map(function (Badge $badge){
                return [
                    'id' => $badge->id,
                    'name' => $badge->title,
                    'image' => $badge->image?$badge->image->getThumbnailUrl('default', false):false
                ];
            })
        ]);
    }



    function changeTimeInfo(Request $request, SalonOrder $order){
        $can = $order->canChangeByUser();
        if(!$can[0]){
            abort(400, $can[1]);
        }
        $salon = $order->salon;
        $rs = [
            'time' => $order->service_time->format('H:i'),
            'date' => $order->service_time->format('d/m/Y'),
            'changeCount' => $order->change_count,
            'changeCountLimit' => SalonOrder::getChangeCountLimit(),
            'timeList' => $salon->getOrderTimeList()
        ];
        return \Response::json($rs);
    }

    function changeTimeRequest(Request $request, SalonOrder $order){
        $can = $order->canChangeByUser();
        if(!$can[0]){
            abort(400, $can[1]);
        }
        $salon = $order->salon;
        $new_request = new SalonOrderChangeTimeRequest();
        $new_request->order_id = $order->id;
        $time = $request->get('time');
        $date = $request->get('date');
        if(!$time || !$date){
            abort(400, 'Yêu cầu không hợp lệ');
        }
        $new_request->service_time = Carbon::createFromFormat('Y-m-d H:i', "{$date} {$time}");
        $new_request->save();
        return \Response::json($request->all());
    }

    function CartItems(Request $request, Salon $salon){
        $items = $request->get('items', []);
        $settings = getSettingsFromPage('promo_configs');
        $settings = collect($settings);
        $promo_limit = $settings->get('promo_limit');
        $promo_percent = $settings->get('promo_percent');
        $total = 0;
        foreach ($items as $k=>$item){
            $service = SalonService::find($item['id']);
            $promo_price = $salon->getServicePromoPrice($service);
            if($promo_price !== false){
                $promo_price = round($promo_price);
                $items[$k]['price'] = round($promo_price);
                $items[$k]['promo'] = true;
                $items[$k]['promo_text'] = " (-$promo_percent% cho $promo_limit đơn đặt chỗ đầu tiên)";
                if($items[$k]['option']){
                    $items[$k]['option']['final_price'] = $promo_price;
                }
            }
            $total += $items[$k]['price'] * $items[$k]['qty'];
        }
        return \Response::json([
            'items' => $items,
            'total' => $total
        ]);
    }

}