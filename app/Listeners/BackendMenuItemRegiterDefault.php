<?php

namespace App\Listeners;

use App\Classes\BackendMenuItem;
use App\Classes\Comment;
use App\Classes\PostTaxRel;
use App\Classes\PostType;
use App\Classes\Taxonomy;
use App\Events\BackendMenuItemRegister;
use App\Events\BackendSettingPageRegister;
use Illuminate\Support\Collection;

class BackendMenuItemRegiterDefault
{
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  BackendMenuItemRegister $register
     * @return void
     */
    public function handle($register)
    {
        //region Post Type
        $content_event = app('post_types');
        $post_types = $content_event->getPostTypes();
        if (count($post_types)) {
            $register->register(
                [
                    new BackendMenuItem(
                        'content',
                        __('Nội dung'),
                        false,
                        false,
                        'icon-book',
                        false,
                        false,
                        1
                    ),
                ]
            );
            /** @var Collection|Comment[] $comment_types */
            $comment_types = collect();
            foreach ($post_types as $Class) {
                /** @var PostType $Class */
                $register->register(
                    [
                        new BackendMenuItem(
                            $Class::getTypeSlug(),
                            $Class::getMenuTitle(),
                            'content',
                            false,
                            $Class::getMenuIcon(),
                            false,
                            false,
                            $Class::getMenuOrder()
                        ),
                    ]
                );
                if ($Class::getCommentType()) {
                    /** @var Comment $comment_type */
                    $comment_type = $Class::getCommentType();
                    if (!$comment_types->has($comment_type)) {
                        $comment_types->put(
                            $comment_type,
                            $comment_type
                        );
                    }
                }
                $register->register(
                    [
                        new BackendMenuItem(
                            $Class::getMenuIndexSlug(),
                            $Class::getMenuIndexTitle(),
                            $Class::getTypeSlug(),
                            [
                                'backend.post.index',
                                [
                                    'post_type' => $Class::getTypeSlug()
                                ],
                                [
                                    'post_type' => $Class
                                ]
                            ],
                            $Class::getMenuIndexIcon(),
                            [
                                $Class::getCreatePermissionID(),
                                $Class::getEditPermissionID(),
                                $Class::getDeletePermissionID(),
                                $Class::getCatalogizePermissionID(),
                                $Class::getPublishPermissionID(),
                                $Class::getTrashPermissionID(),
                            ],
                            true,
                            -999
                        ),
                    ]
                );

                $taxes = $Class::getTaxonomies();
                foreach ($taxes as $tax => $rel) {
                    /** @var Taxonomy $tax */
                    /** @var PostTaxRel $rel */
                    $register->register(
                        [
                            new BackendMenuItem(
                                $tax::getMenuSlug(),
                                $tax::getMenuTitle(),
                                $Class::getTypeSlug(),
                                [
                                    'backend.taxonomy.index',
                                    [
                                        'post_type' => $Class::getTypeSlug(),
                                        'taxonomy'  => $tax::getTaxSlug()
                                    ],
                                    [
                                        'post_type' => $Class,
                                        'taxonomy'  => $tax
                                    ],
                                ],
                                $tax::getMenuIcon(),
                                [
                                    $tax::getManagePermissionID()
                                ],
                                true,
                                1 + $tax::getMenuOrder()
                            ),
                        ]
                    );
                }
            }

            if ($comment_types->count() > 0) {
                $register->register(
                    [
                        new BackendMenuItem(
                            'comments',
                            __('Bình luận'),
                            'interaction',
                            false,
                            'icon-comment-discussion',
                            false,
                            false,
                            1
                        ),
                    ]
                );
                foreach ($comment_types as $comment_type) {
                    $register->register(
                        [
                            new BackendMenuItem(
                                $comment_type::getMenuSlug(),
                                $comment_type::getMenuTitle(),
                                'comments',
                                [
                                    'backend.comment.index',
                                    [
                                        'post_type' => $comment_type::getPostType()::getTypeSlug(),
                                    ],
                                    [
                                        'post_type' => $comment_type::getPostType(),
                                    ],
                                ],
                                $comment_type::getMenuIcon(),
                                [$comment_type::getManagePermissionID()],
                                false,
                                $comment_type::getMenuOrder()
                            ),
                        ]
                    );
                }
            }
        }
        //endregion

        $register->register(
            [
                new BackendMenuItem(
                    'interaction',
                    __('Tương tác'),
                    false,
                    false,
                    'icon-theater',
                    false,
                    false,
                    2
                ),
                new BackendMenuItem(
                    'frontend',
                    __('Giao diện'),
                    false,
                    false,
                    'icon-shutter',
                    false,
                    false,
                    98
                ),

                new BackendMenuItem(
                    'frontend.nav',
                    __('Hệ thống menu'),
                    'frontend',
                    false,
                    'icon-menu2',
                    ['manage_menu'],
                    false,
                    1
                ),

                new BackendMenuItem(
                    'frontend.nav.library',
                    __('Thư viện menu'),
                    'frontend.nav',
                    'backend.menu.library.index',
                    'icon-tree6',
                    ['manage_menu'],
                    false,
                    1
                ),
                new BackendMenuItem(
                    'frontend.nav.locations',
                    __('Thiết lập menu'),
                    'frontend.nav',
                    'backend.menu.location.index',
                    'icon-pencil5',
                    ['manage_menu'],
                    false,
                    2
                ),

//                new BackendMenuItem(
//                    'frontend.sidebar',
//                    __('Hệ thống sidebar'),
//                    'frontend',
//                    '#',
//                    'icon-grid3',
//                    ['manage_sidebar'],
//                    false,
//                    2
//                ),
//
//                new BackendMenuItem(
//                    'frontend.sidebar.library',
//                    __('Thư viện sidebar'),
//                    'frontend.sidebar',
//                    'backend.sidebar.library.index',
//                    'icon-tree6',
//                    ['manage_sidebar'],
//                    false,
//                    1
//                ),
//
//                new BackendMenuItem(
//                    'frontend.sidebar.locations',
//                    __('Thiết lập sidebar'),
//                    'frontend.sidebar',
//                    'backend.sidebar.location.index',
//                    'icon-pencil5',
//                    ['manage_sidebar'],
//                    false,
//                    2
//                ),
//
//                new BackendMenuItem(
//                    'frontend.slider',
//                    __('Hệ thống slider'),
//                    'frontend',
//                    'backend.slider.index',
//                    'icon-menu2',
//                    ['manage_sliders'],
//                    false,
//                    3
//                ),

                new BackendMenuItem(
                    'frontend.theme',
                    __('Các mẫu giao diện'),
                    'frontend',
                    'backend.theme.index',
                    'icon-image3',
                    ['manage_theme'],
                    false,
                    4
                ),

                new BackendMenuItem(
                    'frontend.theme.settings',
                    __('Cấu hình giao diện'),
                    'frontend',
                    false,
                    'icon-cog',
                    false,
                    false,
                    5
                ),

                new BackendMenuItem(
                    'system',
                    __('Hệ thống'),
                    false,
                    false,
                    'icon-gear',
                    false,
                    false,
                    99
                ),

                new BackendMenuItem(
                    'user',
                    __('Quản lý người dùng'),
                    'system',
                    false,
                    'icon-user',
                    [],
                    false,
                    99
                ),

                new BackendMenuItem(
                    'user.users',
                    __('Tài khoản'),
                    'user',
                    'backend.user.index',
                    'icon-user',
                    ['manage_users'],
                    false
                ),


                new BackendMenuItem(
                    'user.roles',
                    __('Vai trò'),
                    'user',
                    'backend.role.index',
                    'icon-users4',
                    ['manage_roles'],
                    false
                ),

            ]
        );

        $register->register(
            [
                new BackendMenuItem(
                    'setting_pages',
                    __('Quản lý cấu hình'),
                    'system',
                    false,
                    'icon-cogs',
                    false,
                    false,
                    99
                ),
            ]
        );

        $register->register(
            [
                new BackendMenuItem(
                    'system_tools',
                    __('Công cụ hệ thống'),
                    'system',
                    false,
                    'icon-hammer-wrench',
                    false,
                    false,
                    100
                ),
            ]
        );

        $register->register(
            [
                new BackendMenuItem(
                    'tool_thumbnail',
                    __('Tạo lại thumbnail'),
                    'system_tools',
                    'backend.tools.regenerate_thumbnails',
                    'icon-wrench',
                    ['regenerate_thumbnails'],
                    false,
                    0
                ),
            ]
        );

//        $register->register(
//            [
//                new BackendMenuItem(
//                    'tool_db_site_url_changer',
//                    __('Đổi site url'),
//                    'system_tools',
//                    'backend.tool.change_db_site_url',
//                    'icon-wrench',
//                    ['change_db_site_url'],
//                    false,
//                    0
//                ),
//            ]
//        );

        /** @var BackendSettingPageRegister $setting_page_events */
        $setting_page_events = app('backend_setting_pages');
        $setting_pages = $setting_page_events->getPages();
        foreach ($setting_pages as $page) {
            $register->register(
                [
                    new BackendMenuItem(
                        $page->getMenuSlug(),
                        $page->getMenuTitle(),
                        $page->getParentMenuSlug(),
                        [
                            'backend.setting.page.edit',
                            [
                                'page' => $page->getSlug()
                            ],
                            [
                                'page' => $page
                            ]
                        ],
                        $page->getMenuIcon(),
                        [$page->getPermissionID()],
                        false,
                        $page->getMenuOrder()
                    ),
                ]
            );
        }
    }
}
