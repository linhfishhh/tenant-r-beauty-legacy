<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/25/18
 * Time: 15:49
 */

namespace Modules\ModHairWorld\Http\Controllers;

use Modules\ModHairWorld\Entities\BrandSmsErrorLog;

class BrandSmsController
{
    private $settings;
    private $client;

    public function __construct()
    {
        $settings = getSettingsFromPage('brandsms_configs');
        $settings = collect($settings);
        $this->settings = $settings;

        $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c24iOiJpc2Fsb24iLCJzaWQiOiI2YzhkZTgxYS00ZTUzLTRhNWMtYTQyZi05Yjk5OWUxNGFlNDUiLCJvYnQiOiIiLCJvYmoiOiIiLCJuYmYiOjE1OTcwNTI3OTEsImV4cCI6MTU5NzA1NjM5MSwiaWF0IjoxNTk3MDUyNzkxfQ.c85_LBUs8JxoerskBhZ3SN0p6fJB-jIg0UGA2BCQcDs';
        if (isset($settings['token'])) {
            $token = $settings['token'];
        }
        if (isset($settings['brandsms_endpoint'])) {
            $endPoint = $settings['brandsms_endpoint'];
        }
        \Log::info('end point: '.$endPoint);

        $options = [
            'base_uri' => 'https://api.brandsms.vn',
            'headers' => [
                'Content-Type' => 'application/json; charset=utf-8',
                'token' => $token,
            ],
        ];
        $client = new \GuzzleHttp\Client($options);
        $this->client = $client;
    }

    private function call($action, $params = []){
        $rs = false;
        try{
            if ($this->settings->get('brandsms_disable')){
                $rs = true;
            } else {
                if ($this->client) {
                    $client = $this->client;
                    $rs = $client->request('POST', $action, $params);
                }
            }
        }
        catch (\Exception $exception){
            \Log::info('Error in call '.$action.' with params:');
            \Log::info(var_export($params));
            \Log::info('Error message: '.$exception->getMessage());
            $rs = false;
        }
        return $rs;
    }

    function getBalance(){
        $rs = false;
        $temp = $this->call('getBalance');
        if($temp){
            if(isset($temp['getBalanceResult'])){
                $result = $temp['getBalanceResult'];
                $rs = $result['error_code'] == 0?$result['balance']:false;
            }
        }
        return $rs;
    }

    function sendSms($phone, $message){
        $rs = new \Exception('Lỗi không xác định khi gửi tin nhắn xác nhận',-100);
        $body = '{
                "to":"'.$phone.'",
                "from":"'.$this->settings->get('brandsms_brandname', 'iSalon.vn').'",
                "message":"'.$message.'",
                "type":1,
                "scheduled":""
            }';
        \Log::info('request body: '. $body);
        $temp = $this->call('/api/SMSBrandname/SendSMS', [
            'body' => $body
        ]);
        if($temp){
            $data = $temp->getBody()->getContents();
            \Log::info($data);
            $result = json_decode($data, true);
            if(isset($result['errorCode']) && $result['errorCode'] != '000') {
                $rs = new \Exception($result['errorMessage']);
            } else {
                $rs = $result['referentId'];
            }
        }
        else{
            BrandSmsErrorLog::newLog($phone, $message,'Lỗi call api','');
        }
        return $rs;
    }

    function test(){
        $rs = $this->sendSms('0915872549','test from dev server rand='.rand(111111, 9999999));
        return $rs;
    }
}