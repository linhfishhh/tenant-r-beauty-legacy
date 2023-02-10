<?php

namespace Modules\ModHairWorld\Handlers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Modules\ModHairWorld\Entities\SalonOrder;

trait SalonWalletHandler {

    public function validateBookService($total, $amountCoin, $spendMaxCoin)
    {
        $user = me();
        $body = '{
            "amountCoin":"' . $amountCoin . '",
            "spendMaxCoin":"' . (isset($spendMaxCoin) ? ($spendMaxCoin ? 'true' : 'false') : 'false') . '",
            "total": "' . $total . '",
            "userId":"' . $user->id . '"
            }';
        try {
            $requestClient = new RequestClient();
            $client = $requestClient->create();
            $response = $client->request('POST', '/r-isalon/payment/validate-book-service', ['body' => $body]);
            $validation = json_decode($response->getBody())->data;
            return $validation;
        } catch (\Exception $ex) {
            \Log::info('Validate booking service failed by error: ' . $ex->getMessage() . '; code: ' . $ex->getCode() .
                '; line: ' . $ex->getLine() . '; in file: ' . $ex->getFile());
            if (isset($user) && $user) {
                \Log::info('post data:');
                \Log::info(json_encode($user));
            }
        }
        return null;
    }

    public function bookService(SalonOrder $order, $total, $spendMaxCoin, $amountCoin)
    {
        $user = me();
        $body = '{
            "amountCoin":' . (isset($amountCoin) ? $amountCoin : 'null'). ',
            "spendMaxCoin":' . (isset($spendMaxCoin) ? ($spendMaxCoin ? 'true' : 'false') : 'false') . ',
            "orderId":'. $order->id. ',
            "salonId":'. $order->salon_id. ',
            "total": "' . $total . '",
            "userId":"' . $user->id . '"
            }';
        try {
            $requestClient = new RequestClient();
            $client = $requestClient->create();
            $response = $client->request('POST', '/r-isalon/payment/book-service', ['body' => $body]);
            $validation = json_decode($response->getBody())->data;
            return $validation;
        } catch (\Exception $ex) {
            \Log::info('Book service failed by error: ' . $ex->getMessage() . '; code: ' . $ex->getCode() .
                '; line: ' . $ex->getLine() . '; in file: ' . $ex->getFile());
            if (isset($user) && $user) {
                \Log::info('post data:');
                \Log::info(json_encode($user));
            }
        }
        return null;
    }

    public function updatePaymentStatus(SalonOrder $order, $isSuccess = null)
    {
        if ($isSuccess != null) {
            $body = '{
                "orderId":' . $order->id . ',
                "isSuccess":' . ($isSuccess ? 'true' : 'false') . ',
                "isBooking": true,
                "userId": ' . $order->user_id . ',
                "salonId": ' . $order->salon_id . ',
                "amountCoin": ' . $order->amount_coin . ',
                "amountMoney": ' . $order->amount_money . ',
                "totalAmount": ' . $order->total . '
                }';
        } else {
            $body = '{
                "orderId":' . $order->id . ',
                "isBooking": true,
                "userId": ' . $order->user_id . ',
                "salonId": ' . $order->salon_id . ',
                "amountCoin": ' . $order->amount_coin . ',
                "amountMoney": ' . $order->amount_money . ',
                "totalAmount": ' . $order->total . '
                }';
        }
        try {
            $requestClient = new RequestClient();
            $client = $requestClient->create();
            $response = $client->request('POST', '/r-isalon/payment/update-status', ['body' => $body]);
            $data = json_decode($response->getBody())->data;
            return $data;
        } catch (\Exception $ex) {
            \Log::info('Update payment status failed by error: ' . $ex->getMessage() . '; code: ' . $ex->getCode() .
                '; line: ' . $ex->getLine() . '; in file: ' . $ex->getFile());
        }
        return null;
    }

    public function syncSalonWallet($salonId)
    {
        $body = '{
            "salonId":'. $salonId. '
            }';
        try {
            $requestClient = new RequestClient();
            $client = $requestClient->create();
            $response = $client->request('POST', '/r-isalon/salon-wallets/create', ['body' => $body]);
            $data = json_decode($response->getBody())->data;
            return $data;
        } catch (\Exception $ex) {
            \Log::info('Sync salon wallet failed by error: ' . $ex->getMessage() . '; code: ' . $ex->getCode() .
                '; line: ' . $ex->getLine() . '; in file: ' . $ex->getFile());
        }
        return null;
    }
}
