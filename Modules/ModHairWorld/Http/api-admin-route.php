<?php

Route::group([
  'prefix' => 'admin'
],function (){
  Route::post('auth/login', 'Modules\ModHairWorld\Http\Controllers\ApiAdmin\AuthController@login')->name('admin.auth.login');

  Route::group([
    'middleware' => ['org_auth:api'],
  ] ,function ()
  {
    Route::middleware( 'scopes:admin')->group(function (){
      Route::post('notify/new_order', 'Modules\ModHairWorld\Http\Controllers\ApiAdmin\NotificationController@notifyNewProductOrder');

      Route::post('notify/create_salon_wallet', 'Modules\ModHairWorld\Http\Controllers\ApiAdmin\NotificationController@notifyCreateSalonWallet');

      Route::post('notify/update_salon_wallet', 'Modules\ModHairWorld\Http\Controllers\ApiAdmin\NotificationController@notifyUpdateSalonWallet');

      Route::post('notify/salon', 'Modules\ModHairWorld\Http\Controllers\ApiAdmin\NotificationController@notifySalonManager');
    });
  });

});
