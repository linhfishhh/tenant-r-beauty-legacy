<?php

adminRoutes(function (){
   Route::middleware(['permission.has:'.\Modules\ModFAQ\Entities\FAQ::getEditPermissionID()])->group(function (){
       Route::post(\Modules\ModFAQ\Entities\FAQ::getTypeSlug().'/send-notify',
           'Modules\ModFAQ\Http\Controllers\ModFAQController@sendNotify')->name('backend.faq.send_notify');
   });
});
