<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 8/23/18
 * Time: 22:47
 */

namespace Modules\ModHairWorld\Http\Controllers\ApiManager;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ModHairWorld\Entities\Salon;
use Modules\ModHairWorld\Entities\SalonOrder;
use Modules\ModHairWorld\Entities\SalonOrderItem;

/**
 * @resource Thống kê thu nhập
 *
 * Những request này yêu cầu token ở header
 */
class IncomeController extends Controller
{
    /**
     * @param Salon $salon
     * @return array
     */
    public static function getTodayIncome(Salon $salon)
    {
        $today = date_format(\Carbon\Carbon::now(), "Y-m-d");

        $done_booking = SalonOrder::whereDate('service_time', '>=', $today . ' 00:00:00')
            ->whereDate('service_time', '<=', \Carbon\Carbon::now()->toDateTimeString())
            ->where(['salon_id' => $salon->id, 'status' => 3])->get();

        $wait_booking = SalonOrder::whereDate('service_time', '>=', $today . ' 00:00:00')
            ->whereDate('service_time', '<=', \Carbon\Carbon::now()->toDateTimeString())
            ->where(['salon_id' => $salon->id, 'status' => 2])->get();

        $total_done_booking = count($done_booking);
        $sum_done_income = 0;
        foreach ($done_booking as $val) {
            foreach ($val->items as $item) {
                $sum_done_income += $item->quatity * $item->price;
            }
        }

        $total_wait_booking = count($wait_booking);
        $sum_wait_income = 0;
        foreach ($wait_booking as $val) {
            foreach ($val->items as $item) {
                $sum_wait_income += $item->quatity * $item->price;
            }
        }

        $total_booking = $total_done_booking + $total_wait_booking;
        $sum_income = $sum_done_income + $sum_wait_income;

        $data = [
            'total_booking' => $total_booking,
            'sum_income' => $sum_income,
            'done_booking' => [
                'total_booking' => $total_done_booking,
                'sum_income' => $sum_done_income
            ],
            'waiting_booking' => [
                'total_booking' => $total_wait_booking,
                'sum_income' => $sum_wait_income
            ]
        ];
        return $data;
    }

    /**
     * @param Salon $salon
     * @return array
     */
    public static function getThisWeekIncome(Salon $salon)
    {
        $current_start_of_week = \Carbon\Carbon::now()->startOfWeek();
        $current_end_of_week = \Carbon\Carbon::now()->endOfWeek();

        $done_booking = SalonOrder::whereDate('service_time', '>=', $current_start_of_week)
            ->whereDate('service_time', '<=', $current_end_of_week)
            ->where(['salon_id' => $salon->id, 'status' => 3])->get();

        $wait_booking = SalonOrder::whereDate('service_time', '>=', $current_start_of_week)
            ->whereDate('service_time', '<=', $current_end_of_week)
            ->where(['salon_id' => $salon->id, 'status' => 2])->get();

        $total_done_booking = count($done_booking);
        $sum_done_income = 0;
        foreach ($done_booking as $val) {
            foreach ($val->items as $item) {
                $sum_done_income += $item->quatity * $item->price;
            }
        }

        $total_wait_booking = count($wait_booking);
        $sum_wait_income = 0;
        foreach ($wait_booking as $val) {
            foreach ($val->items as $item) {
                $sum_wait_income += $item->quatity * $item->price;
            }
        }

        $total_booking = $total_done_booking + $total_wait_booking;
        $sum_income = $sum_done_income + $sum_wait_income;

        $data = [
            'total_booking' => $total_booking,
            'sum_income' => $sum_income,
            'done_booking' => [
                'total_booking' => $total_done_booking,
                'sum_income' => $sum_done_income
            ],
            'waiting_booking' => [
                'total_booking' => $total_wait_booking,
                'sum_income' => $sum_wait_income
            ]
        ];
        return $data;
    }

    /**
     * Thu nhập tuần này*
     *
     * Thống kê thu nhập của tuần này tính từ ngày đầu tuần đến hiện tại
     *
     */
    function thisWeek(Request $request, Salon $salon)
    {
        $data = self::getThisWeekIncome($salon);

        return \Response::json($data);
    }

    /**
     * Thu nhập hôm nay*
     *
     * Thống kê thu nhập của hôm nay này tính từ 0 giờ đến hiện tại
     *
     */
    function today(Request $request, Salon $salon)
    {
        $data = self::getTodayIncome($salon);

        return \Response::json($data);
    }


