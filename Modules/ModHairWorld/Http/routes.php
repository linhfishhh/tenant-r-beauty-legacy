<?php


Route::get('facebook-thumb/{upload}/{file_name}', 'Modules\ModHairWorld\Http\Controllers\frontend\FacebookThumbController@thumb')->name('frontend.facebook_thumb');

Route::group(['middleware' => 'api-nl'], function (){


    Route::any('nl-webservice',
        'Modules\ModHairWorld\Http\Controllers\frontend\NganLuongController@webservice');

    Route::any('one-pay-check', 'Modules\ModHairWorld\Http\Controllers\OnepayController@check')->name('onepay.check');
    Route::any('one-pay-cancel-web', 'Modules\ModHairWorld\Http\Controllers\OnepayController@cancel')->name('onepay.cancel.web');
    Route::any('one-pay-cancel-link', 'Modules\ModHairWorld\Http\Controllers\OnepayController@cancelLink')->name('onepay.cancel.link');
    Route::any('one-pay-check-web', 'Modules\ModHairWorld\Http\Controllers\frontend\CartController@checkOnePay')->name('onepay.check.web');});

Route::prefix( 'api' )
->middleware( 'api' )
->group( __DIR__.'/api-routes.php' );


Route::group([
    'middleware' => 'web',
], function () {

    Route::get('ads/data/google-tag.csv', 'Modules\ModHairWorld\Http\Controllers\AdsController@exportGoogleTag');

    Route::get('sitemap.xml','Modules\ModHairWorld\Http\Controllers\SiteMapController@index');
    Route::get('sitemap_basic.xml','Modules\ModHairWorld\Http\Controllers\SiteMapController@basic')->name('frontend.sitemap.basic');
    Route::get('sitemap_bai_viet_{year}_{month}.xml','Modules\ModHairWorld\Http\Controllers\SiteMapController@news')->name('frontend.sitemap.news');
    Route::get('sitemap_salons_{lv1}_{lv2}.xml','Modules\ModHairWorld\Http\Controllers\SiteMapController@salons')->name('frontend.sitemap.salons');


    Route::get('dang-ky-salon', 'Modules\ModHairWorld\Http\Controllers\BecomeSalonManagerController@register')->name('frontend.salon_register');
    Route::post('dang-ky-salon', 'Modules\ModHairWorld\Http\Controllers\BecomeSalonManagerController@submitRegister')->name('frontend.salon_register.submit');

    Route::middleware(['guest'])->group(
        function () {
            Route::group(
                ['namespace' => 'App\Http\Controllers'],
                function () {
                    Route::get(
                        'social-connect/{provider}',
                        'Auth\LoginController@social'
                    )->name('frontend.login.social');
                    Route::get(
                        'social-connect/{provider}/check',
                        'Auth\LoginController@socialCheck'
                    )->name('frontend.login.social.check');
                }
            );
        });
});
Route::post(
    'login/account-kit',
    'App\Http\Controllers\Auth\LoginController@socialLoginV2'
)->name('frontend.login.socialLoginV2')->middleware('web');
Route::post(
    'social/v3/login',
    'Modules\ModHairWorld\Http\Controllers\api\AuthController@loginWithFirebase'
)->name('api.auth.loginWithFirebase')->middleware('web');
Route::group([
    'middleware' => 'web',
    'namespace' => 'Modules\ModHairWorld\Http\Controllers'
], function (){
    Route::post('refresh-verify-code', 'frontend\AccountController@refreshVerifyCode')->name('frontend.refresh_verify_code');
    Route::middleware(['guest'])->group(
        function () {
            Route::post('dang-nhap',
                'frontend\AuthController@login')
            ->name('frontend.login.check');
            Route::post('request-reset-password', 'frontend\AccountController@requestResetPassword')->name('frontend.request_password_reset');
            Route::post('request-reset-password/check', 'frontend\AccountController@requestResetPasswordCheck')->name('frontend.request_password_reset_check');

            Route::post('request-reset-password/save', 'frontend\AccountController@requestResetPasswordSave')->name('frontend.request_password_reset_save');


            Route::post('register-step-one', 'frontend\AccountController@registerStepOne')->name('frontend.account.register.step_one');
            Route::post('register-step-two', 'frontend\AccountController@registerStepTwo')->name('frontend.account.register.step_two');
            Route::post('social-add-phone', 'frontend\AccountController@socialAddPhone')->name('frontend.social_add_phone');
            Route::post('social-create-account', 'frontend\AccountController@socialCreateAccount')->name('frontend.social_create_account');
        }
    );

    Route::post('dat-cho/prepay', 'frontend\CartController@createOrder')->name('frontend.cart.createOrder');
    Route::get('dat-cho/chon-ngay-gio', 'frontend\CartController@stepOne')->name('frontend.cart.1');
    Route::post('dat-cho/chon-ngay-gio', 'frontend\CartController@stepOneSave')->name('frontend.cart.1.save');

    Route::get('dat-cho/chi-tiet-thanh-toan', 'frontend\CartController@stepTwo')->name('frontend.cart.2');


    Route::post('dat-cho/bo-dich-vu', 'frontend\CartController@removeItem')->name('frontend.cart.remove_item');
    Route::post('dat-cho/them-bot', 'frontend\CartController@updateAmount')->name('frontend.cart.update_amount');


    Route::post('service/{service}/add-to-cart',
        'frontend\SalonController@addToCart')
    ->name('frontend.service.add_to_cart');

    Route::post('service/{service}/options',
        'frontend\SalonController@getOptions')
    ->name('frontend.service.options');

    Route::get('service/{service}', 'frontend\SalonController@serviceDetail')
    ->name('frontend.service.detail');

    Route::get('salon/{salon}/review/filter/rating-list',
        'frontend\SalonController@getReviewFilterRatingListByServiceCat')
    ->name('frontend.salon.review.filter.rating_list_by_service_cat');
    Route::get('salon/{salon}/review/list', 'frontend\SalonController@getReviews')
    ->name('frontend.salon.review.list');


    Route::post('salon/review/add', 'frontend\SalonController@addCommentReview')
    ->name('frontend.salon.review.add');


    Route::get('lien-he', function (){
        return view(getThemeViewName('contact'), []);
    })->name('frontend.contact');

    Route::get('tim-kiem', 'frontend\SearchController@search')->name('frontend.search');
    Route::get('tim-kiem-v2', 'frontend\SearchController@searchv2')->name('frontend.searchv2');
    Route::get('tim-kiem-v2-locations', 'frontend\SearchController@searchv2Locations')->name('frontend.searchv2.locations');
    Route::get('tim-kiem-v2-cats', 'frontend\SearchController@searchv2Cats')->name('frontend.searchv2.cats');

    Route::group([
        'middleware' => 'user'
    ], function (){

        Route::get('brand-sms/test', 'BrandSmsController@test');

        Route::post('dat-cho/chi-tiet-thanh-toan', 'frontend\CartController@stepTwoSave_v2')->name('frontend.cart.2.save');
        Route::post('dat-cho/chi-tiet-thanh-toan/add-address', 'frontend\CartController@stepTwoAddAddress')->name('frontend.cart.2.add_address');
        Route::get('dat-cho/don-hang/{order}', 'frontend\CartController@stepThree')->name('frontend.cart.order');

        Route::get('review/{review}/like', 'frontend\SalonController@likeReview')->name('frontend.salon.review.like');

        Route::get('salon/{salon}/like', 'frontend\SalonController@like')->name('frontend.salon.like');
        Route::get('salon/{salon}/showcase/{showcase}/like', 'frontend\SalonController@showcaseLike')->name('frontend.salon.showcase.like');
        Route::get(
            'test-notify',
            'frontend\AccountController@testNotify'
        )->name('test.notify');

        Route::redirect('tai-khoan','tai-khoan/trang-ca-nhan');

        Route::get('tai-khoan/hoi-dap', function (){
            $faqs = \Modules\ModFAQ\Entities\FAQ::getPublicIndexQuery()->get();
            return view(getThemeViewName('account.help'), [
                'faqs' => $faqs
            ]);
        })->name('frontend.account.help');

        Route::get('tai-khoan/trang-ca-nhan', 'frontend\AccountController@profile'
    )->name('frontend.account.profile');

        Route::get('tai-khoan/mat-khau', 'frontend\AccountController@resetPassword')
        ->name('frontend.account.reset_password');

        Route::post('tai-khoan/mat-khau', 'frontend\AccountController@saveNewPassword')
        ->name('frontend.account.reset_password.save');

        Route::get('tai-khoan/payment',
            'frontend\AccountController@editPaymentMethod'
        )->name('frontend.account.payment');
        Route::post('tai-khoan/payment',
            'frontend\AccountController@savePaymentMethod'
        )->name('frontend.account.payment.save');

        Route::get('tai-khoan/share', function (){
            return view(getThemeViewName('account.share'), []);
        })->name('frontend.account.share');

        Route::get('tai-khoan/thong-bao/kiem-tra','frontend\AccountController@checkUnread'
    )->name('frontend.account.notification.check');

        Route::get('tai-khoan/thong-bao','frontend\AccountController@viewNotifications'
    )->name('frontend.account.notification');
        Route::get('tai-khoan/thong-bao/doc/{id}','frontend\AccountController@readNotification'
    )->name('frontend.account.notification.read');
        Route::get('tai-khoan/thong-bao/xoa/{id}','frontend\AccountController@removeNotification'
    )->name('frontend.account.notification.remove');
        Route::get('tai-khoan/thong-bao/danh-sach','frontend\AccountController@listNotification'
    )->name('frontend.account.notification.list');

        Route::get('tai-khoan/chinh-sua','frontend\AccountController@editProfile'
    )->name('frontend.account.edit');

        Route::post('tai-khoan/chinh-sua/avatar', 'frontend\AccountController@saveAvatar')
        ->name('frontend.account.edit.avatar.save');

        Route::post('tai-khoan/chinh-sua/thong-tin-co-ban', 'frontend\AccountController@saveBasicInfo')
        ->name('frontend.account.edit.basic_info.save');

        Route::post('tai-khoan/chinh-sua/thong-tin-dia-chi', 'frontend\AccountController@saveAddresses')
        ->name('frontend.account.edit.addresses.save');

        Route::delete('tai-khoan/chinh-sua/thong-tin-dia-chi/{address}', 'frontend\AccountController@deleteAddress')
        ->name('frontend.account.edit.addresses.delete');

        Route::post('tai-khoan/chinh-sua/phone/check', 'frontend\AccountController@checkNewPhone')
        ->name('frontend.account.phone.check');

        Route::post('tai-khoan/chinh-sua/phone/save', 'frontend\AccountController@saveNewPhone')
        ->name('frontend.account.phone.save');

        Route::get('tai-khoan/fav/salon/list', 'frontend\AccountController@listFavSalon'
    )->name('frontend.account.fav_Salon.list');

        Route::get('tai-khoan/fav/showcase/list', 'frontend\AccountController@listFavShowcase'
    )->name('frontend.account.fav_showcase.list');

        Route::delete('tai-khoan/fav/salon',
            'frontend\AccountController@removeFavSalon'
        )->name('frontend.account.fav_Salon.remove');

        Route::delete('tai-khoan/fav/showcase',
            'frontend\AccountController@removeFavShowcase'
        )->name('frontend.account.fav_showcase.remove');

        Route::get('tai-khoan/fav/salon', function (){
            return view(getThemeViewName('account.fav'), [
                'salon' => 1
            ]);
        })->name('frontend.account.fav_Salon');
        Route::get('account/fav/showcase', function (){
            return view(getThemeViewName('account.fav'), [
                'salon' => 0
            ]);
        })->name('frontend.account.fav_showcase');
        Route::get('account/history', 'frontend\AccountController@history')->name('frontend.account.history');
        Route::get('account/history/detail/{order}', 'frontend\AccountController@historyDetail')->name('frontend.account.history.detail');

    });
    //web
Route::get('danh-muc-tin', function (){
    return view(getThemeViewName('test.news.index'), []);
})->name('test.news.index');

Route::get('tin/1', function (){
    return view(getThemeViewName('test.news.post_1'), []);
})->name('test.news.post.1');
Route::get('tin/2', function (){
    return view(getThemeViewName('test.news.post_2'), []);
})->name('test.news.post.2');
Route::get('salon/post', function (){
    return view(getThemeViewName('test.salon.post'), []);
})->name('test.salon.post');


Route::get('cart/1', function (){
    return view(getThemeViewName('test.cart.cart_step_1'), []);
})->name('test.cart.1');
Route::get('cart/2', function (){
    return view(getThemeViewName('test.cart.cart_step_2'), []);
})->name('test.cart.2');
Route::get('cart/3', function (){
    return view(getThemeViewName('test.cart.cart_step_3'), []);
})->name('test.cart.3');

Route::get('salon/{salon}/{location_slug}/{slug?}', 'frontend\SalonController@post')
        //->where('slug','^((?!like).)*$')
->name('frontend.salon');
});

