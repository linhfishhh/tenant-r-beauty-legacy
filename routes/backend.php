<?php
Route::middleware('guest')->group(function () {
    Route::get('login', 'Backend\LoginController@showLoginForm')->name('backend.login.index');
    Route::post('login', 'Backend\LoginController@login')->name('backend.login.login');
});
Route::middleware('auth:backend.login.index,web', 'permission.has:access_backend')->group(function () {
    Route::get('', function () {
        return Redirect::route('backend.dashboard.index');
    })->name('backend.index');

    Route::get('dashboard', 'Backend\DashboardController@index')->name('backend.dashboard.index');

    Route::get('logout', 'Backend\LoginController@logout')->name('backend.logout');

	//region Profile
	Route::get('profile', 'Backend\ProfileController@edit')->name('backend.profile.edit');
    Route::put('profile', 'Backend\ProfileController@update')->name('backend.profile.update');
	//endregion

    Route::middleware('permission.has:manage_sliders')->group(function(){
        Route::get('slider', 'Backend\SliderController@index')->name('backend.slider.index');
        Route::get('slider-test','Backend\SliderController@test');
    });

	//region User
	Route::middleware('permission.has:manage_users')->group(function () {
        Route::get('user', 'Backend\UserController@index')->name('backend.user.index');
	    Route::get('user/create', 'Backend\UserController@create')->name('backend.user.create');
	    Route::post('user', 'Backend\UserController@store')->name('backend.user.store');
	    Route::get('user/{user}/edit', 'Backend\UserController@edit')->name('backend.user.edit');
	    Route::put('user/{user}', 'Backend\UserController@update')->name('backend.user.update');
	    Route::delete('user', 'Backend\UserController@destroy')->name('backend.user.destroy');
        Route::put('user', 'Backend\UserController@put')->name('backend.user.put');
    });
	Route::get( 'user/select', 'Backend\UserController@select')->name( 'backend.user.select');
    Route::get( 'user/info', 'Backend\UserController@getInfo')->name( 'backend.user.info');

    //endregion

	//region Role
	Route::middleware('permission.has:manage_roles')->group(function () {
        Route::get('role', 'Backend\RoleController@index')->name('backend.role.index');
	    Route::get('role/create', 'Backend\RoleController@create')->name('backend.role.create');
	    Route::post('role', 'Backend\RoleController@store')->name('backend.role.store');
	    Route::get('role/{role}/edit', 'Backend\RoleController@edit')->name('backend.role.edit');
	    Route::put('role/{role}', 'Backend\RoleController@update')->name('backend.role.update');
        Route::put('role', 'Backend\RoleController@put')->name('backend.role.put');
        Route::delete('role', 'Backend\RoleController@destroy')->name('backend.role.destroy');
        Route::post('role/move', 'Backend\RoleController@move')->name('backend.role.move');
        Route::get('role/select', 'Backend\RoleController@select')->name('backend.role.select');
    });
	//endregion

	//region Menu
	Route::middleware('permission.has:manage_menu')->group(function () {
        Route::get('menu/location','Backend\MenuController@locations')->name('backend.menu.location.index');
        Route::post('menu/location/save','Backend\MenuController@saveLocations')->name('backend.menu.location.save');
        Route::get('menu/library','Backend\MenuController@library')->name('backend.menu.library.index');
        Route::get('menu/{menu}/edit', 'Backend\MenuController@editMenu')->name('backend.menu.edit');
        Route::put('menu/{menu}', 'Backend\MenuController@updateMenu')->name('backend.menu.update');
        Route::delete('menu', 'Backend\MenuController@destroyMenu')->name('backend.menu.destroy');
        Route::get('menu/create', 'Backend\MenuController@createMenu')->name('backend.menu.create');
        Route::post('menu', 'Backend\MenuController@storeMenu')->name('backend.menu.store');
        Route::post('menu/option/save', 'Backend\MenuController@saveMenuOption')->name('backend.menu.option.save');
    });
	//endregion

	//region Sidebar
	Route::middleware('permission.has:manage_sidebar')->group(function () {
        Route::get('sidebar/location','Backend\SidebarController@locations')->name('backend.sidebar.location.index');
	    Route::post('sidebar/location/save','Backend\SidebarController@saveLocations')->name('backend.sidebar.location.save');
	    Route::get('sidebar/library','Backend\SidebarController@library')->name('backend.sidebar.library.index');
        Route::get('sidebar/{sidebar}/edit', 'Backend\SidebarController@editSidebar')->name('backend.sidebar.edit');
        Route::delete('sidebar', 'Backend\SidebarController@destroySidebar')->name('backend.sidebar.destroy');
        Route::get('sidebar/{sidebar}/edit', 'Backend\SidebarController@editSidebar')->name('backend.sidebar.edit');
        Route::get('sidebar/create', 'Backend\SidebarController@createSidebar')->name('backend.sidebar.create');
	    Route::post('widget/option/save', 'Backend\SidebarController@saveWidgetOption')->name('backend.widget.option.save');
	    Route::put('sidebar/{sidebar}', 'Backend\SidebarController@updateSidebar')->name('backend.sidebar.update');
	    Route::post('sidebar', 'Backend\SidebarController@storeSidebar')->name('backend.sidebar.store');

    });
	//endregion

	//region Themes
	Route::middleware('permission.has:manage_theme')->group(function () {
        Route::get('theme','Backend\ThemeController@index')->name('backend.theme.index');
        Route::get('theme/{theme}/theme.jpg', 'Backend\ThemeController@cover')->name('backend.theme.cover');
    });
	//endregion

	//region Settings
	Route::get('setting/get', 'Backend\SettingController@get')->name('backend.setting.get');
    Route::put('setting/set', 'Backend\SettingController@set')->name('backend.setting.set');
    Route::middleware('backend.settings.page')->group(function (){
        Route::get('setting/page/{page}', 'Backend\SettingController@edit')->name('backend.setting.page.edit');
        Route::put('setting/page/{page}', 'Backend\SettingController@save')->name('backend.setting.page.save');
    });
	//endregion

	$post_types = getPostTypes();
	if(count( $post_types)>0){
		//region Post Type
		Route::middleware('post_type')->group(function () {
            Route::get( 'post/{post_type}/info', 'Backend\PostTypeController@getInfo')->name('backend.post.info');
			Route::get( 'post/{post_type}', 'Backend\PostTypeController@index' )->name( 'backend.post.index' );
			Route::get( 'post/{post_type}/{post}/edit', 'Backend\PostTypeController@edit' )->name( 'backend.post.edit' );
			Route::put( 'post/{post_type}/{post}', 'Backend\PostTypeController@update' )->name( 'backend.post.update' );
			Route::get( 'post/{post_type}/create', 'Backend\PostTypeController@create' )->name( 'backend.post.create' );
			Route::post( 'post/{post_type}', 'Backend\PostTypeController@store' )->name( 'backend.post.store' );
			Route::delete( 'post/{post_type}', 'Backend\PostTypeController@destroy' )->name( 'backend.post.destroy' );
			Route::delete( 'post/{post_type}/trash', 'Backend\PostTypeController@trash' )->name( 'backend.post.trash' );
			Route::delete( 'post/{post_type}/restore', 'Backend\PostTypeController@restore' )->name( 'backend.post.restore' );
			Route::put( 'post/{post_type}', 'Backend\PostTypeController@put')->name( 'backend.post.put');
            Route::post( 'post/{post_type}/update-term', 'Backend\PostTypeController@updateTerm')->name( 'backend.post.update_term');
            Route::get( 'post/{post_type}/counts', 'Backend\PostTypeController@counts' )->name( 'backend.post.counts' );
            Route::get( 'post/{post_type}/select', 'Backend\PostTypeController@select')->name('backend.post.select');
		});
		//endregion
		//region Taxonomy
		Route::middleware(['taxonomy', 'taxonomy.manage'])->group(function () {
			Route::get( 'taxonomy/{post_type}/{taxonomy}', 'Backend\TaxonomyController@index' )->name( 'backend.taxonomy.index' );
            Route::get( 'taxonomy/{post_type}/{taxonomy}/info', 'Backend\TaxonomyController@getInfo' )->name( 'backend.taxonomy.info' );
            Route::get( 'taxonomy/{post_type}/{taxonomy}/{term}/edit', 'Backend\TaxonomyController@edit' )->name( 'backend.taxonomy.edit' );
			Route::put( 'taxonomy/{post_type}/{taxonomy}/{term}', 'Backend\TaxonomyController@update' )->name( 'backend.taxonomy.update' );
			Route::get( 'taxonomy/{post_type}/{taxonomy}/create', 'Backend\TaxonomyController@create' )->name( 'backend.taxonomy.create' );
			Route::post( 'taxonomy/{post_type}/{taxonomy}', 'Backend\TaxonomyController@store' )->name( 'backend.taxonomy.store' );
			Route::delete( 'taxonomy/{post_type}/{taxonomy}', 'Backend\TaxonomyController@destroy' )->name( 'backend.taxonomy.destroy' );
		});

		Route::middleware('taxonomy')->group(function (){
			Route::get( 'taxonomy/{post_type}/{taxonomy}/search', 'Backend\TaxonomyController@search')->name( 'backend.taxonomy.search');
			Route::get( 'taxonomy/{post_type}/{taxonomy}/select', 'Backend\TaxonomyController@select')->name( 'backend.taxonomy.select');
		});
		//endregion

        //region Comments
            Route::middleware('post_type')->group(function () {
                Route::get('comment/{post_type}', 'Backend\CommentController@index')->name('backend.comment.index');
                Route::delete('comment/{post_type}', 'Backend\CommentController@destroy')->name('backend.comment.destroy');
                Route::put('comment/{post_type}/published', 'Backend\CommentController@published')->name('backend.comment.published');
                Route::put('comment/{post_type}/content', 'Backend\CommentController@content')->name('backend.comment.content');
                Route::get('comment/{post_type}/{post}', 'Backend\CommentController@postIndex')->name('backend.comment.post.index');
            });
        //endregion
	}

	Route::get( 'file/manager', 'Backend\FileManagerController@index')->name( 'backend.file.manager.index');
    Route::get( 'file/info', 'Backend\FileManagerController@getInfo')->name( 'backend.file.info');
	Route::middleware('permission.has:manage_files')->group(function (){
        Route::post( 'file/upload', 'Backend\FileManagerController@upload')->name('backend.file.upload');
        Route::delete('file/upload', 'Backend\FileManagerController@destroy')->name('backend.file.destroy');
        Route::middleware('permission.has:regenerate_thumbnails')->group(function(){
            Route::get('tools/regenerate-thumbnails', 'Backend\FileManagerController@regenerateThumbnailTool')->name('backend.tools.regenerate_thumbnails');
            Route::post('tools/regenerate-thumbnails/{file}', 'Backend\FileManagerController@regenerateThumbnailToolRun')->name('backend.tools.regenerate_thumbnails.run');
        });
	});

	Route::middleware('permission.has:change_db_site_url')->group(function(){
	    Route::get('tools/change-db-site-url', 'Backend\DatabaseToolController@changeSiteUrlIndex')->name('backend.tool.change_db_site_url');
        Route::get('tools/change-db-site-url/analyze', 'Backend\DatabaseToolController@changeSiteUrlAnalyze')->name('backend.tool.change_db_site_url.analyze');
    });
});