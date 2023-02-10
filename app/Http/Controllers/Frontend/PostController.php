<?php

namespace App\Http\Controllers\Frontend;

use App\Classes\Attachment;
use App\Classes\PostType;
use App\Events\ThemePostTypeIndexViewData;
use App\Events\ThemePostViewData;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PostController extends Controller
{

    /**
     * @param Request $request
     * @param PostType $post_type
     * @param Builder $posts_query
     * @return \Illuminate\Http\RedirectResponse
     */
    function index(
        Request $request,
        $post_type,
        $posts_query
    ) {
        $data = [
            'post_type' => $post_type,
            'posts_query' => $posts_query,
            'request' => $request
        ];
        $event = new ThemePostTypeIndexViewData($request,$data);
        event($event);
        $data = $event->data;
        $view = $post_type::getThemeIndexView($data);
        if(!$view){
            return \Redirect::route('frontend.index');
        }
        return $view;
    }

    /**
     * @param Request $request
     * @param $post_slug
     * @param PostType $post_type
     * @param PostType $post
     * @return bool|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    function detail(
        Request $request,
        $post_slug,
        $post_type,
        $post
    ) {
        $data = [
            'post' => $post,
            'request' => $request,
            'post_type' => $post_type
        ];
        $event = new ThemePostViewData($request,$data);
        event($event);
        $data = $event->data;
        $view = $post_type::getThemePostView($post,$data);
        if(!$view){
            return \Redirect::route('frontend.index');
        }
        return $view;
    }


    /**
     * @param Request $request
     * @param Attachment $attachment
     * @param PostType $post_type
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    function attachment(
        Request $request,
        $attachment,
        $post_type
    ) {
        $ext = strtolower($attachment->file->extension);
        $mime = getMime($ext);
        $file_name = $attachment->file->name.'.'.$attachment->file->extension;
        $path = public_path($attachment->file->getUploadFilePath());
        return \Response::download($path, $file_name, [
            'Content-Type' => $mime
        ]);
    }
}
