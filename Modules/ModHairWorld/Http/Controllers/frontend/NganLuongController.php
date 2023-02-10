<?php

namespace Modules\ModHairWorld\Http\Controllers\frontend;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use nusoap_server;

class NganLuongController extends Controller
{

    function webservice(Request $request){
        $server = new nusoap_server();
        $server->configureWSDL('WS_WITH_SMS','NS', url('nl-webservice'));
        $server->wsdl->schemaTargetNamespace='NS';
        $server->register('UpdateOrder',array('transaction_info'=>'xsd:string','order_code'=>'xsd:string','payment_id'=>'xsd:int','payment_type'=>'xsd:int','secure_code'=>'xsd:string'),array('result'=>'xsd:int'),'NS');
        $server->register('RefundOrder',
            array(
                'transaction_info'=>'xsd:string',
                'order_code'=>'xsd:string',
                'payment_id'=>'xsd:int',
                'refund_payment_id'=>'xsd:int',
                'refund_amount'=>'xsd:int',
                'payment_type'=>'xsd:int',
                'refund_description'=>'xsd:string',
                'secure_code'=>'xsd:string'),
            array('result'=>'xsd:int'),
            'NS');
        $rawPostData = file_get_contents("php://input");
        return \Response::make($server->service($rawPostData), 200, array('Content-Type' => 'text/xml; charset=ISO-8859-1'));
    }
}