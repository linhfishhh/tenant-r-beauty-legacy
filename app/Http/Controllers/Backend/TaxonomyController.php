<?php

namespace App\Http\Controllers\Backend;

use App\Classes\PostType;
use App\Classes\Taxonomy;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaxonomySearch;
use App\Http\Requests\TaxonomyStoreUpdate;
use DataTables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Redirect;
use Response;

class TaxonomyController extends Controller
{

    private function getDatatableChildren(
        $post_type,
        $taxonomy,
        $data,
        $id = null,
        $old = [],
        $lv = 0
    ) {
        $output = $old;
        foreach ($data->data as $item) {
            if ($item->parent_id == $id) {
                $output[] = $item;
                $lvz = $lv + 1;
                $adds = $this->getDataTable(
                    $item->id,
                    $post_type,
                    $taxonomy,
                    $lvz
                );
                $add_datas = $adds->getData();
                $output = $this->getDatatableChildren(
                    $post_type,
                    $taxonomy,
                    $add_datas,
                    $item->id,
                    $output,
                    $lvz
                );
            }
        }
        return $output;
    }

    /**
     * @param PostType $post_type
     * @param Taxonomy $taxonomy
     */
    private function getDataTable(
        $parent_id,
        $post_type,
        $taxonomy,
        $lv = 0
    ) {
        $terms = $taxonomy::withCount('posts');
        $terms = $taxonomy::dataTableQuery($terms);
        if ($taxonomy::isHierarchy()) {
            $terms->where(
                'parent_id',
                '=',
                $parent_id
            );
            $terms->with('children');
        }
        $rs = DataTables::eloquent($terms)->addColumn(
            'posts_count',
            function (Taxonomy $term) {
                return $term->posts_count;
            }
        )->addColumn(
                'link',
                function (Taxonomy $term) use
                (
                    $post_type,
                    $taxonomy
                ) {
                    return route(
                        'backend.taxonomy.edit',
                        ['post_type' => $post_type::getTypeSlug(), 'taxonomy' => $taxonomy::getTaxSlug(
                        ), 'term' => $term]
                    );
                }
            )->addColumn(
                'level',
                function (Taxonomy $term) use
                (
                    $post_type,
                    $taxonomy,
                    $lv
                ) {
                    return $lv;
                }
            )->addColumn(
                'language_title',
                function (Taxonomy $term) use
                (
                    $post_type,
                    $taxonomy,
                    $lv
                ) {
                    return getLanguageTitle($term->language);
                }
            )
            ->addColumn(
                'public_link',
                function (Taxonomy $term) use
                (
                    $post_type,
                    $taxonomy,
                    $lv
                ) {
                    return $term->getPublicDetailUrl($term->slug);
                }
            );
        if (isMultipleLanguage()) {
            if ($rs->request->has('language')) {
                $lang_code = $rs->request->get('language');
                if ($lang_code != '11' && $lang_code != '00') {
                    $rs->getQuery()->where(
                        'language',
                        '=',
                        $lang_code
                    );
                } elseif ($lang_code == '11') {
                    $lang_codes = getLanguageCodes();
                    $rs->getQuery()->whereNotIn(
                        'language',
                        $lang_codes
                    );
                }
            }
        } else {
            $rs->getQuery()->where(
                'language',
                '=',
                config('app.locale')
            );
        }
        $rs->filter(function($query) use ($taxonomy, $rs){
            $keyword = "%{$rs->request->get( 'search')['value']}%";
            /** @var Builder $query */
            $query->where('title', 'like', $keyword);
            $query = $taxonomy::dataTableFilter($query);
        });
        $rs = $taxonomy::dataTable($rs);
        $rs =  $rs->make(true);
        $rs = $taxonomy::dataTableViewData($rs);
        return $rs;
    }

