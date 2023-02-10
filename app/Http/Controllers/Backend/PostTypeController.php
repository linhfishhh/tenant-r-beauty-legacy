<?php

namespace App\Http\Controllers\Backend;

use App\Classes\PostTaxRel;
use App\Classes\PostType;
use App\Classes\Taxonomy;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostCounts;
use App\Http\Requests\PostCreate;
use App\Http\Requests\PostDestroy;
use App\Http\Requests\PostEdit;
use App\Http\Requests\PostIndex;
use App\Http\Requests\PostPut;
use App\Http\Requests\PostRestore;
use App\Http\Requests\PostSelect;
use App\Http\Requests\PostStore;
use App\Http\Requests\PostTermUpdate;
use App\Http\Requests\PostTrash;
use App\Http\Requests\PostUpdate;
use App\User;
use Carbon\Carbon;
use DataTables;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Response;

class PostTypeController extends Controller
{

    /**
     * @param PostIndex $request
     * @param $post_type
     * @return $this
     */
    function index(
        PostIndex $request,
        $post_type
    ) {
        if ($request->ajax()) {
            /**
             * @var  Taxonomy $taxonomy
             * @var  PostTaxRel $rel
             * @var  Builder|PostType|Model|Collection $posts
             */
            $posts = $post_type::withTrashed()->with(['user', 'user.role']);
            $posts = $post_type::dataTableQuery($posts);
            foreach ($post_type::getTaxonomies() as $taxonomy => $rel) {
                if (!$taxonomy::getShowInAdminTable()) {
                    continue;
                }
                $posts->with(
                    [$taxonomy::getShortClass() => function ($q) {
                        /** @var Builder $q */
                        $q->orderBy(
                            'id',
                            'asc'
                        );
                    }]
                );
            }
            if (!me()->hasPermission($post_type::getManageGlobalPermissionID())) {
                $posts->where(
                    'user_id',
                    '=',
                    me()->id
                );
            }
            $rs = DataTables::eloquent($posts)->addColumn(
                'link',
                function ($post) use
                (
                    $post_type
                ) {
                    return route(
                        'backend.post.edit',
                        ['post_type' => $post_type::getTypeSlug(), 'post' => $post]
                    );
                }
            )->addColumn(
                'language_title',
                function ($post) use
                (
                    $post_type
                ) {
                    return getLanguageTitle($post->language);
                }
            )->addColumn(
                'language_title',
                function ($post) use
                (
                    $post_type
                ) {
                    return getLanguageTitle($post->language);
                }
            )->addColumn(
                'user_email',
                function ($post) use
                (
                    $post_type
                ) {
                    /** @var User $user */
                    $user = $post->user;
                    if($user){
                        return $user->email;
                    }
                    return __('Người dùng bị xóa');
                }
            )->addColumn(
                'user_name',
                function ($post) use
                (
                    $post_type
                ) {
                    /** @var User $user */
                    $user = $post->user;
                    if($user){
                        return $user->name;
                    }
                    return __('Người dùng bị xóa');return '';
                }
            )->addColumn(
                    'public_link',
                    function ($post) use
                    (
                        $post_type
                    ) {
                        /** @var PostType $post */
                        return $post->getUrl();
                    }
                )->addColumn(
                    'user_link',
                    function ($post) use
                    (
                        $post_type
                    ) {
                        /** @var User $user */
                        $user = $post->user;

                        return route(
                            'backend.user.edit',
                            ['user' => $user]
                        );
                    }
                )->addColumn(
                    'user_role_title',
                    function ($post) use
                    (
                        $post_type
                    ) {
                        /** @var User $user */
                        $user = $post->user;
                        if($user){
                            return $user->getRoleTitle();
                        }
                        return __('Người dùng bị xóa');
                    }
                )->addColumn(
                    'trashed',
                    function ($post) use
                    (
                        $post_type
                    ) {
                        /** @var PostType $post */
                        return $post->deleted_at != null;
                    }
                )->addColumn(
                    'taxonomies',
                    function ($post) use
                    (
                        $post_type
                    ) {
                        /** @var PostType $post */
                        $taxs = [];
                        foreach ($post_type::getTaxonomies() as $taxonomy => $rel) {
                            /** @var Taxonomy $taxonomy */
                            if (!$taxonomy::getShowInAdminTable()) {
                                continue;
                            }

                            $taxs[$taxonomy::getTaxSlug()] = [];
                            /** @var Taxonomy[] $ts */
                            $name = $taxonomy::getShortClass();
                            $ts = $post->$name;
                            foreach ($ts as $t) {
                                $add = ['id' => $t->id, 'title' => $t->title, 'link' => false];
                                if (me()->hasPermission($taxonomy::getManagePermissionID())) {
                                    $add['link'] = route(
                                        'backend.taxonomy.edit',
                                        ['post_type' => $post_type::getTypeSlug(), 'taxonomy' => $taxonomy::getTaxSlug(
                                        ), 'post' => $post->id]
                                    );
                                }
                                $taxs[$taxonomy::getTaxSlug()][] = $add;
                            }
                        }

                        return $taxs;
                    }
                )->filter(
                    function ($query) use
                    (
                        $request,
                        $post_type
                    ) {
                        /** @var Builder|Model $query */
                        $query->where(
                            'title',
                            'like',
                            "%{$request->get( 'search')['value']}%"
                        );
                        switch ($request->get(
                            'view_mode',
                            'all'
                        )) {
                            case 'mine':
                                $query->where(
                                    'user_id',
                                    '=',
                                    me()->id
                                )->whereNull('deleted_at');
                                break;
                            case 'trashed':
                                $query->onlyTrashed();
                                break;
                            default:
                                $query->whereNull('deleted_at');
                                break;
                        }
                        if (isMultipleLanguage()) {
                            if ($request->has('language')) {
                                $lang_code = $request->get('language');
                                if ($lang_code != '11' && $lang_code != '00') {
                                    $query->where(
                                        'language',
                                        '=',
                                        $lang_code
                                    );
                                } elseif ($lang_code == '11') {
                                    $lang_codes = getLanguageCodes();
                                    $query->whereNotIn(
                                        'language',
                                        $lang_codes
                                    );
                                }
                            }
                        } else {
                            $query->where(
                                'language',
                                '=',
                                config('app.locale')
                            );
                        }
                        $enabled = $request->get(
                            'published',
                            -1
                        );
                        if ($enabled != -1) {
                            $query->where(
                                'published',
                                '=',
                                $enabled
                            );
                        }
                        foreach ($post_type::getTaxonomies() as $taxonomy => $rel) {
                            /** @var Taxonomy $taxonomy */
                            /** @var PostTaxRel $rel */
                            if (!$taxonomy::getShowInAdminTable()) {
                                continue;
                            }
                            $ids = $request->get(
                                $taxonomy::getTaxSlug(),
                                null
                            );
                            if ($ids) {
                                $query->whereHas(
                                    $rel::getShortClass(),
                                    function ($sub_query) use
                                    (
                                        $ids
                                    ) {
                                        /** @var Builder|Model $sub_query */
                                        $sub_query->whereIn(
                                            'term_id',
                                            $ids
                                        );
                                    }
                                );
                            }
                        }
                        $published_date = $request->get(
                            'published_date',
                            ['', '']
                        );
                        if ($published_date[0] && $published_date[1]) {
                            $query->whereBetween(
                                'published_at',
                                $published_date
                            );
                        }
                        $user_ids = $request->get(
                            'user_ids',
                            []
                        );
                        if ($user_ids) {
                            $query->whereIn(
                                'user_id',
                                $user_ids
                            );
                        }
                        $query = $post_type::dataTableFilter($query);
                    }
                );
            $rs = $post_type::dataTable($rs);
            $rs = $rs->make(true);
            $data = $rs->getData();
            $data->counts = $this->countMode(
                $request,
                $post_type
            );
            $rs->setData($data);
            $rs = $post_type::dataTableViewData($rs);
            return $rs;
        }

        return $post_type::getIndexView(
            ['post_type' => $post_type, 'taxonomies' => $post_type::getTaxonomies()]
        );
    }

