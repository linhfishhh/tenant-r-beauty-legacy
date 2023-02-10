<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 8/24/18
 * Time: 20:56
 */

namespace Modules\ModHairWorld\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Modules\ModHairWorld\Entities\SalonOrder;

class OnepayController extends Controller
{
    function cancelLink(Request $request){
        $mid = $request->get('mid');
        $id = $request->get('id');
        $booking =  SalonOrder::where('id', $id)->where('online_payment_extra',$mid)->first();
        if($booking){
            if($booking->status === SalonOrder::_CHO_XU_LY_){
                $booking->status = SalonOrder::_HUY_BOI_KHACH_;
                $booking->save();
            }
        }
        return Redirect::route('frontend.index');
    }

    function cancel(Request $request){
        \Debugbar::disable();
        $mid = $request->get('mid');
        $id = $request->get('id');
        $booking =  SalonOrder::where('id', $id)->where('online_payment_extra',$mid)->first();
        if($booking){
            if($booking->status === SalonOrder::_CHO_XU_LY_){
                $booking->status = SalonOrder::_HUY_BOI_KHACH_;
                $booking->save();
            }
        }
        return view('modhairworld::onepay_cancel');
    }

     static function doCheck($redirect=false, $cancel_link=false){
        \Debugbar::disable();
        try{
            $txnResponseCode =  static::null2unknown($_GET ["vpc_TxnResponseCode"]);
            if($txnResponseCode == 255){
                return [
                    'success' => false,
                    'message' => 'Lỗi từ hệ thống OnePay (code: 255)',
                    'retry' => false,
                    'cancel' => false,
                    'id' => false
                ];
            }

            $merchTxnRef = static::null2unknown($_GET ["vpc_MerchTxnRef"]);
            $booking = false;
            if($merchTxnRef){
                $ap = explode('---', $merchTxnRef);
                $id = $ap[0]*1;
                $booking = SalonOrder::where('id', $id)->where('status', 0)->first();
            }
            if(!$booking){
                return [
                    'success' => false,
                    'message' => 'Lỗi không xác định',
                    'retry' => false,
                    'cancel' => false,
                    'id' => false
                ];
            }

            $SECURE_SECRET = "A3EFDFABA8653DF2342E8DAC29B51AF0";
            $vpc_Txn_Secure_Hash = $_GET ["vpc_SecureHash"];
            unset ($_GET ["vpc_SecureHash"]);
            $errorExists = false;
            ksort($_GET);

            if (strlen($SECURE_SECRET) > 0 && $_GET ["vpc_TxnResponseCode"] != "7" && $_GET ["vpc_TxnResponseCode"] != "No Value Returned") {

                $stringHashData = "";

                foreach ($_GET as $key => $value) {
                    if ($key != "vpc_SecureHash" && (strlen($value) > 0) && ((substr($key, 0, 4) == "vpc_") || (substr($key, 0, 5) == "user_"))) {
                        $stringHashData .= $key . "=" . $value . "&";
                    }
                }
                $stringHashData = rtrim($stringHashData, "&");


                if (strtoupper($vpc_Txn_Secure_Hash) == strtoupper(hash_hmac('SHA256', $stringHashData, pack('H*', $SECURE_SECRET)))) {

                    $hashValidated = "CORRECT";
                } else {

                    $hashValidated = "INVALID HASH";
                }
            } else {
                $hashValidated = "INVALID HASH";
            }


            $amount = static::null2unknown($_GET ["vpc_Amount"]);
            $locale = static::null2unknown($_GET ["vpc_Locale"]);
            $command = static::null2unknown($_GET ["vpc_Command"]);
            $version = static::null2unknown($_GET ["vpc_Version"]);
            $orderInfo = static::null2unknown($_GET ["vpc_OrderInfo"]);
            $merchantID = static::null2unknown($_GET ["vpc_Merchant"]);
            $transactionNo = static::null2unknown($_GET ["vpc_TransactionNo"]);
            $txnResponseCode = static::null2unknown($_GET ["vpc_TxnResponseCode"]);

            $transStatus = "";
            if($hashValidated=="CORRECT" && $txnResponseCode=="0"){
                $transStatus = "Giao dịch thành công";
            }elseif ($hashValidated=="INVALID HASH" && $txnResponseCode=="0"){
                $transStatus = "Giao dịch đang chờ";
            }else {
                $transStatus = "Giao dịch thất bại";
            }
            if($txnResponseCode == 0){
                if($booking->status === SalonOrder::_CHO_XU_LY_){
                    $booking->status = SalonOrder::_CHO_THANH_TOAN_;
                    $booking->save();
                }
                else{
                    return [
                        'success' => false,
                        'message' => 'Đơn hàng không khả dụng để thanh toán',
                        'retry' => false,
                        'cancel' => false,
                        'id' => false
                    ];
                }
            }
            return [
                'success' => $txnResponseCode == 0,
                'message' => static::getResponseDescription($txnResponseCode),
                'retry' => $txnResponseCode == 0? false : $booking->getPaymentLink('onepay',$redirect),
                'cancel' => $cancel_link?$cancel_link.'?mid='.$booking->online_payment_extra.'&id='.$booking->id:route('onepay.cancel.web',['mid' => $booking->online_payment_extra, 'id' => $booking->id]),
                'id' => $txnResponseCode == 0 ? $booking->id : false
            ];
        }
        catch (\Exception $exception){
            return [
                'success' => false,
                'message' => 'Lỗi từ hệ thống OnePay: '.$exception->getMessage(),
                'retry' => false,
                'cancel' => false,
                'id' => false
            ];
        }
    }

    function check(Request $request)
    {
        $rs = static::doCheck();
        \Debugbar::disable();
        return view('modhairworld::onepay', $rs);

    }

    static function null2unknown($data) {
        if ($data == "") {
            return "No Value Returned";
        } else {
            return $data;
        }
    }

    static function getResponseDescription($responseCode) {

        switch ($responseCode) {
            case "0" :
                $result = "Giao dịch thành công - Approved";
                break;
            case "1" :
                $result = "Ngân hàng từ chối giao dịch - Bank Declined";
                break;
            case "3" :
                $result = "Mã đơn vị không tồn tại - Merchant not exist";
                break;
            case "4" :
                $result = "Không đúng access code - Invalid access code";
                break;
            case "5" :
                $result = "Số tiền không hợp lệ - Invalid amount";
                break;
            case "6" :
                $result = "Mã tiền tệ không tồn tại - Invalid currency code";
                break;
            case "7" :
                $result = "Lỗi không xác định - Unspecified Failure ";
                break;
            case "8" :
                $result = "Số thẻ không đúng - Invalid card Number";
                break;
            case "9" :
                $result = "Tên chủ thẻ không đúng - Invalid card name";
                break;
            case "10" :
                $result = "Thẻ hết hạn/Thẻ bị khóa - Expired Card";
                break;
            case "11" :
                $result = "Thẻ chưa đăng ký sử dụng dịch vụ - Card Not Registed Service(internet banking)";
                break;
            case "12" :
                $result = "Ngày phát hành/Hết hạn không đúng - Invalid card date";
                break;
            case "13" :
                $result = "Vượt quá hạn mức thanh toán - Exist Amount";
                break;
            case "21" :
                $result = "Số tiền không đủ để thanh toán - Insufficient fund";
                break;
            case "99" :
                $result = "Người sủ dụng hủy giao dịch - User cancel";
                break;
            default :
                $result = "Giao dịch thất bại - Failured";
        }
        return $result;
    }
}