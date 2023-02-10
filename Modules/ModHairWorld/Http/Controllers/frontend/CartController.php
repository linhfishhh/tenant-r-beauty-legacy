<?php

namespace Modules\ModHairWorld\Http\Controllers\frontend;


use App\Http\Controllers\Controller;
use App\Http\Requests\Ajax;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Modules\ModHairWorld\Entities\NganLuongCheckout;
use Modules\ModHairWorld\Entities\Salon;
use Modules\ModHairWorld\Entities\SalonOpenTime;
use Modules\ModHairWorld\Entities\SalonOrder;
use Modules\ModHairWorld\Entities\SalonOrderItem;
use Modules\ModHairWorld\Entities\SalonService;
use Modules\ModHairWorld\Entities\UserAddress;
use Modules\ModHairWorld\Http\Controllers\OnepayController;
use Modules\ModHairWorld\Http\Requests\Frontend\Cart\PaymentAddressAdd;
use Modules\ModHairWorld\Entities\SalonOrderIncludedItem;

class CartController extends Controller
{
    /** @var Salon $salon */
    private $salon;
    /** @var SalonService[] */
    private $items;
    private $item_info;
    private function check(){
        $salon_id = session()->get('wa_cart_salon', null);
        $salon = Salon::find($salon_id);
        $item_ids = session()->get('wa_cart_items', []);
        $items = SalonService::whereSalonId($salon_id)
            ->with(['options'])
            ->whereIn('id', array_keys($item_ids))
            ->get();
        $this->salon = $salon;
        $this->items = $items;
        if(
            !$salon
            ||($items->count() == 0)
        ){
            return false;
        }
        $salon->load(['times']);
        foreach ($items as $item){
            $this->item_info[$item->id] = $item_ids[$item->id];
        }
        return true;
    }