    /**
     * @param Request $request
     * @param PostType $post_type
     * @param PostType|Model $post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    function edit(
        PostEdit $request,
        $post_type,
        $post
    ) {
        $post->load('user');
        /**
         * @var Taxonomy $taxonomy
         * @var PostTaxRel $rel
         */
        foreach ($post_type::getTaxonomies() as $taxonomy => $rel) {
            $post->load(
                [$taxonomy::getShortClass() => function ($q) {
                    $q->orderBy(
                        'id',
                        'asc'
                    );
                }]
            );
        }
        $view = $post_type::getEditView(
            ['post_type' => $post_type, 'model' => $post]
        );

        return $view;
    }

    /**
     * @param PostUpdate|PostStore $request
     * @param PostType $post_type
     * @param null|PostType $post
     *
     * @return PostType|null
     */
    private function savePost(
        $request,
        $post_type,
        $post = null
    ) {
        $edit_mode = $post != null;
        if (!$edit_mode) {
            $post = new $post_type();
        }
        $post->title = $request->get('title');

        $post->slug = $this->getSlug(
            $request,
            $post_type,
            $edit_mode ? $post : null
        );
        $post->language = $this->getLanguage(
            $request,
            $post_type,
            $edit_mode ? $post : null
        );
        $post->user_id = $this->getUserID(
            $request,
            $post_type,
            $edit_mode ? $post : null
        );
        $post->published = $this->getPublished(
            $request,
            $post_type,
            $edit_mode ? $post : null
        );
        $post->published_at = $this->getPublishedAt(
            $request,
            $post_type,
            $edit_mode ? $post : null
        );;
        if($edit_mode){
            $post_type::beforeUpdateData(
                $request,
                $post);
        }
        else{
            $post_type::beforeStoreData(
                $request,
                $post);
        }
        $post->save();
        /**
         * @var Taxonomy $taxonomy
         * @var PostTaxRel $rel
         */
        if (me()->hasPermission($post_type::getCatalogizePermissionID())) {
            foreach ($post_type::getTaxonomies() as $taxonomy => $rel) {
                if ($edit_mode) {
                    $post->removeTerms($taxonomy);
                }
                $taxs = $request->get('tax-' . $taxonomy::getTaxSlug());
                if (!$taxs) {
                    continue;
                }
                $this->saveTaxonomies(
                    $taxonomy,
                    $rel,
                    $post,
                    $taxs
                );
            }
        }
        if($edit_mode){
            $post_type::afterUpdateData(
                $request,
                $post);
        }
        else{
            $post_type::afterStoreData(
                $request,
                $post);
        }
        return $post;
    }

    /**
     * @param PostUpdate|PostStore $request
     * @param PostType $post_type
     * @param null|PostType $post
     *
     * @return bool|int|mixed
     */
    private function getPublished(
        $request,
        $post_type,
        $post = null
    ) {
        $edit_mode = $post != null;
        if ($edit_mode) {
            if (me()->hasPermission($post_type::getPublishPermissionID())) {
                $published = $request->get(
                    'published',
                    0
                );
            } else {
                $published = $post->published;
            }
        } else {
            if (me()->hasPermission($post_type::getPublishPermissionID())) {
                $published = $request->get(
                    'published',
                    0
                );
            } else {
                if (me()->hasPermission($post_type::getAutoPublishPermissionID())) {
                    $published = 1;
                } else {
                    $published = 0;
                }
            }
        }
        return $published;
    }

    /**
     * @param PostUpdate|PostStore $request
     * @param PostType $post_type
     * @param null|PostType $post
     *
     * @return int|mixed
     */
    private function getUserID(
        $request,
        $post_type,
        $post = null
    ) {
        $edit_mode = $post != null;
        if ($edit_mode) {
            if (me()->hasPermission($post_type::getChangeAuthorPermissionID())) {
                $user_id = $request->get(
                    'user_id',
                    me()->id
                );
            } else {
                $user_id = $post->user_id;
            }
        } else {
            if (me()->hasPermission($post_type::getChangeAuthorPermissionID())) {
                $user_id = $request->get(
                    'user_id',
                    me()->id
                );
            } else {
                $user_id = me()->id;
            }
        }
        return $user_id;
    }

    /**
     * @param PostUpdate|PostStore $request
     * @param PostType $post_type
     * @param null|PostType $post
     *
     * @return mixed|string
     */
    private function getPublishedAt(
        $request,
        $post_type,
        $post = null
    ) {
        $edit_mode = $post != null;
        if ($edit_mode) {
            if (me()->hasPermission($post_type::getPublishPermissionID())) {
                $published_at = $request->get(
                    'published_at',
                    Carbon::now()->format('Y-m-d H:i:s')
                );
            } else {
                $published_at = $post->published_at;
            }
        } else {
            if (me()->hasPermission($post_type::getPublishPermissionID())) {
                $published_at = $request->get(
                    'published_at',
                    Carbon::now()->format('Y-m-d H:i:s')
                );
            } else {
                $published_at = Carbon::now()->format('Y-m-d H:i:s');
            }
        }
        return $published_at;
    }

    /**
     * @param PostUpdate|PostStore $request
     * @param PostType $post_type
     * @param null|PostType $post
     *
     * @return string
     */
    private function getSlug(
        $request,
        $post_type,
        $post = null
    ) {
        $edit_mode = $post != null;
        $title = $request->get('title');
        $slug_temp = $request->get(
            'slug',
            $title
        );
        if (!$slug_temp) {
            $slug_temp = $title;
        }
        $slug_temp = str_slug($slug_temp);
        $c = 1;
        $slug = $slug_temp;
        if ($edit_mode) {
            while ($post_type::whereSlug($slug)->whereKeyNot($post->id)->count() > 0) {
                $slug = $slug_temp . '-' . $c;
                $c++;
            }
        } else {
            while ($post_type::whereSlug($slug)->count() > 0) {
                $slug = $slug_temp . '-' . $c;
                $c++;
            }
        }
        return $slug;
    }

    /**
     * @param PostUpdate|PostStore $request
     * @param PostType $post_type
     * @param null|PostType $post
     *
     * @return mixed
     */
    private function getLanguage(
        $request,
        $post_type,
        $post = null
    ) {
        $language = $request->get(
            'language',
            config('app.locale')
        );
        if (!isValidLanguage($language)) {
            $language = config('app.locale');
        }
        return $language;
    }

    /**
     * @param PostUpdate $request
     * @param PostType $post_type
     * @param PostType $post
     *
     * @return \Illuminate\Http\JsonResponse
     */
    function update(
        PostUpdate $request,
        $post_type,
        $post
    ) {
        $this->savePost(
            $request,
            $post_type,
            $post
        );
        return Response::json();
    }

    /**
     * @param PostCreate $request
     * @param PostType $post_type
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function create(
        PostCreate $request,
        $post_type
    ) {
        $view = $post_type::getEditView(
            ['post_type' => $post_type, 'model' => null]
        );

        return $view;
    }

    /**
     * @param PostStore $request
     * @param PostType $post_type
     *
     * @return \Illuminate\Http\JsonResponse
     */
    function store(
        PostStore $request,
        $post_type
    ) {
        $post = $this->savePost(
            $request,
            $post_type,
            null
        );
        return Response::json(
            route(
                'backend.post.edit',
                ['post_type' => $post_type::getTypeSlug(), 'post' => $post->id]
            )
        );
    }


    /**
     * @param Taxonomy $taxonomy
     * @param PostTaxRel $rel
     * @param PostType $post
     * @param $tax_ids
     */
    private function saveTaxonomies(
        $taxonomy,
        $rel,
        $post,
        $tax_ids
    ) {
        if (!$tax_ids) {
            return;
        }
        if (!is_array($tax_ids)) {
            $tax_ids = [$tax_ids];
        }
        if (count($tax_ids) == 0) {
            return;
        }
        foreach ($tax_ids as $term_id) {
            /** @var PostTaxRel $tax */
            $tax = new $rel();
            $tax->post_id = $post->id;
            $tax->term_id = $term_id;
            $tax->save();
        }
    }

    /**
     * @param PostDestroy $request
     * @param $post_type
     *
     * @return \Illuminate\Http\JsonResponse
     */
    function destroy(
        PostDestroy $request,
        $post_type
    ) {
        $ids = $request->get(
            'ids',
            []
        );
        if (me()->hasPermission($post_type::getManageGlobalPermissionID())) {
            $posts = $post_type::withTrashed()->whereIn(
                'id',
                $ids
            )->get();
        } else {
            $posts = $post_type::withTrashed()->whereUserId(me()->id)->whereIn(
                'id',
                $ids
            )->get();
        }
        /** @var Collection|Model[] $posts */
        foreach ($posts as $post) {
            $post->forceDelete();
        }

        return Response::json([]);
    }

    /**
     * @param PostTrash $request
     * @param postType $post_type
     *
     * @return \Illuminate\Http\JsonResponse
     */
    function trash(
        PostTrash $request,
        $post_type
    ) {
        $ids = $request->get(
            'ids',
            []
        );
        if (me()->hasPermission($post_type::getManageGlobalPermissionID())) {
            $posts = $post_type::whereIn(
                'id',
                $ids
            )->get();
        } else {
            $posts = $post_type::whereUserId(me()->id)->whereIn(
                'id',
                $ids
            )->get();
        }
        /** @var Collection|Model[] $posts */
        foreach ($posts as $post) {
            try {
                $post->delete();
            } catch (\Exception $e) {
            }
        }

        return Response::json([]);
    }

    /**
     * @param PostRestore $request
     * @param postType $post_type
     *
     * @return \Illuminate\Http\JsonResponse
     */
    function restore(
        PostRestore $request,
        $post_type
    ) {
        $ids = $request->get(
            'ids',
            []
        );
        if (me()->hasPermission($post_type::getManageGlobalPermissionID())) {
            $posts = $post_type::onlyTrashed()->whereIn(
                'id',
                $ids
            )->get();
        } else {
            $posts = $post_type::onlyTrashed()->whereUserId(me()->id)->whereIn(
                'id',
                $ids
            )->get();
        }
        /** @var Collection|Model[] $posts */
        foreach ($posts as $post) {
            $post->restore();
        }

        return Response::json([]);
    }

    /**
     * @param PostTermUpdate $request
     * @param PostType $post_type
     *
     * @return \Illuminate\Http\JsonResponse
     */
    function updateTerm(PostTermUpdate $request, $post_type){
        $post_id = $request->get('pk');
        /** @var PostType $post */
        $post = $post_type::find($post_id);
        $new_term_ids = $request->get('value');
        $taxonomy = $request->get('taxonomy');
        /** @var Taxonomy $taxonomy */
        $taxonomy = getTaxonomy(
            $post_type::getTypeSlug(),
            $taxonomy);
        $post->removeTerms($taxonomy);
        $this->saveTaxonomies(
            $taxonomy,
            $taxonomy::getPostTaxRel(),
            $post,
            $new_term_ids);
        return Response::json([]);
    }

    /**
     * @param PostPut $request
     * @param PostType $post_type
     *
     * @return \Illuminate\Http\JsonResponse
     */
    function put(
        PostPut $request,
        $post_type
    ) {
        if ($request->ajax()) {
            /** @var PostType $post */
            $post = $post_type::find($request->input('pk'));
            if (me()->hasPermission($post_type::getManageGlobalPermissionID()) || ($post->user_id == me()->id)) {
                $post->setAttribute(
                    $request->input('name'),
                    $request->input('value')
                );
                $post->save();
            }

            return Response::json('');
        }
    }

    /**
     * @param Request $request
     * @param PostType $post_type
     *
     * @return array
     */
    private function countMode(
        Request $request,
        $post_type
    ) {
        $rs = ['all' => 0, 'mine' => 0, 'trashed' => 0];
        if (!me()->hasPermission($post_type::getManageGlobalPermissionID())) {
            $query_all = $query_mine = $post_type::where(
                'user_id',
                '=',
                me()->id
            );
            $rs['all'] = $query_all->count();
            $rs['mine'] = $query_mine->count();
            $rs['trashed'] = $post_type::onlyTrashed()->where(
                'user_id',
                '=',
                me()->id
            )->count();
        } else {
            $query_all = $query_mine = $post_type::query();
            $rs['all'] = $query_all->count();
            $rs['mine'] = $query_mine->whereUserId(me()->id)->count();
            $rs['trashed'] = $post_type::onlyTrashed()->count();
        }

        return $rs;
    }

    /**
     * @param PostCounts $request
     * @param PostType $post_type
     *
     * @return \Illuminate\Http\JsonResponse
     */
    function counts(
        PostCounts $request,
        $post_type
    ) {

        return Response::json(
            $this->countMode(
                $request,
                $post_type
            )
        );
    }

    /**
     * @param PostSelect $request
     * @param PostType $post_type
     * @return \Illuminate\Http\JsonResponse
     */
    function select(
        PostSelect $request,
        $post_type
    ) {
        $keyword = $request->get(
            'keyword',
            false
        );
        $language = $request->get(
            'language',
            config('app.locale')
        );
        if ($language != 'all') {
            $query = $post_type::where(
                'language',
                '=',
                $language
            )->orderBy('language')->orderBy(
                    'id',
                    'desc'
                );
        } else {
            $query = $post_type::whereIn(
                'language',
                array_keys(config('app.locales'))
            )->orderBy('language')->orderBy(
                    'id',
                    'desc'
                );
        }
        $query->where(
            'title',
            'like',
            "%{$keyword}%"
        );
        $rs = $query->paginate(
            50,
            ['id', 'title', 'language'],
            'page',
            $request->get(
                'page',
                1
            )
        );
        $items = $rs->items();
        return Response::json($items);
    }

    /**
     * @param Request $request
     * @param PostType $post_type
     * @return \Illuminate\Http\JsonResponse
     */
    function getInfo(
        Request $request,
        $post_type
    ) {
        $ids = $request->get(
            'ids',
            []
        );
        $rs = [];
        if ($ids) {
            $rs = $post_type::whereIn(
                'id',
                $ids
            )->get();
        }
        return Response::json($rs);
    }

    function __construct(Request $request)
    {
        $post_type = $request->route()->parameter('post_type');
        $post_type = getPostType($post_type);

    }
}
