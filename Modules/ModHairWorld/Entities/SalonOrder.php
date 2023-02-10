<?php

namespace Modules\ModHairWorld\Entities;


use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\ModHairWorld\Events\SalonOrderCreated;
use Modules\ModHairWorld\Events\SalonOrderDeleted;
use Modules\ModHairWorld\Events\SalonOrderRetrieved;
use Modules\ModHairWorld\Events\SalonOrderUpdated;

/**
 * Modules\ModHairWorld\Entities\SalonOrder
 *
 * @property int $id
 * @property int $user_id
 * @property int $salon_id
 * @property string $reminder_id
 * @property \Carbon\Carbon|null $service_time
 * @property int $status
 * @property int $change_count
 * @property string|null $note
 * @property string $info_name
 * @property string $info_phone
 * @property string $info_email
 * @property string $info_address
 * @property int $amount_coin
 * @property int $amount_money
 * @property int $total total amount
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrder whereInfoAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrder whereInfoEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrder whereInfoName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrder whereInfoPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrder whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrder whereSalonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrder whereServiceTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrder whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrder whereUserId($value)
 * @mixin \Eloquent
 * @property string $payment_method
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrder wherePaymentMethod($value)
 * @property string $salon_name
 * @property string $salon_address
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrder whereSalonAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrder whereSalonName($value)
 * @property-read Salon $salon
 * @property-read SalonOrderItem[]|Collection $items
 * @property-read SalonOrderIncludedItem[]|Collection $included_items
 * @property \Carbon\Carbon|null $payment_time
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrder wherePaymentTime($value)
 * @property-read User $user
 * @property-read SalonServiceReview|null $review
 * @property-read SalonServiceReview[]|Collection $reviewedItems
 * @property-read SalonOrderChangeTimeRequest|null $change_request
 * @property string|null $online_payment_code
 * @property string|null $online_payment_extra
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrder whereOnlinePaymentCode($value)
 */
