<?php

namespace App\Listeners;

use App\Classes\Comment;
use App\Classes\Permission;
use App\Classes\PermissionGroup;
use App\Classes\Taxonomy;
use App\Events\PermissionRegister;

class PermissionRegisterDefault {
    public function __construct() {
    }
    
    
    /**
     * @param PermissionRegister $register
     */
    public function handle( $register ) {
    	$groups = [
		    new PermissionGroup(
			    'backend',
			    __( 'Hệ thống quản trị' ),
			    'icon-medal-star',
			    - 99
		    ),

            new PermissionGroup(
                'other',
                __( 'Các quyền khác' ),
                'icon-medal-star',
                999999
            ),
		    new PermissionGroup(
			    'users',
			    __( 'Người dùng' ),
			    'icon-users',
			    - 98
		    ),
		    new PermissionGroup(
			    'frontend',
			    __( 'Giao diện' ),
			    'icon-shutter',
			    - 97
		    ),
	    ];

	    $permissions = [
		    new Permission(
			    'access_backend',
			    __( 'Truy xuất quản trị' ),
			    'backend'
		    ),
		    new Permission(
			    'manage_files',
			    __( 'Quản lý file upload' ),
			    'backend'
		    ),
		    new Permission(
			    'manage_theme',
			    __( 'Quản lý mẫu giao diện' ),
			    'frontend'
		    ),
		    new Permission(
			    'manage_menu',
			    __( 'Quản lý menu' ),
			    'frontend'
		    ),
		    new Permission(
			    'manage_sidebar',
			    __( 'Quản lý Sidebar' ),
			    'frontend'
		    ),
            new Permission(
                'manage_sliders',
                __( 'Quản lý các slider' ),
                'frontend'
            ),
		    new Permission(
			    'manage_users',
			    __( 'Quản lý người dùng' ),
			    'users'
		    ),
		    new Permission(
			    'manage_roles',
			    __( 'Quản lý vai trò người dùng' ),
			    'users'
		    ),
	    ];

        $post_types = getPostTypes();

	    foreach ( $post_types as $k=>$post_type ) {
		    $groups[] = $post_type::getPermissionGroup();
		    $permissions[] = $post_type::getCreatePermission();
		    $permissions[] = $post_type::getEditPermission();
		    $permissions[] = $post_type::getTrashPermission();
		    $permissions[] = $post_type::getDeletePermission();

		    $permissions[] = $post_type::getChangeAuthorPermission();
		    $permissions[] = $post_type::getPublishPermission();
		    $permissions[] = $post_type::getAutoPublishPermission();
		    $permissions[] = $post_type::getCatalogizePermission();
		    $permissions[] = $post_type::getManageGlobalPermission();
            $permissions[] = $post_type::getPreviewPermission();

		    if($post_type::getCommentType()){
		        /** @var Comment $comment_type */
                $comment_type = $post_type::getCommentType();
                $permissions[] = $comment_type::getManagePermission();
            }

		    $taxonomies = $post_type::getTaxonomies();
		    foreach ($taxonomies as $taxonomy=>$relationship){
		    	/** @var Taxonomy $taxonomy */
			    $groups[] = $taxonomy::getPermissionGroup();
			    $permissions[] = $taxonomy::getManagePermission();
		    }
        }

        $groups[] = new PermissionGroup(
            'settings',
            __( 'Cấu hình hệ thống' ),
            'icon-cogs',
            -99
        );



        $setting_pages = app('backend_setting_pages')->getPages();

        foreach ($setting_pages as $page){
            $permissions[] = new Permission(
                $page->getPermissionID(),
                $page->getPermissionTitle(),
                $page->getPermissionGroupID(),
                $page->getPermissionOrder()

            );
        }

        $groups[] = new PermissionGroup(
            'tools',
            __( 'Các công cụ hệ thống' ),
            'icon-hammer-wrench',
            -99
        );

        $permissions[] = new Permission(
            'regenerate_thumbnails',
            __('Sử dụng công cụ tạo lại thumbnail cho ảnh đã upload'),
            'tools');
        $permissions[] = new Permission(
            'change_db_site_url',
            __('Sử dụng công cụ đổi site url cho các bảng csdl'),
            'tools');

	    $register->registerGroups(
		    $groups
	    );
        $register->registerPermissions(
            $permissions
        );
    }


}
