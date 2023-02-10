<?php
Route::group([
    'prefix' => 'manager'
],function (){
    Route::group(['prefix' => 'location'],  function (){
        Route::post('lv1',
            'Modules\ModHairWorld\Http\Controllers\api\AuthController@locationLv1'
        );
        Route::post('lv2',
            'Modules\ModHairWorld\Http\Controllers\api\AuthController@locationLv2'
        );
        Route::post('lv3',
            'Modules\ModHairWorld\Http\Controllers\api\AuthController@locationLv3'
        );
    });

    Route::post('auth/login', 'Modules\ModHairWorld\Http\Controllers\ApiManager\AuthController@login');
    Route::post('sms-verify/new', 'Modules\ModHairWorld\Http\Controllers\ApiManager\SMSController@newCode');
    Route::post('sms-verify/check', 'Modules\ModHairWorld\Http\Controllers\ApiManager\SMSController@checkCode');
    Route::post('auth/reset-password', 'Modules\ModHairWorld\Http\Controllers\ApiManager\AuthController@resetPassword');
    Route::post('account/reset-password',
        'Modules\ModHairWorld\Http\Controllers\api\AuthController@resetPassword');
    Route::post('verify-phone-code',
        'Modules\ModHairWorld\Http\Controllers\api\AuthController@verifyPhoneCode');

    Route::post('account/new-password',
        'Modules\ModHairWorld\Http\Controllers\api\AuthController@newPassword');

    Route::group([
        'middleware' => ['org_auth:api'],
    ] ,function ()
    {
        Route::middleware( 'scopes:manager')->group(function (){

            Route::middleware(\Modules\ModHairWorld\Http\Middleware\AutoGetSalon::class)->group(function(){
                
                Route::get('salon/short-info', 'Modules\ModHairWorld\Http\Controllers\ApiManager\SalonController@shortInfo');
                Route::post('salon/open', 'Modules\ModHairWorld\Http\Controllers\ApiManager\SalonController@open');

                Route::get('home-screen', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@home');
                Route::get('faqs-screen', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@faqs');

                Route::get('notification/count','Modules\ModHairWorld\Http\Controllers\ApiManager\NotificationController@count');
                Route::get('notification/list','Modules\ModHairWorld\Http\Controllers\ApiManager\NotificationController@listItems');
                Route::post('notification/delete','Modules\ModHairWorld\Http\Controllers\ApiManager\NotificationController@delete');
                Route::post('notification/{notification}/read','Modules\ModHairWorld\Http\Controllers\ApiManager\NotificationController@read');

                Route::get('edit-salon/stylists', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@salonStylists');
                Route::post('edit-salon/stylists/create', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@createStylist');
                Route::post('edit-salon/stylists/{stylist}/remove', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@removeStylist');

                Route::get('edit-salon/brands', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@salonBrands');
                Route::post('edit-salon/brands/create', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@createBrands');
                Route::post('edit-salon/brands/{brand}/remove', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@removeBrand');

                Route::get('edit-salon/showcases', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@salonShowcases');
                Route::post('edit-salon/showcases/create', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@salonShowcasesCreate');
                Route::post('edit-salon/showcases/{showcase}/update', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@salonShowcasesUpdate');
                Route::post('edit-salon/showcases/{showcase}/remove', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@removeShowcase');

                Route::get('edit-salon/map', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@locationMap');
                Route::post('edit-salon/map', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@locationMapUpdate');

                Route::get('edit-salon/time', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@workTimes');
                Route::post('edit-salon/time', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@workTimesUpdate');

                Route::get('edit-salon/service-cats', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@serviceCats');
                Route::get('edit-salon/service-cats/{cat}', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@serviceCatServices');
                Route::get('edit-salon/service/{service}', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@service');

                Route::post('edit-salon/service/create', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@serviceCreate');

                Route::post('edit-salon/service/{service}/update', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@serviceUpdate');

                Route::post('edit-salon/service/{service}/remove', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@serviceRemove');


                Route::get('edit-salon/basic-info', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@basicInfo');
                Route::post('edit-salon/basic-info', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@basicInfoSave');


                Route::get('customer/list', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@customerList');
                Route::get('customer/{user}/history', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@customerHistory');
                Route::post('change-password', 'Modules\ModHairWorld\Http\Controllers\api\ProfileController@changePassword');

                Route::get('get-tos',
                    'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@getTos');

                Route::get('get-app-intro',
                    'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@getAppIntro');


                Route::get('edit-salon/sales', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@sales');
                Route::get('edit-salon/not-sales', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@notSales');

                Route::post('edit-salon/sales/{service}/create', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@salesCreate');
                Route::post('edit-salon/sales/{sale}/remove', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@salesRemove');

                Route::get('edit-salon/bank-info', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@bankInfo');
                Route::post('edit-salon/bank-info', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@bankInfoSave');

                Route::get('edit-salon/payment-supports', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@paymentSupports');
                Route::post('edit-salon/payment-supports', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@paymentSupportsSave');

                Route::get('rating-screen', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@rating');
                Route::get('rating-screen/ratings', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@ratingDetail');

                Route::get('rating-screen/accept', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@accept');
                Route::get('rating-screen/cancel', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@cancel');

                Route::get('rating-screen/badges', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@badges');

                Route::get('rating-screen/reviews', 'Modules\ModHairWorld\Http\Controllers\ApiManager\ScreenController@reviews');

                Route::get('news/{news}', 'Modules\ModHairWorld\Http\Controllers\ApiManager\HomeNewsController@newsDetail');

                Route::get('booking/new/next', 'Modules\ModHairWorld\Http\Controllers\ApiManager\BookingController@newBooking');
                Route::post('booking/new/accept', 'Modules\ModHairWorld\Http\Controllers\ApiManager\BookingController@acceptNewBooking');
                Route::post('booking/new/cancel', 'Modules\ModHairWorld\Http\Controllers\ApiManager\BookingController@cancelNewBooking');
                Route::get('booking/new/detail', 'Modules\ModHairWorld\Http\Controllers\ApiManager\BookingController@newBookingDetail');
                Route::get('booking/new/list', 'Modules\ModHairWorld\Http\Controllers\ApiManager\BookingController@newBookingList');
                Route::get('booking/cancel-reasons', 'Modules\ModHairWorld\Http\Controllers\ApiManager\BookingController@getCancelReasons');

                Route::get('booking/waiting/next', 'Modules\ModHairWorld\Http\Controllers\ApiManager\BookingController@nextWaitingBooking');
                Route::get('booking/waiting/count', 'Modules\ModHairWorld\Http\Controllers\ApiManager\BookingController@countWaitingBooking');
                Route::get('booking/waiting/list', 'Modules\ModHairWorld\Http\Controllers\ApiManager\BookingController@listWaitingBooking');
                Route::get('booking/waiting/today', 'Modules\ModHairWorld\Http\Controllers\ApiManager\BookingController@todayWaitingBooking');

                Route::get('booking/may-done/next', 'Modules\ModHairWorld\Http\Controllers\ApiManager\BookingController@nextMayDoneBooking');
                Route::get('booking/may-done/list', 'Modules\ModHairWorld\Http\Controllers\ApiManager\BookingController@listMayDoneBooking');
                Route::post('booking/detail', 'Modules\ModHairWorld\Http\Controllers\ApiManager\BookingController@detail');

                Route::get('booking/change-request/{change}/info', 'Modules\ModHairWorld\Http\Controllers\ApiManager\BookingController@changeRequestInfo');

                Route::post('booking/change-request/{change}/accept', 'Modules\ModHairWorld\Http\Controllers\ApiManager\BookingController@changeRequestAccept');
                Route::post('booking/change-request/{change}/cancel', 'Modules\ModHairWorld\Http\Controllers\ApiManager\BookingController@changeRequestCancel');



                Route::post('booking/finish', 'Modules\ModHairWorld\Http\Controllers\ApiManager\BookingController@finish');
                Route::post('booking/not-come', 'Modules\ModHairWorld\Http\Controllers\ApiManager\BookingController@notCome');


                Route::get('booking/today/done/list', 'Modules\ModHairWorld\Http\Controllers\ApiManager\BookingController@todayDoneList');
                Route::get('booking/today/income/list', 'Modules\ModHairWorld\Http\Controllers\ApiManager\BookingController@todayIncomeList');

                Route::get('booking/this-week/done/list', 'Modules\ModHairWorld\Http\Controllers\ApiManager\BookingController@thisWeekDoneList');
                Route::get('booking/this-week/waiting/list', 'Modules\ModHairWorld\Http\Controllers\ApiManager\BookingController@thisWeekWaitingList');
                Route::get('booking/this-week/list', 'Modules\ModHairWorld\Http\Controllers\ApiManager\BookingController@thisWeekList');
                Route::get('booking/this-month/list', 'Modules\ModHairWorld\Http\Controllers\ApiManager\BookingController@thisMonthList');
                Route::get('booking/this-month/done/list', 'Modules\ModHairWorld\Http\Controllers\ApiManager\BookingController@thisMonthDoneList');
                Route::get('booking/all-time/list', 'Modules\ModHairWorld\Http\Controllers\ApiManager\BookingController@allTimeList');
                Route::get('booking/stats', 'Modules\ModHairWorld\Http\Controllers\ApiManager\BookingController@bookingFilter');

                Route::get('income/today', 'Modules\ModHairWorld\Http\Controllers\ApiManager\IncomeController@today');

                Route::get('income/this-week', 'Modules\ModHairWorld\Http\Controllers\ApiManager\IncomeController@thisWeek');
                Route::get('income/this-month', 'Modules\ModHairWorld\Http\Controllers\ApiManager\IncomeController@thisMonth');
                Route::get('income/all-time', 'Modules\ModHairWorld\Http\Controllers\ApiManager\IncomeController@allTime');
            });


            Route::post('account/change-password', 'Modules\ModHairWorld\Http\Controllers\ApiManager\AccountController@changePassword');
        });
    });

});
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 29-May-18
 * Time: 10:48
 */

namespace Modules\ModHairWorld\Http\Requests;


use App\Http\Requests\Ajax;

class SalonExtendedInfoUpdate extends Ajax
{
    public function rules()
    {
        $rs = [];
        if($this->has('extra_infos')){
            $rs['extra_infos.*.icon'] = ['required'];
            $rs['extra_infos.*.title'] = ['required'];
            $rs['extra_infos.*.content'] = ['required'];
        }
        return $rs;
    }

    public function messages()
    {
        $rs = [];
        if($this->has('extra_infos')){
            $rs['extra_infos.*.icon.required'] = _('Vui lòng nhập thông tin này');
            $rs['extra_infos.*.title.required'] = _('Vui lòng nhập thông tin này');
            $rs['extra_infos.*.content.required'] = _('Vui lòng nhập thông tin này');
        }
        return $rs;
    }
}