<?php

Route::group(
    [
        'middleware' => 'web',
        'namespace'  => 'Modules\ModContact\Http\Controllers'
    ],
    function () {
        Route::post(
            'gui-lien-he',
            'ModContactController@contactStore'
        )->name('frontend.contact.store');
    }
);

Route::group(
    [
        'prefix' => 'api',
        'middleware' => 'api',
        'namespace'  => 'Modules\ModContact\Http\Controllers\api'
    ],
    function () {
        Route::post(
            'gui-lien-he',
            'ContactController@contactStore'
        );
    }
);

adminRoutes(function (){
    Route::group(
        [
            'namespace' => 'Modules\ModContact\Http\Controllers',
            'middleware' => 'permission.has:manage_contact'
        ],
        function(){
            Route::get('contact', 'ModContactController@backendIndex')->name('backend.contact.index');
            Route::delete('contact', 'ModContactController@backendDestroy')->name('backend.contact.destroy');
            Route::put('contact/handle', 'ModContactController@backendHandle')->name('backend.contact.handle');
            Route::get('contact/mail-list', 'ModContactController@backendGetMailList')->name('backend.contact.mail_list.get');
            Route::post('contact/mail-list', 'ModContactController@backendSetMailList')->name('backend.contact.mail_list.set');
            Route::post('contact/{contact}/reply', 'ModContactController@reply')->name('backend.contact.reply');
        });
});
