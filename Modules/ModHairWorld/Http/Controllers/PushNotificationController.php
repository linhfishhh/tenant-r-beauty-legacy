<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/8/18
 * Time: 14:21
 */

namespace Modules\ModHairWorld\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use function GuzzleHttp\Psr7\parse_query;

class PushNotificationController extends Controller
{
    function index(Request $request)
    {
        return view(getThemeViewName('backend.pages.notification.index'));
    }

    function send(Request $request)
    {
        \Validator::validate($request->all(),
            ['content' => ['required'], 'title' => ['required']], ['content.required' => 'Vui lòng nhập nội dung', 'title.required' => 'Vui lòng nhập tiêu đề']);
        $target = $request->get('target', 'web');
        $location = $request->get('location', []);
        if (!is_array($location)) {
            $location = [$location];
        }
        $content = $request->get('content');
        $title = $request->get('title');
        $link_type = $request->get('link_type', url(''));
        $link_value = $request->get('link_value', url(''));
        $limit_user_id = $request->get('limit_user_id');

        $params = [
            'headings' => [
                'en' => $title
            ],
            'contents' => [
                'en' => $content
            ],
            'data' => [
                'type' => 'ads'
            ],
            'filters' => [],
            'url' => '',
            'priority' => 10,
            'large_icon' => 'ic_stat_onesignal_large_icon',
            'android_sound' => 'noti2',
            'ios_sound' => 'noti2.wav',
            'ios_badgeType' => 'Increase',
            'ios_badgeCount' => 1,
        ];
        $client = null;
        switch ($target){
            case 'web':
                $params['data']['scope'] = 'web';
                $params['included_segments'][] = 'Web';
                $params['isIos'] = false;
                $params['isAndroid'] = false;
                $params['isAnyWeb'] = true;
                $client = OneSignalController::getCustomerClient();
                if($link_type == 'web-link'){
                    if($link_value){
                        $params['url'] = $link_value;
                    }
                }
                break;
            case 'booking':
                $params['data']['scope'] = 'customer';
                $params['included_segments'][] = 'Mobile';
                $params['isIos'] = true;
                $params['isAndroid'] = true;
                $params['isAnyWeb'] = false;
                $client = OneSignalController::getCustomerClient();
                switch($link_type){
                    case 'web-link':
                        if($link_value){
                            $params['url'] = $link_value;
                        }
                        break;

                    case 'home':
                        $params['data']['route'] = [
                            'home',
                            [
                                'tabIndex' => 1
                            ]
                        ];
                        break;

                    case 'history':
                        $params['data']['route'] = [
                            'home',
                            [
                                'tabIndex' => 3
                            ]
                        ];
                        break;
                    case 'salon':
                        $params['data']['route'] = [
                            'home_salon',
                            [
                                'id' => $link_value
                            ]
                        ];
                        break;
                    case 'salon-list':
                        $url = parse_url($link_value);
                        $query = false;
                        if($url){
                            $query_ = $url['query'];
                            if($query_){
                                parse_str($url['query'], $query_);
                                foreach ($query_ as $name=>$item){
                                    if($item){
                                        $query[$name] = $item;
                                    }
                                }
                            }
                        }
                        $params['data']['route'] = [
                            'home_result',
                            [
                                'query' => $query
                            ]
                        ];
                        break;

                }
                break;
            case 'manager':
                $params['data']['scope'] = 'salon';
                $client = OneSignalController::getManagerClient();
                if($link_type == 'web-link'){
                    if($link_value){
                        $params['url'] = $link_value;
                    }
                }
                else{
                    switch ($link_type){
                        case 'rating':
                            $params['data']['route'] = [
                                'home',
                                [
                                    'tabIndex' => 2
                                ]
                            ];
                            break;
                        case 'month-income':
                            $params['data']['route'] = [
                                'booking_history_list',
                                [
                                ]
                            ];
                            break;
                        case 'news':
                            $params['data']['route'] = [
                                'home_news',
                                [
                                    'id' => $link_value
                                ]
                            ];
                            break;
                    }
                }
                break;
        }

        foreach ($location as $lIndex => $l) {
            if($lIndex > 0){
                $params['filters'][] = [
                    "operator" => "OR"
                ];
            }
            $params['filters'][] = [
                "field" => "tag",
                'key' => 'location',
                'relation' => '=',
                'value' => $l
            ];
        }
        if($limit_user_id){
            if($params['filters']){
                $params['filters'][] = [
                    "operator" => "AND"
                ];
            }
            $params['filters'][] = [
                "field" => "tag",
                'key' => 'user_id',
                'relation' => '=',
                'value' => $limit_user_id
            ];
        }

        $client->sendNotificationCustom($params);
        return \Response::json($params);
    }
}