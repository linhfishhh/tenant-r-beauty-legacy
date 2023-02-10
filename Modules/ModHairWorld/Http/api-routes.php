<?php
Route::post('login',
    'Modules\ModHairWorld\Http\Controllers\api\AuthController@login');
Route::get('/api_demo', function () {
    dd(Session::getId());
});
Route::post('social/login',
    'Modules\ModHairWorld\Http\Controllers\api\AuthController@socialLogin');
Route::post('social/v2/login',
    'Modules\ModHairWorld\Http\Controllers\api\AuthController@socialLoginV2')->name('api.auth.socialLoginV2');
Route::post('social/v3/login',
    'Modules\ModHairWorld\Http\Controllers\api\AuthController@loginWithFirebase')->name('api.auth.loginWithFirebase');
Route::post('social/create-account',
    'Modules\ModHairWorld\Http\Controllers\api\AuthController@socialCreateAccount');
Route::post('social/v3/send-verification-code',
    'Modules\ModHairWorld\Http\Controllers\api\AuthController@sendVerificationCode')->name('api.auth.sendVerificationCode');
Route::post('social/v3/login-sms',
    'Modules\ModHairWorld\Http\Controllers\api\AuthController@loginWithFirebaseOrSms')->name('api.auth.loginWithFirebaseOrSms');

Route::group(['prefix' => 'register'], function () {
    Route::post('check',
        'Modules\ModHairWorld\Http\Controllers\api\AuthController@checkRegister');
});

Route::post('verify-phone',
    'Modules\ModHairWorld\Http\Controllers\api\AuthController@sendPhoneVerify');

Route::post('verify-phone-code',
    'Modules\ModHairWorld\Http\Controllers\api\AuthController@verifyPhoneCode');

Route::post('get-join-tos',
    'Modules\ModHairWorld\Http\Controllers\api\AuthController@getJoinTos');

Route::post('account/create',
    'Modules\ModHairWorld\Http\Controllers\api\AuthController@createAccount');

Route::post('account/reset-password',
    'Modules\ModHairWorld\Http\Controllers\api\AuthController@resetPassword');

Route::post('account/new-password',
    'Modules\ModHairWorld\Http\Controllers\api\AuthController@newPassword');

Route::post('account/test-sms',
    'Modules\ModHairWorld\Http\Controllers\api\AuthController@testSMS');