    function add($salonId, $items, $includedItems) {
        $salon = Salon::find($salonId);
        if (!$salon) {
            throw new \Exception('Lỗi');
        }
        if(!$salon->open){
            abort(400, 'Salon hiện tại đã ngoại tuyến không tiếp nhận đơn đặt chỗ mới, vui lòng quay lại sau nhé!');
        }

        SalonOrder::preventSpam();

        $booking = new SalonOrder();
        $booking->user_id = me()->id;
        $booking->salon_id = $salonId;
        $booking->salon_address = $salon->getAddressLine();
        $booking->salon_name = $salon->name;

        $booking->status = SalonOrder::_CHO_THANH_TOAN_; // Chờ thanh toán

        $booking->info_name = me()->name;
        $booking->info_email = me()->email;
        $booking->info_phone = me()->phone;

        $booking->save();

        $item_list = [];
        foreach ($items as $item){
            $item_list[$item['id']] = $item;
        }

        $included_items = $includedItems;
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
            if(isset($item['option_id']) && is_numeric($item['option_id'])){
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

        return $booking->id;
    }

    function createOrder(Request $request, SalonService $service) {
        $items = [];
        $stored_cart_items = $request->session()->get('wa_cart_items', []);
        $stored_cart_item_keys = array_keys($stored_cart_items);
        foreach ($stored_cart_item_keys as $cart_item_key) {
            $cart_item = $stored_cart_items[$cart_item_key];
            if (array_key_exists("option_id", $cart_item)) {
                array_push($items, [
                    'id' => $cart_item_key,
                    'qty' => $cart_item['amount'],
                    'option_id' => $cart_item['option_id']
                ]);
            } else {
                array_push($items, [
                    'id' => $cart_item_key,
                    'qty' => $cart_item['amount']
                ]);
            }
        }

        $salon_id = $request->session()->get('wa_cart_salon', null);
        $bookingId = $this->add($salon_id, $items, []);
        $request->session()->put('wa_cart_booking_id', $bookingId);
//        return \Response::json([
//            'id' => $bookingId
//        ]);
        return \Redirect::route('frontend.cart.1');
    }

    function stepOne(Request $request){
        if(!$this->check()){
            if($this->salon){
                return \Redirect::route('frontend.salon', ['salon' => $this->salon->id]);
            }
            else{
                return \Redirect::route('frontend.index');
            }
        }
        $disabled_week_days = [0=>0, 1=>1, 2=>2, 3=>3, 4=>4, 5=>5, 6=>6];
        foreach ($this->salon->times as $time){
            if($time->weekday == 7){
                unset($disabled_week_days[0]);
            }
            if(in_array($time->weekday,$disabled_week_days)){
                unset($disabled_week_days[$time->weekday]);
            }
        }

        return view(getThemeViewName('cart.cart_step_1'), [
            'salon' => $this->salon,
            'items' => $this->items,
            'disabled_week_days' => $disabled_week_days
        ]);
    }

    function stepOneSave(Request $request){
        if(!$this->check()){
            return \Response::json(0);
        }
        $date = $request->get('date');
        $time = $request->get('time');
        try{
            $date_check = Carbon::createFromFormat('d/m/Y', $date);
            $weekday = $date_check->dayOfWeek;
            if($weekday == 0){
                $weekday = 7;
            }
            $date_check_m = SalonOpenTime::whereSalonId($this->salon->id)->where('weekday', $weekday)->first();
            if(!$date_check_m){
                return \Response::json(0);
            }
            $time_check = Carbon::createFromFormat('H:i:s', $time);
            $time_start = Carbon::createFromFormat('H:i:s', $date_check_m->start);
            $time_end = Carbon::createFromFormat('H:i:s', $date_check_m->end);
            if(!$time_check->between($time_start, $time_end)){
                return \Response::json(0);
            }
            session()->put('wa_cart_time', $date_check->format('Y-m-d').' '.$time_check->format('H:i:s'));
        }
        catch (\Exception $e){
            return \Response::json(0);
        }
        if($date && $time){
        }
        return \Response::json(1);
    }

    function stepTwo(Request $request){
        if(!$this->check()){
            if($this->salon){
                return \Redirect::route('frontend.salon', ['salon' => $this->salon->id]);
            }
            else{
                return \Redirect::route('frontend.index');
            }
        }
        $cart_time = session()->get('wa_cart_time');
        if(!$cart_time){
            return \Redirect::route('frontend.cart.1');
        }
        $cart_time = Carbon::createFromFormat('Y-m-d H:i:s', $cart_time);
        return view(getThemeViewName('cart.cart_step_2'), [
            'cart_time' => $cart_time,
            'salon' => $this->salon,
            'items' => $this->items
        ]);
    }

    function checkOnePay(){
        \Debugbar::disable();
        $rs = OnepayController::doCheck(route('onepay.check.web'),route('onepay.cancel.link'));
        $success = $rs['success'];
        if(!$success){
            return view('modhairworld::cart.cart_step_2_onpay', $rs);
        }
        else{
            return Redirect::route('frontend.cart.order', ['order' => $rs['id']]);
        }
    }

    function stepTwoSave_v2(Request $request){

        if(!$this->check()){
            return \Response::json(0);
        }
        $cart_time = session()->get('wa_cart_time');
        if(!$cart_time){
            return \Response::json(0);
        }
        $salon = $this->salon;
        if(!$salon->open){
            abort(400, 'Rất tiếc salon đã ngoại tuyến, xin bạn vui lòng đặt chỗ lại sau nhé!');
        }

        SalonOrder::preventSpam();

        $bookingId = session()->get('wa_cart_booking_id');
        $new_order = SalonOrder::find($bookingId);
        if (!$new_order) {
            $new_order = new SalonOrder();
        }
        $payment_method = $request->get('payment_method');
        $cart_time = Carbon::createFromFormat('Y-m-d H:i:s', $cart_time);
        $new_order->payment_method = $payment_method;
        $new_order->service_time = $cart_time;

        $new_order->user_id = me()->id;
        $new_order->salon_id = $this->salon->id;
        $new_order->salon_name = $this->salon->name;
        $new_order->salon_address = $this->salon->getAddressLine();
        $new_order->status = SalonOrder::_KHOI_TAO_;
        $new_order->save();
        $bookingId =  $new_order->id;
        foreach ($this->items as $service){
            $qty = $this->item_info[$service->id]['amount'];
            $option_id = $this->item_info[$service->id]['option_id'];
            $items = SalonOrderItem::where(['service_id' => $service->id, 'order_id' => $bookingId])->get();
            $item = null;
            if ($items && $items->count() > 0) {
                $item = $items[0];
            } else {
                $item = new SalonOrderItem();
            }
            $item->order_id = $bookingId;
            $item->service_id = $service->id;
            $item->service_name = $service->getOptionName($option_id);
            $item->price = $service->getOptionFinalPrice($option_id);
            $item->quatity = $qty;
            $item->save();
        }

        if($payment_method == 'salon'){
            $rs_link = route('frontend.cart.order', ['order' => $bookingId]);
        }
        else{
            $rs_link = $payment_method;
        }

        $new_order->status = SalonOrder::_CHO_XU_LY_;
        $new_order->save();
        session()->remove('wa_cart_salon');
        session()->remove('wa_cart_items');
        session()->remove('wa_cart_total');
        session()->remove('wa_cart_time');

        //Log::info('Link: '.$rs_link);
        return \Response::json($rs_link);
    }

    function stepTwoSave(Request $request){
		
        if(!$this->check()){
            return \Response::json(0);
        }
        $cart_time = session()->get('wa_cart_time');
        if(!$cart_time){
            return \Response::json(0);
        }
        $salon = $this->salon;
        if(!$salon->open){
            abort(400, 'Rất tiếc salon đã ngoại tuyến, xin bạn vui lòng đặt chỗ lại sau nhé!');
        }

        SalonOrder::preventSpam();

        $cart_time = Carbon::createFromFormat('Y-m-d H:i:s', $cart_time);
        $payment_method = $request->get('payment_method');
        $new_order = new SalonOrder();
        $new_order->user_id = me()->id;
        $new_order->salon_id = $this->salon->id;
        $new_order->service_time = $cart_time;
        $new_order->salon_name = $this->salon->name;
        $new_order->salon_address = $this->salon->getAddressLine();
        $new_order->status = SalonOrder::_KHOI_TAO_;
        $new_order->payment_method = $payment_method;
        $new_order->save();
        $order_id =  $new_order->id;
        foreach ($this->items as $service){
            $qty = $this->item_info[$service->id]['amount'];
            $option_id = $this->item_info[$service->id]['option_id'];
            $item = new SalonOrderItem();
            $item->order_id = $order_id;
            $item->service_id = $service->id;
            $item->service_name = $service->getOptionName($option_id);
            $item->price = $service->getOptionFinalPrice($option_id);
            $item->quatity = $qty;
            $item->save();
        }
        if($payment_method == 'salon'){
            $rs_link = route('frontend.cart.order', ['order' => $order_id]);
        }
        else{
            $rs_link = $payment_method;
        }
		
//        $event = new SalonOrderWaitingToProcess($new_order);
//        event($event);
        $new_order->status = SalonOrder::_CHO_XU_LY_;
        $new_order->save();
        session()->remove('wa_cart_salon');
        session()->remove('wa_cart_items');
        session()->remove('wa_cart_total');
        session()->remove('wa_cart_time');
		
		//Log::info('Link: '.$rs_link);
        return \Response::json($rs_link);
    }

    static function NLCheckOutLink(SalonOrder $order){
        $configs = getSettingsFromPage('nganluong_configs');
        $configs = collect($configs);
        $receiver= $configs->get('nl_email');
        //Mã đơn hàng
        $order_code=$order->id;
        //Khai báo url trả về
        $return_url= route('frontend.cart.order', ['order' => $order->id]);
        // Link nut hủy đơn hàng
        $cancel_url= url('');
        //Giá của cả giỏ hàng
        $txh_name = $order->info_name;
        $txt_email =$order->info_email;
        $txt_phone =$order->info_phone;
        $price = $order->getSum();
        //Thông tin giao dịch
        $transaction_info="Thanh toán đơn đặt chỗ #".$order->id;
        $currency= "vnd";
        $quantity=1;
        $tax=0;
        $discount=0;
        $fee_cal=0;
        $fee_shipping=0;
        $order_description="Thanh toán đơn đặt chỗ #: ".$order_code;
        $buyer_info=$txh_name."*|*".$txt_email."*|*".$txt_phone;
        $affiliate_code="";
        //Khai báo đối tượng của lớp NL_Checkout
        $nl= new NganLuongCheckout();
        $nl->nganluong_url = $configs->get('nl_link');
        $nl->merchant_site_code = $configs->get('nl_user');
        $nl->secure_pass = $configs->get('nl_pass');
        //Tạo link thanh toán đến nganluong.vn
        $url= $nl->buildCheckoutUrlExpand($return_url,
            $receiver, $transaction_info,
            $order_code, $price, $currency,
            $quantity, $tax, $discount , $fee_cal,
            $fee_shipping, $order_description, $buyer_info ,
            $affiliate_code);
        //$url= $nl->buildCheckoutUrl($return_url, $receiver, $transaction_info, $order_code, $price);
        return $url;
    }

    function stepTwoAddAddress(PaymentAddressAdd $request){
        $name = $request->get('name');
        $phone = $request->get('phone');
        $email = $request->get('email');
        $address_text = $request->get('address');
        $tinh_thanh_pho_id = $request->get('tinh_thanh_pho_id');
        $quan_huyen_id = $request->get('quan_huyen_id');
        $phuong_xa_thi_tran_id = $request->get('phuong_xa_thi_tran_id');
        $address = new UserAddress();
        $address->name = $name;
        $address->phone = $phone;
        $address->email = $email;
        $address->address = $address_text;
        $address->address_lv1 = $tinh_thanh_pho_id;
        $address->address_lv2 = $quan_huyen_id;
        $address->address_lv3 = $phuong_xa_thi_tran_id;
        $address->user_id = me()->id;
        if($address->save()){
            return \Response::json([
                'id' => $address->id,
                'text' => $address->getAddressLine()
            ]);
        }
        else{
            return \Response::json(0);
        }

    }

    function stepThree(Request $request, SalonOrder $order){
        if($order->user_id != me()->id){
            return \Redirect::route('frontend.index');
        }
        $order->load([
            'salon',
            'salon.location_lv1',
            'salon.location_lv2',
            'salon.location_lv3',
            'items'
        ]);
        return view(getThemeViewName('cart.cart_step_3'), [
            'order' => $order
        ]);
    }

    function reCalculateTotal(){
        $total = 0;
        foreach ($this->items as $item){
            if(isset($this->item_info[$item->id])){
                $total += $item->getFinalPrice() * $this->item_info[$item->id]['amount'];
            }
        }
        session()->put('wa_cart_total', $total);
    }

    function removeItem(Ajax $request){
        if(!$this->check()){
            return \Response::json(0);
        }
        $id = $request->get('id');
        $item_ids = session()->get('wa_cart_items', null);
        if(isset($item_ids[$id])){
            unset($item_ids[$id]);
            session()->put('wa_cart_items', $item_ids);
            $this->item_info = $item_ids;
            $this->reCalculateTotal();
        }
        return \Response::json(1);
    }

    function updateAmount(Ajax $request){
        if(!$this->check()){
            return \Response::json(0);
        }
        $amount = $request->get('amount',1);
        $id = $request->get('id');
        $item_ids = $this->item_info;
        if(isset($item_ids[$id])){
            $item_ids[$id]['amount'] = $amount;
            session()->put('wa_cart_items', $item_ids);
            $this->item_info = $item_ids;
            $this->reCalculateTotal();
        }
        return \Response::json(1);
    }
}