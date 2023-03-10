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
                abort(400, "Nh???m tr??nh s??? qu???y r???i b???ng nh???ng ????n h??ng spam, ch??ng t??i gi???i h???n {$booking_limit} ????n ?????t ch???/ng??y. R???t xin l???i v?? s??? b???t ti???n n??y, b???n c?? th??? ?????t ch??? tr??? l???i v??o ng??y mai.");
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
            'Y??u c???u kh??ng h???p l???, ????n ?????t ch??? hi???n t???i kh??ng trong tr???ng th??i c???n thanh to??n!'
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
                'Y??u c???u kh??ng h???p l???, ????n ?????t ch??? hi???n t???i kh??ng trong tr???ng th??i c?? th??? hu???!'
            ];
        }

        if($this->status === static::_CHO_THUC_HIEN_){

        }

        return $rs;
    }

    function canChangeByUser(){
        $rs = [
            false,
            'Y??u c???u kh??ng h???p l???, ????n ?????t ch??? hi???n t???i kh??ng trong tr???ng th??i c?? th??? thay ?????i ng??y gi??? d???ch v???!'
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
            'Y??u c???u kh??ng h???p l???, ????n ?????t ch??? hi???n t???i kh??ng trong tr???ng th??i ch??? duy???t'
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
            'Y??u c???u kh??ng h???p l???, ????n ?????t ch??? hi???n t???i kh??ng trong tr???ng th??i c?? th??? ho??n th??nh'
        ];

        if($this->service_time->diffInSeconds(Carbon::now(), false)<0){
            return [
                false,
                'Y??u c???u kh??ng h???p l???, ????n ?????t ch??? hi???n t???i kh??ng trong tr???ng th??i c?? th??? ho??n th??nh'
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
                '????n h??ng kh??ng ??ang ??? trang th??i ch??? x??t duy???t',
                false,
            ];
        }
        $timeout = static::getProcessTimeOut();
        $limit = $timeout;
        $timeout = $this->created_at->diffInMinutes(Carbon::now(), false);
        if($timeout < 0){
            return [
                false,
                "Th???i gian ????n h??ng n??y ???????c t???o kh??ng h???p l??? (???????c t???o trong t????ng lai!!!)",
                false,
            ];

        }
        // if($timeout > $limit) {
        //     $this->status = SalonOrder::_HUY_DO_QUA_HAN_XU_LY;
        //     $this->save();
        //     return [
        //         false,
        //         "????n ?????t ch??? ???? qu?? th???i gian duy???t cho ph??p. Th???i gian duy???t cho ph??p l?? {$limit} ph??t k??? l??c t??? ????n h??ng ???????c t???o.\n ????n h??ng n??y t???o l??c: {$this->created_at->format('H:i d/m/Y')}",
        //         true,
        //     ];
        // }
        return $rs;
    }

    static function getStatusList(){
        $rs = [];
        $rs[-3] = 'Hu??? do qu?? h???n x??? l??';
        $rs[-2] = 'Hu??? b???i salon';
        $rs[-1] = 'Hu??? b???i kh??ch';
        $rs[0] = 'Ch??? x??? l??';
        $rs[1] = 'Ch??? thanh to??n';
        $rs[2] = 'Ch??? th???c hi???n';
        $rs[3] = '???? ho??n th??nh';
        $rs[4] = 'Kh??ch kh??ng ?????n';
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
                    $rs = "Y??u c???u ?????t ch??? ???? b??? hu??? do qu?? h???n x??? l??!";
                }
                else{
                    $rs = "Y??u c???u ?????t ch??? ???? b??? hu??? do qu?? h???n x??? l??!";
                }
                break;
            case -2:
                $note = $this->note?$this->note:'L?? do ri??ng t??';
                if($scope == 'customer'){
                    $rs = "Y??u c???u ?????t ch??? c???a b???n ???? b??? t??? ch???i b???i salon v???i th??ng ??i???p: \"{$note}\"";
                }
                else{
                    $rs = "B???n ???? t??? ch???i y??u c???u ?????t ch??? c???a kh??ch v???i th??ng ??i???p: \"{$note}\"";
                }
                break;
            case -1:
                if($scope == 'customer'){
                    $rs = 'B???n ???? hu??? y??u c???u ?????t ch??? n??y!';
                }
                else{
                    $rs = 'Kh??ch ???? hu??? y??u c???u ?????t ch??? n??y!';
                }
                break;
            case 0:
                if($scope == 'customer'){
                    $rs = 'Vui l??ng ch??? salon duy???t y??u c???u ?????t ch??? ????? ti???p t???c nh??!';
                }
                else{
                    $limit = getSetting('booking_accept_timeout', 120);
                    $rs = "B???n c?? {$limit} ph??t ????? duy???t y??u ?????t ch??? n??y. H???n ch??t v??o l??c {$this->created_at->addMinute($limit)->format('H:i d/m/Y')}";
                }
                break;
            case 1:
                if($scope == 'customer'){
                    $rs = 'Vui l??ng thanh to??n ????n ?????t ch??? n??y ????? ti???p t???c!';
                }
                else{
                    $rs = '????n ?????t ch??? ??ang ch??? kh??ch thanh to??n.';
                }
                break;
            case 2:
                $request = $this->change_request;
                if($scope == 'customer'){
                    $rs = "Vui l??ng ?????n salon v??o l??c {$this->service_time->format('H:i d/m/Y')} ????? salon ph???c v??? cho b???n nh??";
                    if($request){
                        $rs = "\n*B???n ???? y??u c???u ?????i gi??? th???c hi???n sang {$request->service_time->format('H:i d/m/Y')} v?? ??ang ch??? salon duy???t.";
                    }

                }
                else{
                    $rs = "Kh??ch s??? ?????n salon v??o l??c {$this->service_time->format('H:i d/m/Y')} ????? th???c hi???n d???ch v???, vui l??ng chu???n b??? tr?????c ????? ph???c v??? kh??ch!";
                    if($request){
                        $rs = "\n*Kh??ch ???? y??u c???u ?????i gi??? th???c hi???n sang {$request->service_time->format('H:i d/m/Y')} v?? ??ang ch??? salon duy???t.";
                    }
                }

                break;
            case 3:
                if($scope == 'customer'){
                    $rs = 'Y??u c???u ?????t ch??? ???? ???????c salon ho??n th??nh. C??m ??n b???n ???? s??? d???ng d???ch v???!';
                }
                else{
                    $rs = 'Y??u c???u ?????t ch??? n??y ???? ???????c salon ho??n th??nh!';
                }
                break;
            case 4:
                if($scope == 'customer'){
                    $rs = 'B???n ???? kh??ng ?????n salon ????? th???c hi???n d???ch v??? ????ng ng??y gi??? m?? b???n ???? ?????t!';
                }
                else{
                    $rs = 'Kh??ch ???? kh??ng ?????n salon ????? th???c hi???n d???ch v??? ????ng ng??y gi??? m?? kh??ch ???? ?????t!';
                }
                break;
        }
        return $rs;
    }

    function getStatusText(){
        $rs = '';
        switch ($this->status){
            case static::_CHO_THANH_TOAN_:
                $rs = 'Ch??? thanh to??n';
                break;
            case static::_CHO_XU_LY_:
                $rs = 'Ch??? x??? l??';
                break;
            case static::_CHO_THUC_HIEN_:
                $rs = 'Ch??? th???c hi???n';
                break;
            case static::_DA_HOAN_THANH_:
                $rs = '???? ho??n th??nh';
                break;
            case static::_HUY_BOI_SALON_:
                $rs = 'Hu??? b???i salon';
                break;
            case static::_HUY_BOI_KHACH_:
                $rs = 'Hu??? b???i kh??ch';
                break;
            case static::_HUY_DO_QUA_HAN_XU_LY:
                $rs = 'Hu??? do qu?? h???n x??? l??';
                break;
            case static::_KHACH_KHONG_DEN_:
                $rs = 'Kh??ch kh??ng ?????n';
                break;
        }
        return $rs;
    }

    public static function getPaymentMethods(){
        return [
            [
                'id' => 'salon',
                'title' => 'Thanh to??n t???i salon',
                'desc' => 'Kh??ch thanh to??n t???i salon ph???c v??? d???ch v???, c??c h??nh th???c thanh to??n tu??? thu???c v??o t???ng salon',
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