class SalonOrder extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
        'service_time',
        'payment_time'
    ];

    protected $dispatchesEvents = [
        'created' => SalonOrderCreated::class,
        'deleted' => SalonOrderDeleted::class,
        'updated' => SalonOrderUpdated::class,
        // 'retrieved' => SalonOrderRetrieved::class
    ];

    public static function getChangeTimeLimit(){
        $timeout = getSetting('booking_change_time_limit', 12);
        if(!is_numeric($timeout)){
            $timeout = 12;
        }
        $timeout = round($timeout);
        return $timeout;
    }

    public static function getProcessTimeOut(){
        $timeout = getSetting('booking_accept_timeout', 120);
        if(!is_numeric($timeout)){
            $timeout = 120;
        }
        $timeout = round($timeout);
        return $timeout;
    }

    static function preventSpam(){
        $booking_limit = getSetting('booking_limit', 3);
        if($booking_limit>0){
            $booking_count = SalonOrder::
            where('user_id', me()->id)
                ->whereDate('created_at', Carbon::today())
                ->whereNotIn('status', [
                    SalonOrder::_DA_HOAN_THANH_
                ])
                ->count();
            if($booking_count >= $booking_limit){
                abort(400, "Nhằm tránh sự quấy rối bằng những đơn hàng spam, chúng tôi giới hạn {$booking_limit} đơn đặt chỗ/ngày. Rất xin lỗi vì sự bất tiện này, bạn có thể đặt chỗ trở lại vào ngày mai.");
            }
        }
    }

    function change_request(){
        return $this->hasOne(SalonOrderChangeTimeRequest::class, 'order_id', 'id');
    }

    function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    function salon(){
        return $this->hasOne(Salon::class,'id', 'salon_id');
    }

    function review(){
        return $this->hasOne(SalonServiceReview::class, 'order_id', 'id');
    }

    function getPaymentMethodText(){
        $rs = '';
        foreach (static::getPaymentMethods() as $method){
            if($method['id'] == $this->payment_method){
                $rs = $method['title'];
                break;
            }
        }
        return $rs;
    }

    function getCheckWebCheckLink($id){
        if($id == 'onepay'){
            return route('onepay.check.web');
        }
    }

    function getCancelWebCheckLink($id){
        if($id == 'onepay'){
            return route('onepay.cancel.link');
        }
    }

    function getPaymentLink($id, $return_url=false){
        $link = '';
        if($id == 'onepay'){
            $SECURE_SECRET = "A3EFDFABA8653DF2342E8DAC29B51AF0";
            $vpcURL = 'https://mtf.onepay.vn/onecomm-pay/vpc.op?';
            $stringHashData = "";
            $extra = date ( 'YmdHis' ) . rand ();
            $this->online_payment_extra = $extra;
            $this->save();
            $query = [
                'vpc_Merchant' => 'ONEPAY',
                'vpc_AccessCode' => 'D67342C2',
                'vpc_MerchTxnRef' => $this->id.'---'.$extra,
                'vpc_OrderInfo' => 'iSalon - Thanh toan don dat cho #'.$this->id,
                'vpc_Amount' => $this->items->sum(function(SalonOrderItem $item){
                    return $item->quatity * $item->price;
                })*100,
                'vpc_ReturnURL' => !$return_url?route('onepay.check'):$return_url,
                'vpc_Version' => 2,
                'vpc_Command' => 'pay',
                'vpc_Locale' => 'vn',
                'vpc_Currency' => 'VND'
            ];
            ksort($query);
            $appendAmp = 0;
            foreach($query as $key => $value) {
                if (strlen($value) > 0) {

                    if ($appendAmp == 0) {
                        $vpcURL .= urlencode($key) . '=' . urlencode($value);
                        $appendAmp = 1;
                    } else {
                        $vpcURL .= '&' . urlencode($key) . "=" . urlencode($value);
                    }

                    if ((strlen($value) > 0) && ((substr($key, 0,4)=="vpc_") || (substr($key,0,5) =="user_"))) {
                        $stringHashData .= $key . "=" . $value . "&";
                    }
                }
            }
            $stringHashData = rtrim($stringHashData, "&");
            if (strlen($SECURE_SECRET) > 0) {

                $vpcURL .= "&vpc_SecureHash=" . strtoupper(hash_hmac('SHA256', $stringHashData, pack('H*',$SECURE_SECRET)));
            }
            $link = $vpcURL;
        }
        return $link;
    }

    function items(){
        return $this->hasMany(SalonOrderItem::class, 'order_id', 'id');
    }

    function included_items(){
        return $this->hasMany(SalonOrderIncludedItem::class, 'order_id', 'id');
    }

    function reviewedItems(){
        return $this->hasMany(SalonServiceReview::class, 'order_id', 'id');
    }

    function getSum(){
        $rs = $this->items->sum(function ($item){
            /** @var SalonOrderItem $item */
            return $item->quatity * $item->price;
        });
        return $rs;
    }

    function getNganLuongPaymentLink(){
        $rs = '';
        return $rs;
    }

    function canPayOnlineByUser(){
        $rs = [
            false,
            'Yêu cầu không hợp lệ, đơn đặt chỗ hiện tại không trong trạng thái cần thanh toán!'
        ];
        if($this->status === static::_CHO_THANH_TOAN_){
            return [
                true,
                ''
            ];
        }
        return $rs;
    }

    function canCancelByUser(){
        $rs = [
            true,
            ''
        ];

        if(
            !in_array($this->status, [
                static::_CHO_XU_LY_,
                static::_CHO_THANH_TOAN_,
                static::_CHO_THUC_HIEN_
            ])
        ){
            return [
                false,
                'Yêu cầu không hợp lệ, đơn đặt chỗ hiện tại không trong trạng thái có thể huỷ!'
            ];
        }

        if($this->status === static::_CHO_THUC_HIEN_){

        }

        return $rs;
    }

    function canChangeByUser(){
        $rs = [
            false,
            'Yêu cầu không hợp lệ, đơn đặt chỗ hiện tại không trong trạng thái có thể thay đổi ngày giờ dịch vụ!'
        ];
        if($this->status === static::_CHO_THUC_HIEN_){
            $limit = static::getChangeTimeLimit();
            $remain = Carbon::now()->diffInSeconds($this->service_time, false);
            if($remain >= $limit*60*60){
                $change_count = $this->change_count;
                if($change_count == null){
                    $change_count = 0;
                }
                $change_count_limit = static::getChangeCountLimit();
                if($change_count< $change_count_limit){
                    $request = SalonOrderChangeTimeRequest::whereOrderId($this->id)->count();
                    if($request == 0){
                        return [
                            true,
                            ''
                        ];
                    }
                }
            }
        }
        return $rs;
    }

    function canChangeBySalon(){
        $rs = [
            false,
            'Yêu cầu không hợp lệ, đơn đặt chỗ hiện tại không trong trạng thái chờ duyệt'
        ];
        if($this->status === static::_CHO_XU_LY_){
            return [
                true,
                ''
            ];
        }
        return $rs;
    }

    function canRejectBySalon(){
        return $this->canAcceptBySalon();
    }

    function canFinishBySalon(){
        $rs = [
            false,
            'Yêu cầu không hợp lệ, đơn đặt chỗ hiện tại không trong trạng thái có thể hoàn thành'
        ];

        if($this->service_time->diffInSeconds(Carbon::now(), false)<0){
            return [
                false,
                'Yêu cầu không hợp lệ, đơn đặt chỗ hiện tại không trong trạng thái có thể hoàn thành'
            ];
        }

        if($this->status === static::_CHO_THUC_HIEN_){
            return [
                true,
                ''
            ];
        }
        return $rs;
    }

    function canAcceptBySalon(){
        $rs = [
            true,
            '',
            false,
        ];
        if($this->status !== static::_CHO_XU_LY_){
            return [
                false,
                'Đơn hàng không đang ở trang thái chờ xét duyệt',
                false,
            ];
        }
        $timeout = static::getProcessTimeOut();
        $limit = $timeout;
        $timeout = $this->created_at->diffInMinutes(Carbon::now(), false);
        if($timeout < 0){
            return [
                false,
                "Thời gian đơn hàng này được tạo không hợp lệ (được tạo trong tương lai!!!)",
                false,
            ];

        }
        // if($timeout > $limit) {
        //     $this->status = SalonOrder::_HUY_DO_QUA_HAN_XU_LY;
        //     $this->save();
        //     return [
        //         false,
        //         "Đơn đặt chỗ đã quá thời gian duyệt cho phép. Thời gian duyệt cho phép là {$limit} phút kể lúc từ đơn hàng được tạo.\n Đơn hàng này tạo lúc: {$this->created_at->format('H:i d/m/Y')}",
        //         true,
        //     ];
        // }
        return $rs;
    }

    static function getStatusList(){
        $rs = [];
        $rs[-3] = 'Huỷ do quá hạn xử lý';
        $rs[-2] = 'Huỷ bởi salon';
        $rs[-1] = 'Huỷ bởi khách';
        $rs[0] = 'Chờ xử lý';
        $rs[1] = 'Chờ thanh toán';
        $rs[2] = 'Chờ thực hiện';
        $rs[3] = 'Đã hoàn thành';
        $rs[4] = 'Khách không đến';
        return $rs;
    }

    static function getChangeCountLimit(){
        $limit = getSetting('booking_change_limit', 0);
        if(!is_numeric($limit)){
            $limit = 0;
        }
        $limit = round($limit);
        return $limit;
    }

    static function getStatusTextByID($id){
        $rs = 'N/A';
        $status = static::getStatusList();
        if(array_key_exists($id, $status)){
            $rs = $status[$id];
        }
        return $rs;
    }

    function getOrderMessage($scope){
        $rs = '';
        switch ($this->status){
            case -3:
                if($scope == 'customer'){
                    $rs = "Yêu cầu đặt chỗ đã bị huỷ do quá hạn xử lý!";
                }
                else{
                    $rs = "Yêu cầu đặt chỗ đã bị huỷ do quá hạn xử lý!";
                }
                break;
            case -2:
                $note = $this->note?$this->note:'Lý do riêng tư';
                if($scope == 'customer'){
                    $rs = "Yêu cầu đặt chỗ của bạn đã bị từ chối bởi salon với thông điệp: \"{$note}\"";
                }
                else{
                    $rs = "Bạn đã từ chối yêu cầu đặt chỗ của khách với thông điệp: \"{$note}\"";
                }
                break;
            case -1:
                if($scope == 'customer'){
                    $rs = 'Bạn đã huỷ yêu cầu đặt chỗ này!';
                }
                else{
                    $rs = 'Khách đã huỷ yêu cầu đặt chỗ này!';
                }
                break;
            case 0:
                if($scope == 'customer'){
                    $rs = 'Vui lòng chờ salon duyệt yêu cầu đặt chỗ để tiếp tục nhé!';
                }
                else{
                    $limit = getSetting('booking_accept_timeout', 120);
                    $rs = "Bạn có {$limit} phút để duyệt yêu đặt chỗ này. Hạn chót vào lúc {$this->created_at->addMinute($limit)->format('H:i d/m/Y')}";
                }
                break;
            case 1:
                if($scope == 'customer'){
                    $rs = 'Vui lòng thanh toán đơn đặt chỗ này để tiếp tục!';
                }
                else{
                    $rs = 'Đơn đặt chỗ đang chờ khách thanh toán.';
                }
                break;
            case 2:
                $request = $this->change_request;
                if($scope == 'customer'){
                    $rs = "Vui lòng đến salon vào lúc {$this->service_time->format('H:i d/m/Y')} để salon phục vụ cho bạn nhé";
                    if($request){
                        $rs = "\n*Bạn đã yêu cầu đổi giờ thực hiện sang {$request->service_time->format('H:i d/m/Y')} và đang chờ salon duyệt.";
                    }

                }
                else{
                    $rs = "Khách sẽ đến salon vào lúc {$this->service_time->format('H:i d/m/Y')} để thực hiện dịch vụ, vui lòng chuẩn bị trước để phục vụ khách!";
                    if($request){
                        $rs = "\n*Khách đã yêu cầu đổi giờ thực hiện sang {$request->service_time->format('H:i d/m/Y')} và đang chờ salon duyệt.";
                    }
                }

                break;
            case 3:
                if($scope == 'customer'){
                    $rs = 'Yêu cầu đặt chỗ đã được salon hoàn thành. Cám ơn bạn đã sử dụng dịch vụ!';
                }
                else{
                    $rs = 'Yêu cầu đặt chỗ này đã được salon hoàn thành!';
                }
                break;
            case 4:
                if($scope == 'customer'){
                    $rs = 'Bạn đã không đến salon để thực hiện dịch vụ đúng ngày giờ mà bạn đã đặt!';
                }
                else{
                    $rs = 'Khách đã không đến salon để thực hiện dịch vụ đúng ngày giờ mà khách đã đặt!';
                }
                break;
        }
        return $rs;
    }

    function getStatusText(){
        $rs = '';
        switch ($this->status){
            case static::_CHO_THANH_TOAN_:
                $rs = 'Chờ thanh toán';
                break;
            case static::_CHO_XU_LY_:
                $rs = 'Chờ xử lý';
                break;
            case static::_CHO_THUC_HIEN_:
                $rs = 'Chờ thực hiện';
                break;
            case static::_DA_HOAN_THANH_:
                $rs = 'Đã hoàn thành';
                break;
            case static::_HUY_BOI_SALON_:
                $rs = 'Huỷ bởi salon';
                break;
            case static::_HUY_BOI_KHACH_:
                $rs = 'Huỷ bởi khách';
                break;
            case static::_HUY_DO_QUA_HAN_XU_LY:
                $rs = 'Huỷ do quá hạn xử lý';
                break;
            case static::_KHACH_KHONG_DEN_:
                $rs = 'Khách không đến';
                break;
        }
        return $rs;
    }

    public static function getPaymentMethods(){
        return [
            [
                'id' => 'salon',
                'title' => 'Thanh toán tại salon',
                'desc' => 'Khách thanh toán tại salon phục vụ dịch vụ, các hình thức thanh toán tuỳ thuộc vào từng salon',
                'image' => ''
            ],
        ];
    }

    const _KHOI_TAO_ = -99;
    const _HUY_DO_QUA_HAN_XU_LY = -3;
    const _HUY_BOI_SALON_ = -2;
    const _HUY_BOI_KHACH_ = -1;
    const _CHO_XU_LY_ = 0;
    const _CHO_THANH_TOAN_ = 1;
    const _CHO_THUC_HIEN_ = 2;
    const _DA_HOAN_THANH_ = 3;
    const _KHACH_KHONG_DEN_ = 4;
}