adminRoutes(function (){

    Route::get('service-cat-ordering', 'Modules\ModHairWorld\Http\Controllers\Backend\ServiceCatController@index')->name('backend.service_cat_ordering.index');
    Route::post('service-cat-ordering', 'Modules\ModHairWorld\Http\Controllers\Backend\ServiceCatController@update')->name('backend.service_cat_ordering.update');


    Route::group(
        [
            'middleware' => 'permission.has:manage_salon_register'
        ],
        function(){
            Route::get('salon-register', 'Modules\ModHairWorld\Http\Controllers\BecomeSalonManagerController@backendIndex')->name('backend.salon_register.index');
            Route::delete('salon-register', 'Modules\ModHairWorld\Http\Controllers\BecomeSalonManagerController@backendDestroy')->name('backend.salon_register.destroy');
            Route::put('salon-register/handle', 'Modules\ModHairWorld\Http\Controllers\BecomeSalonManagerController@backendHandle')->name('backend.salon_register.handle');
        });

    Route::group([
        'middleware' => 'permission.has:manage_option_promo_configs'
    ], function(){
        Route::get('promo-salons', 'Modules\ModHairWorld\Http\Controllers\Backend\PromoController@index')->name('backend.promo_salons.index');

        Route::get('promo-salons/salons', 'Modules\ModHairWorld\Http\Controllers\Backend\PromoController@salons')->name('backend.promo_salons.salons');

        Route::post('promo-salons/add/{salon}', 'Modules\ModHairWorld\Http\Controllers\Backend\PromoController@addSalon')->name('backend.promo_salons.add');

        Route::delete('promo-salons', 'Modules\ModHairWorld\Http\Controllers\Backend\PromoController@destroy')->name('backend.promo_salons.destroy');
    });

    Route::group(
        [
            'middleware' => 'permission.has:manage_salons',
        ],
        function (){
            Route::get('top-salons', 'Modules\ModHairWorld\Http\Controllers\Backend\TopSalonController@index')->name('backend.top_salons.index');

            Route::get('top-salons/show/{id}', 'Modules\ModHairWorld\Http\Controllers\Backend\TopSalonController@show')->name('backend.top_salons.show');

            Route::post('top-salons/show/{id}', 'Modules\ModHairWorld\Http\Controllers\Backend\TopSalonController@postshow')->name('backend.top_salons.postshow');

            Route::get('top-salons/salons', 'Modules\ModHairWorld\Http\Controllers\Backend\TopSalonController@salons')->name('backend.top_salons.salons');

            Route::get('top-salons/list', 'Modules\ModHairWorld\Http\Controllers\Backend\TopSalonController@list')->name('backend.top_salons.list');

            Route::post('top-salons/add/{salon}', 'Modules\ModHairWorld\Http\Controllers\Backend\TopSalonController@addSalon')->name('backend.top_salons.add');

            Route::delete('top-salons/show/{id}', 'Modules\ModHairWorld\Http\Controllers\Backend\TopSalonController@destroySalon')->name('backend.top_salons.destroySalon');
            //
            
            Route::get('custom-salons', 'Modules\ModHairWorld\Http\Controllers\Backend\TopSalonController@indexcustom')->name('backend.list_salons.indexcustom');

            Route::get('custom-salons/salons', 'Modules\ModHairWorld\Http\Controllers\Backend\TopSalonController@salons')->name('backend.list_salons.salons');

            Route::post('custom-salons/add', 'Modules\ModHairWorld\Http\Controllers\Backend\TopSalonController@addCustomsalons')->name('backend.list_salons.add');

            Route::delete('custom-salons', 'Modules\ModHairWorld\Http\Controllers\Backend\TopSalonController@destroycustom')->name('backend.list_salons.destroycustom');

            Route::get('notification', 'Modules\ModHairWorld\Http\Controllers\PushNotificationController@index')->name('backend.notification.index');
            Route::post('notification', 'Modules\ModHairWorld\Http\Controllers\PushNotificationController@send')->name('backend.notification.send');

            Route::get('booking',
                'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@bookings')
            ->name('backend.bookings.index');
            ;
            Route::delete('booking',
                'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@bookingsDestroy')
            ->name('backend.bookings.destroy');

            Route::put('booking/{order}',
                'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@bookingsUpdate')
            ->name('backend.bookings.update');

            Route::get('salon/{salon}/stats',
               'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@salonStatCount')
            ->name('backend.salon.stats');
            Route::get('salon',
               'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@index')
            ->name('backend.salon.index');
            Route::get('salon/{salon}/basic-info',
               'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@editBasicInfo')
            ->name('backend.salon.basic_info.edit');

            Route::get('salon/create',
                'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@createSalon')
            ->name('backend.salon.create');
            Route::post('salon/create',
                'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@storeSalon')
            ->name('backend.salon.store');

            Route::delete('salon/{salon}',
                'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@deleteSalon')->name('backend.salon.destroy');

            Route::post('salon/{salon}/clone',
                'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@cloneSalon')->name('backend.salon.clone');

            Route::put('salon/{salon}/basic-info',
               'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@updateBasicInfo')
            ->name('backend.salon.basic_info.update');
            Route::get('salon/{salon}/extended-info',
               'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@editExtendedInfo')
            ->name('backend.salon.extended_info.edit');
            Route::put('salon/{salon}/extended-info',
               'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@updateExtendedInfo')
            ->name('backend.salon.extended_info.update');
            Route::get('salon/{salon}/managers',
               'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@editManagers')
            ->name('backend.salon.managers.edit');
            Route::post('salon/{salon}/managers',
                'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@storeManager')
            ->name('backend.salon.managers.store');
            Route::delete('salon/{salon}/managers',
                'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@destroyManager')
            ->name('backend.salon.managers.destroy');
            Route::get('salon/{salon}/time',
               'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@editTime')
            ->name('backend.salon.time.edit');
            Route::post('salon/{salon}/time',
               'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@storeTime')
            ->name('backend.salon.time.store');

            Route::get('salon/{salon}/stylist',
               'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@editStylist')
            ->name('backend.salon.stylist.edit');
            Route::post('salon/{salon}/stylist',
                'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@storeStylist')
            ->name('backend.salon.stylist.store');
            Route::put('salon/{salon}/stylist',
                'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@updateStylist')
            ->name('backend.salon.stylist.update');

            Route::delete('salon/{salon}/stylist',
              'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@destroyStylist')
            ->name('backend.salon.stylist.destroy');


            Route::get('salon/{salon}/gallery',
                'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@editGallery')
            ->name('backend.salon.gallery.edit');
            Route::post('salon/{salon}/gallery',
                'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@storeGallery')
            ->name('backend.salon.gallery.store');
            Route::put('salon/{salon}/gallery',
                'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@updateGallery')
            ->name('backend.salon.gallery.update');
            Route::delete('salon/{salon}/gallery',
                'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@destroyGallery')
            ->name('backend.salon.gallery.destroy');

            Route::get('salon/{salon}/brand',
               'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@editBrand')
            ->name('backend.salon.brand.edit');
            Route::post('salon/{salon}/brand',
                'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@storeBrand')
            ->name('backend.salon.brand.store');
            Route::put('salon/{salon}/brand',
               'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@updateBrand')
            ->name('backend.salon.brand.update');
            Route::delete('salon/{salon}/brand',
              'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@destroyBrand')
            ->name('backend.salon.brand.destroy');

            Route::get('salon/{salon}/showcase',
               'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@editShowcase')
            ->name('backend.salon.showcase.edit');
            Route::post('salon/{salon}/showcase',
                'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@storeShowcase')
            ->name('backend.salon.showcase.store');
            Route::put('salon/{salon}/showcase',
               'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@updateShowcase')
            ->name('backend.salon.showcase.update');
            Route::delete('salon/{salon}/showcase',
              'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@destroyShowcase')
            ->name('backend.salon.showcase.destroy');

            Route::get('salon/{salon}/service',
                'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@editService')
            ->name('backend.salon.service.edit');
            Route::post('salon/{salon}/service',
                'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@storeService')
            ->name('backend.salon.service.store');
            Route::put('salon/{salon}/service',
                'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@updateService')
            ->name('backend.salon.service.update');
            Route::delete('salon/{salon}/service',
                'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@destroyService')
            ->name('backend.salon.service.destroy');

            Route::post('salon/{salon}/service/{service}/options',
                'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@updateServiceOptions')
            ->name('backend.salon.service.options.update');

            Route::post('salon/{salon}/service/{service}/included-options',
                'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@updateServiceIncludedOptions')
            ->name('backend.salon.service.includedoptions.update');

            Route::get('salon/{salon}/sale',
                'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@editSales')
            ->name('backend.salon.sale.edit');
            Route::post('salon/{salon}/sale',
                'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@storeSales')
            ->name('backend.salon.sale.store');
            Route::put('salon/{salon}/sale',
                'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@updateSales')
            ->name('backend.salon.sale.update');
            Route::delete('salon/{salon}/sale',
                'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@destroySales')
            ->name('backend.salon.sale.destroy');
            Route::delete('salon/{salon}/sales',
                'Modules\ModHairWorld\Http\Controllers\Backend\SalonController@destroySalesAvd')
            ->name('backend.salon.sales.destroy');
        });

Route::group(
    [
        'middleware' => 'permission.has:salon_import_tool',
        'prefix' => 'salon-tools'
    ], function(){
        Route::get('import', 'Modules\ModHairWorld\Http\Controllers\ToolController@importIndex')->name('salon_tools.import');
        Route::post('import', 'Modules\ModHairWorld\Http\Controllers\ToolController@importDo')->name('salon_tools.import.do');
        Route::post('import/rollback', 'Modules\ModHairWorld\Http\Controllers\ToolController@rollBack')->name('salon_tools.import.rollback');

    });
});

Route::get('info/tinh-thanh-pho-list', 'Modules\ModHairWorld\Http\Controllers\ModHairWorldController@getInfoTinhThanhPho')->name('info.tinh_thanh_pho.list');
Route::get('info/tinh-thanh-pho-list/get-from-ids', 'Modules\ModHairWorld\Http\Controllers\ModHairWorldController@getInfoTinhThanhPhoFromIds')->name('info.tinh_thanh_pho.from_ids');
Route::get('info/quan-huyen-list', 'Modules\ModHairWorld\Http\Controllers\ModHairWorldController@getInfoQuanHuyen')->name('info.quan_huyen.list');
Route::get('info/phuong-xa-list', 'Modules\ModHairWorld\Http\Controllers\ModHairWorldController@getInfoPhuongXa')->name('info.phuong_xa.list');