Route::group(['prefix' => 'location'], function () {
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

Route::post('search/map-radius', 'Modules\ModHairWorld\Http\Controllers\api\SearchController@mapRadius');
Route::post('search/salon-near-me', 'Modules\ModHairWorld\Http\Controllers\api\SearchController@salonNearMe');
Route::post('search/top-salons', 'Modules\ModHairWorld\Http\Controllers\api\SearchController@topSalons');
Route::get('search/top-cities', 'Modules\ModHairWorld\Http\Controllers\api\SearchController@topCities');
Route::get('search/top-deals', 'Modules\ModHairWorld\Http\Controllers\api\SearchController@topDeals');
Route::get('search/top-brands', 'Modules\ModHairWorld\Http\Controllers\api\SearchController@topBrands');
Route::post('search/all', 'Modules\ModHairWorld\Http\Controllers\api\SearchController@listSalons');
Route::post('search/v2/by-keyword', 'Modules\ModHairWorld\Http\Controllers\api\SearchController@searchSalons');

Route::post('verify-personal-info-step-one',
    'Modules\ModHairWorld\Http\Controllers\api\AuthController@verifyInfoStepOne');
Route::post('verify-personal-info-step-two',
    'Modules\ModHairWorld\Http\Controllers\api\AuthController@verifyInfoStepTwo');

Route::group([
    'prefix' => 'home'
], function () {
    Route::post('index', 'Modules\ModHairWorld\Http\Controllers\api\HomeController@index');
});

Route::group([
    'prefix' => 'search'
], function () {
    Route::post('location-list', 'Modules\ModHairWorld\Http\Controllers\api\SearchV2Controller@searchLocationListLV1');
    Route::post('/v2', 'Modules\ModHairWorld\Http\Controllers\api\SearchV2Controller@search');
    Route::post('hints', 'Modules\ModHairWorld\Http\Controllers\api\SearchV2Controller@searchHint')->name('api.search_hint');
    //v2
    Route::post('location-list/{lv1}', 'Modules\ModHairWorld\Http\Controllers\api\SearchV2Controller@searchLocationListLV2');
    Route::post('location-find', 'Modules\ModHairWorld\Http\Controllers\api\SearchV2Controller@searchLocationFind')->name('api.location_find');
    Route::post('configs', 'Modules\ModHairWorld\Http\Controllers\api\SearchV2Controller@searchConfigs');

    Route::post('by-keyword', 'Modules\ModHairWorld\Http\Controllers\api\SearchController@searchByKeyword');

    Route::post('get-service-categories', 'Modules\ModHairWorld\Http\Controllers\api\SearchController@serviceCategories');
});

Route::get('banners', 'Modules\ModHairWorld\Http\Controllers\api\BannerController@index');

Route::get('banner-gird', 'Modules\ModHairWorld\Http\Controllers\api\BannerController@getBannerGird');

Route::group([
    'middleware' => [\Modules\ModHairWorld\Http\Middleware\AutoLogin::class],
    'prefix' => 'salon'
], function () {
    Route::post('{salon}/info', 'Modules\ModHairWorld\Http\Controllers\api\SalonController@info');
    Route::post('{salon}/reviews', 'Modules\ModHairWorld\Http\Controllers\api\SalonController@reviews');
    Route::post('{salon}/reviews/alt', 'Modules\ModHairWorld\Http\Controllers\api\SalonController@reviewsMore');
    Route::post('{salon}/open-time-list', 'Modules\ModHairWorld\Http\Controllers\api\SalonController@openTimeList');
}
);

Route::group([
    'middleware' => [\Modules\ModHairWorld\Http\Middleware\AutoLogin::class],
    'prefix' => 'service'
], function () {
    Route::post('{service}/reviews', 'Modules\ModHairWorld\Http\Controllers\api\ServiceController@reviews');
}
);


Route::post('faqs',
    'Modules\ModHairWorld\Http\Controllers\api\FAQController@faqs');

Route::group([
    'middleware' => [\Modules\ModHairWorld\Http\Middleware\AutoLogin::class],
], function () {

    Route::post('search/full-search', 'Modules\ModHairWorld\Http\Controllers\api\SearchController@fullSearch');
    Route::post('search/get-featured-items', 'Modules\ModHairWorld\Http\Controllers\api\SearchController@getFeaturedItems');

    Route::post('salon/{salon}/detail', 'Modules\ModHairWorld\Http\Controllers\api\SalonController@detail');
    Route::post('service/{service}/detail', 'Modules\ModHairWorld\Http\Controllers\api\ServiceController@service');


});

Route::get('promo-salons/html', 'Modules\ModHairWorld\Http\Controllers\api\PromoController@salonsHtml')->name('promo_Salons.html');

Route::group([
    'middleware' => ['org_auth:api'],
], function () {

    Route::middleware('scopes:customer')->group(function () {


        Route::group([
            'prefix' => 'salon'
        ], function () {
            Route::post('{salon}/like', 'Modules\ModHairWorld\Http\Controllers\api\SalonController@like');
            Route::post('{salon}/likeV2', 'Modules\ModHairWorld\Http\Controllers\api\SalonController@likeV2');
        }
        );

        Route::group([
            'prefix' => 'showcase'
        ], function () {
            Route::post('{showcase}/like', 'Modules\ModHairWorld\Http\Controllers\api\ShowcaseController@like');
        }
        );


        Route::post('account/info',
            'Modules\ModHairWorld\Http\Controllers\api\AuthController@getAccountInfo');

        Route::group([
            'prefix' => 'notification'
        ], function () {
            Route::post('count', 'Modules\ModHairWorld\Http\Controllers\api\NotificationController@count');
            Route::post('list', 'Modules\ModHairWorld\Http\Controllers\api\NotificationController@listItems');
            Route::post('delete', 'Modules\ModHairWorld\Http\Controllers\api\NotificationController@delete');
            Route::post('{notification}/read', 'Modules\ModHairWorld\Http\Controllers\api\NotificationController@read');
        });

        Route::group([
            'prefix' => 'booking'
        ], function () {
            Route::post('check-status', 'Modules\ModHairWorld\Http\Controllers\api\BookingController@checkStatus');
            Route::post('add', 'Modules\ModHairWorld\Http\Controllers\api\BookingController@add');
            Route::post('add/v2', 'Modules\ModHairWorld\Http\Controllers\api\BookingController@addV2');
            Route::post('update', 'Modules\ModHairWorld\Http\Controllers\api\BookingController@update');
            Route::post('create', 'Modules\ModHairWorld\Http\Controllers\api\BookingController@create');
            Route::post('address-info/new', 'Modules\ModHairWorld\Http\Controllers\api\BookingController@newAddressInfo');
            Route::post('payment/link', 'Modules\ModHairWorld\Http\Controllers\api\BookingController@paymentLink');
            Route::post('cancel', 'Modules\ModHairWorld\Http\Controllers\api\BookingController@cancel');
            Route::post('history', 'Modules\ModHairWorld\Http\Controllers\api\BookingController@history');
            Route::post('detail', 'Modules\ModHairWorld\Http\Controllers\api\BookingController@detail');
            Route::post('waiting', 'Modules\ModHairWorld\Http\Controllers\api\BookingController@waiting');
            Route::post('crits', 'Modules\ModHairWorld\Http\Controllers\api\BookingController@crits');
            Route::post('{order}/change-time/info', 'Modules\ModHairWorld\Http\Controllers\api\BookingController@changeTimeInfo');
            Route::post('{order}/change-time/request', 'Modules\ModHairWorld\Http\Controllers\api\BookingController@changeTimeRequest');
            Route::post('{salon}/cart-items', 'Modules\ModHairWorld\Http\Controllers\api\BookingController@CartItems');
        });

        Route::group([
            'prefix' => 'fav'
        ], function () {
            Route::post('list', 'Modules\ModHairWorld\Http\Controllers\api\FAVController@listFav');
            Route::post('salon/delete', 'Modules\ModHairWorld\Http\Controllers\api\FAVController@deleteSalonLike');
            Route::post('showcase/delete', 'Modules\ModHairWorld\Http\Controllers\api\FAVController@deleteShowcaseLike');
        });


        Route::group([
            'prefix' => 'profile'
        ], function () {
            Route::post('get', 'Modules\ModHairWorld\Http\Controllers\api\ProfileController@get');
            Route::post('update', 'Modules\ModHairWorld\Http\Controllers\api\ProfileController@update');
            Route::post('update-phone', 'Modules\ModHairWorld\Http\Controllers\api\ProfileController@updatePhone');
            Route::post('update-email', 'Modules\ModHairWorld\Http\Controllers\api\ProfileController@updateEmail');
            Route::post('change-password', 'Modules\ModHairWorld\Http\Controllers\api\ProfileController@changePassword');
        });


        Route::group([
            'prefix' => 'review'
        ], function () {
            Route::post('{review}/like', 'Modules\ModHairWorld\Http\Controllers\api\ReviewController@like');
            Route::post('services-to-review', 'Modules\ModHairWorld\Http\Controllers\api\ReviewController@ServiceToReview');
            Route::post('new', 'Modules\ModHairWorld\Http\Controllers\api\ReviewController@newReview');
        }
        );

        Route::group([
            'prefix' => 'history'
        ], function () {
            Route::get('get', 'Modules\ModHairWorld\Http\Controllers\api\HistoryController@get');
            Route::post('add', 'Modules\ModHairWorld\Http\Controllers\api\HistoryController@store');
        }
        );
    });
});
Route::post('custom-list-salon', 'Modules\ModHairWorld\Http\Controllers\api\CustomSalonsController@index');

Route::post('promo-salons', 'Modules\ModHairWorld\Http\Controllers\api\PromoController@salons');

Route::get('become-salon-manager-config', 'Modules\ModHairWorld\Http\Controllers\api\SettingController@becomeSalonManagerConfig');
Route::get('contact-config', 'Modules\ModHairWorld\Http\Controllers\api\SettingController@getContactConfig');
Route::get('page-config', 'Modules\ModHairWorld\Http\Controllers\api\SettingController@getPageConfig');

require_once __DIR__ . '/api-manager-route.php';
require_once __DIR__ . '/api-admin-route.php';