    /**
     * @param Request $request
     * @param PostType $post_type
     * @param Taxonomy $taxonomy
     */
    function index(
        Request $request,
        $post_type,
        $taxonomy
    ) {
        if ($request->ajax()) {
            $rs = $this->getDataTable(
                null,
                $post_type,
                $taxonomy,
                0
            );
            /** @var JsonResponse $rs */
            if ($taxonomy::isHierarchy()) {
                $data = $rs->getData();
                $new_data = $data;
                $list = $this->getDatatableChildren(
                    $post_type,
                    $taxonomy,
                    $data,
                    null,
                    [],
                    0
                );
                $new_data->data = $list;
                $new_data->recordsTotal = count($list);
                $new_data->recordsFiltered = count($list);
                $rs->setData($new_data);
            }
            return $rs;
        }
        //$post_type = getPostType( $post_type::getTypeSlug());
        //$taxonomy = getTaxonomy( $post_type::getTypeSlug(), $taxonomy::getTaxSlug());
        return $taxonomy::getIndexView(
            ['post_type' => $post_type, 'taxonomy' => $taxonomy]
        );
    }

    /**
     * @param Request $request
     * @param PostType $post_type
     * @param Taxonomy $taxonomy
     * @param Taxonomy $term
     */
    function edit(
        Request $request,
        $post_type,
        $taxonomy,
        $term
    ) {
        return $taxonomy::getEditView(
            ['post_type' => $post_type, 'taxonomy' => $taxonomy, 'model' => $term]
        );
    }

    /**
     * @param Request $request
     * @param PostType $post_type
     * @param Taxonomy $taxonomy
     * @param Taxonomy $term
     */
    function update(
        TaxonomyStoreUpdate $request,
        $post_type,
        $taxonomy,
        $term
    ) {
        if ($request->ajax()) {
            $term->title = $request->get('title');
            $slug_temp = Input::get(
                'slug',
                $term->title
            );
            if (!$slug_temp) {
                $slug_temp = $term->title;
            }
            $slug_temp = str_slug($slug_temp);
            $c = 1;
            $slug = $slug_temp;
            while ($taxonomy::whereSlug($slug)->whereKeyNot($term->id)->count() > 0) {
                $slug = $slug_temp . '-' . $c;
                $c++;
            }
            $term->slug = $slug;
            $term->language = $request->get(
                'language',
                config('app.locale')
            );
            if ($taxonomy::isHierarchy()) {
                $term->parent_id = $request->get(
                    'parent_id',
                    null
                );
            } else {
                $term->parent_id = null;
            }
            $taxonomy::beforeUpdateData(
                $request,
                $term);
            $term->save();
            $taxonomy::afterUpdateData(
                $request,
                $term);
            return Response::json();
        }

        return Redirect::route(
            'backend.taxonomy.index',
            ['post_type' => $post_type::getTypeSlug(), 'taxonomy' => $taxonomy::getTaxSlug()]
        );
    }

    /**
     * @param Request $request
     * @param PostType $post_type
     * @param Taxonomy $taxonomy
     */
    function create(
        Request $request,
        $post_type,
        $taxonomy
    ) {

        return $taxonomy::getEditView(
            ['post_type' => $post_type, 'taxonomy' => $taxonomy, 'model' => null]
        );
    }

    /**
     * @param Request $request
     * @param PostType $post_type
     * @param Taxonomy $taxonomy
     */
    function store(
        TaxonomyStoreUpdate $request,
        $post_type,
        $taxonomy
    ) {
        if ($request->ajax()) {
            /** @var Taxonomy $term */
            $term = new $taxonomy();
            $term->title = $request->get('title');
            $slug_temp = Input::get(
                'slug',
                $term->title
            );
            if (!$slug_temp) {
                $slug_temp = $term->title;
            }
            $slug_temp = str_slug($slug_temp);
            $c = 1;
            $slug = $slug_temp;
            while ($taxonomy::whereSlug($slug)->count() > 0) {
                $slug = $slug_temp . '-' . $c;
                $c++;
            }
            $term->slug = $slug;
            $term->language = $request->get(
                'language',
                config('app.locale')
            );
            if ($taxonomy::isHierarchy()) {
                $term->parent_id = $request->get(
                    'parent_id',
                    null
                );
            } else {
                $term->parent_id = null;
            }
            $taxonomy::beforeStoreData(
                $request,
                $term);
            $term->save();
            $taxonomy::afterStoreData(
                $request,
                $term);
            return Response::json(
                route(
                    'backend.taxonomy.edit',
                    ['post_type' => $post_type::getTypeSlug(), 'taxonomy' => $taxonomy::getTaxSlug(), 'term' => $term]
                )
            );
        }
        return Redirect::route(
            'backend.taxonomy.index',
            ['post_type' => $post_type::getTypeSlug(), 'taxonomy' => $taxonomy::getTaxSlug()]
        );
    }

