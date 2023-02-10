<?php
Route::get(
    'assets/modules/{module}/{file}',
    'Frontend\ModuleController@assetUrl'
)->where(
    ['file' => '.*']
)->name('frontend.module.asset.url');

Route::middleware('auth.basic')->group(
    function () {
        Route::get(
            'logout',
            'Backend\LoginController@logout'
        )->name('frontend.logout');
    }
);

Route::middleware('frontend.theme')->group(
    function () {
        Route::get('/demo',function (){
            dd(Session::getId());
        });
        Route::get(
            '/',
            'Frontend\ThemeController@index'
        )->name('frontend.index');

        //region Post Type Routes
        $post_types = getPostTypes();
        /** @var \App\Classes\PostType $post_type */
        foreach ($post_types as $post_type) {
            if (!$post_type::isPublic()) {
                continue;
            }
            Route::prefix($post_type::getPublicSlug())->group(
                function () use
                (
                    $post_type
                ) {
                    Route::middleware('frontend.post_type:' . $post_type)->group(
                        function () use
                        (
                            $post_type
                        ) {
                            if ($post_type::hasIndex()) {
                                Route::middleware('frontend.post_type.index')->group(
                                    function () use
                                    (
                                        $post_type
                                    ) {
                                        Route::get(
                                            '',
                                            'Frontend\PostController@index'
                                        )->name('frontend.post.index.' . $post_type::getTypeSlug());
                                    }
                                );
                            }

                            if($post_type::getAttachmentType()){
                                /** @var \App\Classes\Attachment $attachment_type */
                                $attachment_type = $post_type::getAttachmentType();
                                Route::middleware('frontend.post.attachment')->group(function () use ($attachment_type, $post_type){
                                    Route::get($attachment_type::getPublicSlug().'/{attachment}', 'Frontend\PostController@attachment')->name('frontend.post.attachment.' . $post_type::getTypeSlug());
                                });
                            }

                            /**
                             * @var \App\Classes\Taxonomy $taxonomy
                             * @var  $rel
                             */
                            foreach ($post_type::getTaxonomies() as $taxonomy => $rel) {
                                if (!$taxonomy::isPublic()) {
                                    continue;
                                }
                                Route::middleware('frontend.taxonomy:' . $taxonomy)->group(
                                    function () use
                                    (
                                        $post_type,
                                        $taxonomy
                                    ) {
                                        Route::prefix($taxonomy::getPublicSlug())->group(
                                            function () use
                                            (
                                                $taxonomy,
                                                $post_type
                                            ) {
                                                if ($taxonomy::hasIndex()) {
                                                    Route::middleware('frontend.term.index')->group(
                                                        function () use
                                                        (
                                                            $taxonomy,
                                                            $post_type
                                                        ) {
                                                            Route::get(
                                                                '',
                                                                'Frontend\TaxonomyController@index'
                                                            )->name(
                                                                'frontend.term.index.' . $post_type::getTypeSlug(
                                                                ) . '.' . $taxonomy::getTaxSlug()
                                                            );
                                                        }
                                                    );
                                                }
                                                Route::middleware('frontend.term')->group(
                                                    function () use
                                                    (
                                                        $taxonomy,
                                                        $post_type
                                                    ) {
                                                        Route::get(
                                                            '{term_slug}',
                                                            'Frontend\TaxonomyController@term'
                                                        )->name(
                                                            'frontend.term.detail.' . $post_type::getTypeSlug(
                                                            ) . '.' . $taxonomy::getTaxSlug()
                                                        );
                                                    }
                                                );
                                            }
                                        );
                                    }
                                );
                            }

                            Route::middleware('frontend.post')->group(
                                function () use
                                (
                                    $post_type
                                ) {
                                    Route::get(
                                        '{post_slug}',
                                        'Frontend\PostController@detail'
                                    )->name('frontend.post.detail.' . $post_type::getTypeSlug());
                                }
                            );
                        }
                    );
                }
            );
        }
        //endregion
    }
);