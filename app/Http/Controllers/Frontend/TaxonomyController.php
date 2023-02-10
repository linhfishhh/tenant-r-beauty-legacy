<?php

namespace App\Http\Controllers\Frontend;

use App\Classes\PostType;
use App\Classes\Taxonomy;
use App\Events\ThemeTaxonomyIndexViewData;
use App\Events\ThemeTermViewData;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class TaxonomyController extends Controller
{

    /**
     * @param Request $request
     * @param PostType $post_type
     * @param Taxonomy $taxonomy
     * @param Builder $terms_query
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    function index(Request $request, $post_type, $taxonomy, $terms_query){
        $data = [
            'terms_query' => $terms_query,
            'request' => $request,
            'post_type' => $post_type,
            'taxonomy' => $taxonomy
        ];
        $event = new ThemeTaxonomyIndexViewData($request,$data);
        event($event);
        $data = $event->data;
        $view = $taxonomy::getThemeIndexView($data);
        if(!$view){
            return \Redirect::route('frontend.index');
        }
        return $view;
    }

    /**
     * @param Request $request
     * @param string $term_slug
     * @param PostType $post_type
     * @param Taxonomy $taxonomy
     * @param Taxonomy $term
     * @param Builder|HasMany $posts_query
     * @return bool|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    function term(Request $request, $term_slug, $post_type, $taxonomy, $term, $posts_query){
        $data = [
            'term' => $term,
            'posts_query' => $posts_query,
            'post_type' => $post_type,
            'taxonomy' => $taxonomy,
            'request' => $request
        ];
        $event = new ThemeTermViewData($request,$data);
        event($event);
        $data = $event->data;
        $view = $taxonomy::getThemeTermView($term,$data);
        if(!$view){
            return \Redirect::route('frontend.index');
        }
        return $view;
    }
}