    /**
     * Thu nhập tháng này*
     *
     * Thống kê thu nhập của tháng này tính từ ngày 1 đến hiện tại
     *
     */
    function thisMonth(Request $request, Salon $salon)
    {
        $data = [
            'total_booking' => '',
            'sum_income' => '',   
            'done_booking' => [
                'total_booking' => '',
                'sum_income' => ''
            ],
            'waiting_booking' => [
                'total_booking' => '',
                'sum_income' => ''
            ]
        ];
        $current_month_year = \Carbon\Carbon::now()->format('Y-m');

        $done_booking =  SalonOrder::whereDate('service_time', '>=', $current_month_year.'-01')
                        ->whereDate('service_time', '<=', \Carbon\Carbon::now()->toDateTimeString())
                        ->where(['salon_id' => $salon->id, 'status' => 3])->get();

        $wait_booking =  SalonOrder::whereDate('service_time', '>=', $current_month_year.'-01')
                        ->whereDate('service_time', '<=', \Carbon\Carbon::now()->toDateTimeString())
                        ->where(['salon_id' => $salon->id, 'status' => 2])->get();

        $total_done_booking = count($done_booking); 
        $sum_done_income = 0;
        foreach($done_booking as $val){
            foreach($val->items as $item){
                $sum_done_income += $item->quatity * $item->price;
            }
        }
        
        $total_wait_booking = count($wait_booking); 
        $sum_wait_income = 0;
        foreach($wait_booking as $val){
            foreach($val->items as $item){
                $sum_wait_income += $item->quatity * $item->price;
            }
        }

        $total_booking = $total_done_booking + $total_wait_booking;
        $sum_income = $sum_done_income + $sum_wait_income;

        $data = [
            'total_booking' => $total_booking,
            'sum_income' => $sum_income,   
            'done_booking' => [
                'total_booking' => $total_done_booking,
                'sum_income' => $sum_done_income
            ],
            'waiting_booking' => [
                'total_booking' => $total_wait_booking,
                'sum_income' => $sum_wait_income
            ]
        ];
        return \Response::json($data);
        // return [
        //     'total_booking' => 7,
        //     'sum_income' => 13000000,
        //     'done_booking' => [
        //         'total_booking' => 5,
        //         'sum_income' => 1000000
        //     ],
        //     'waiting_booking' => [
        //         'total_booking' => 2,
        //         'sum_income' => 300000
        //     ]
        // ];
    }

    /**
     * Thu nhập tổng*
     *
     * Thống kê thu nhập từ trước đến giờ
     *
     */
    function allTime(Request $request, Salon $salon)
    {
        $data = [
            'total_booking' => '',
            'sum_income' => '',   
            'done_booking' => [
                'total_booking' => '',
                'sum_income' => ''
            ],
            'waiting_booking' => [
                'total_booking' => '',
                'sum_income' => ''
            ]
        ];

        $done_booking =  SalonOrder::whereDate('service_time', '<=', \Carbon\Carbon::now()->toDateTimeString())
                        ->where(['salon_id' => $salon->id, 'status' => 3])->get();

        $wait_booking =  SalonOrder::whereDate('service_time', '<=', \Carbon\Carbon::now()->toDateTimeString())
                        ->where(['salon_id' => $salon->id, 'status' => 2])->get();

        $total_done_booking = count($done_booking); 
        $sum_done_income = 0;
        foreach($done_booking as $val){
            foreach($val->items as $item){
                $sum_done_income += $item->quatity * $item->price;
            }
        }
        
        $total_wait_booking = count($wait_booking); 
        $sum_wait_income = 0;
        foreach($wait_booking as $val){
            foreach($val->items as $item){
                $sum_wait_income += $item->quatity * $item->price;
            }
        }

        $total_booking = $total_done_booking + $total_wait_booking;
        $sum_income = $sum_done_income + $sum_wait_income;

        $data = [
            'total_booking' => $total_booking,
            'sum_income' => $sum_income,   
            'done_booking' => [
                'total_booking' => $total_done_booking,
                'sum_income' => $sum_done_income
            ],
            'waiting_booking' => [
                'total_booking' => $total_wait_booking,
                'sum_income' => $sum_wait_income
            ]
        ];
        return \Response::json($data);
    }


}