    /**
     * @param Request $request
     * @param PostType $post_type
     * @param Taxonomy $taxonomy
     */
    function destroy(
        Request $request,
        $post_type,
        $taxonomy
    ) {
        $ids = Input::get(
            'ids',
            []
        );
        /** @var Taxonomy[]|Collection $terms */
        $terms = $taxonomy::whereIn(
            'id',
            $ids
        )->get();
        foreach ($terms as $term) {
            $term->delete();
        }
        if ($request->ajax()) {
            return Response::json('');
        }

        return Redirect::route('backend.taxonomy.index');
    }

    /**
     * @param TaxonomySearch $request
     * @param PostType $post_type
     * @param Taxonomy|Builder $taxonomy
     *
     * @return JsonResponse
     */
    function search(
        TaxonomySearch $request,
        $post_type,
        $taxonomy
    ) {
        $keyword = $request->get(
            'keyword',
            false
        );
        $query = $taxonomy::where(
            'title',
            'like',
            "%{$keyword}%"
        )->orderBy('language');
        $rs = $query->paginate(
            50,
            ['id', 'title', 'language'],
            'page',
            $request->get(
                'page',
                1
            )
        );
        return Response::json($rs->items());
    }


    /**
     * @param Taxonomy[] $items
     * @param null|int $parent_id
     * @param int $level
     */
    private function buildSelectItems(
        $items,
        $olds,
        $parent_id,
        $level
    ) {
        foreach ($items as $item) {
            if ($item->parent_id == $parent_id) {
                $item->level = $level;
                $olds[] = $item;
                $olds = $this->buildSelectItems(
                    $items,
                    $olds,
                    $item->id,
                    $level + 1
                );
            }
        }
        return $olds;
    }

    /**
     * @param TaxonomySearch $request
     * @param PostType $post_type
     * @param Taxonomy|Builder $taxonomy
     *
     * @return JsonResponse
     */
    function select(
        TaxonomySearch $request,
        $post_type,
        $taxonomy
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
            $query = $taxonomy::where(
                'language',
                '=',
                $language
            )->orderBy('language');
        } else {
            $query = $taxonomy::whereIn(
                'language',
                array_keys(config('app.locales'))
            )->orderBy('language');
        }
        if ($taxonomy::isHierarchy()) {
            $items = $query->get(['id', 'title', 'language', 'parent_id']);
            $new = [];
            $items = $this->buildSelectItems(
                $items,
                $new,
                null,
                0
            );
        } else {
            $query->where(
                'title',
                'like',
                "%{$keyword}%"
            );
            $rs = $query->paginate(
                50,
                ['id', 'title', 'language', 'parent_id'],
                'page',
                $request->get(
                    'page',
                    1
                )
            );
            $items = $rs->items();
        }
        return Response::json($items);
    }

    /**
     * @param Request $request
     * @param PostType $post_type
     * @param Taxonomy $taxonomy
     * @return \Illuminate\Http\JsonResponse
     */
    function getInfo(
        Request $request,
        $post_type,
        $taxonomy
    ) {
        $ids = $request->get(
            'ids',
            []
        );
        $rs = [];
        if ($ids) {
            $rs = $taxonomy::whereIn(
                'id',
                $ids
            )->get();
        }
        return Response::json($rs);
    }
}
