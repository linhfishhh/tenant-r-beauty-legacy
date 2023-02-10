<?php

namespace App\Http\Controllers\Backend;

use App\Classes\Comment;
use App\Classes\PostType;
use App\Http\Requests\CommentContent;
use App\Http\Requests\CommentDestroy;
use App\Http\Requests\CommentPublished;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;

class CommentController extends Controller
{
    /**
     * @param Request $request
     * @param PostType $post_type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View|\Yajra\DataTables\DataTableAbstract|\Yajra\DataTables\EloquentDataTable
     */
    function index(Request $request, $post_type){
        /** @var Comment $comment_type */
        $comment_type = $post_type::getCommentType();
        if($request->ajax()){
            $items = $comment_type::with(['post'=>function($query){
                /** @var Builder $query */
                $query->withCount('comments');
            }, 'user', 'parent.user', 'children'])->whereHas('post');
            $rs =  DataTables::eloquent($items);
            $rs->filter(function ($query) use ($request){
                /** @var Builder $query */
                $keyword = "%{$request->get( 'search')['value']}%";
                if($keyword){
                    $query->where(function ($query) use ($keyword){
                        /** @var Builder $query */
                        $query->whereHas('post', function ($query) use ($keyword){
                            /** @var Builder $query */
                            $query->where('title', 'like', $keyword);
                        });
                        $query->orWhere('ip', 'like', $keyword);
                    });
                }
                $user_id = $request->get('user_id', '');
                if($user_id){
                    $query->whereHas('user', function ($query) use ($user_id) {
                        /** @var Builder $query */
                        $query->where('id', '=', $user_id);
                    });
                }
                $published = $request->get('published', "-1");
                if($published != "-1"){
                    $query->where('published', '=', $published);
                }
                $created_at = $request->get( 'created_at', [ '', '' ] );
                if ( $created_at[0] && $created_at[1] ) {
                    $query->whereBetween( 'created_at', $created_at );
                }
            });
            $rs = $rs->make(true);
            $counts = [
                '-1' => 0,
                '1' => 0,
                '0' => 0
            ];
            $counts['-1'] = $comment_type::count();
            $counts['1'] = $comment_type::where('published', '=', 1)->count();
            $counts['0'] = $comment_type::where('published', '=', 0)->count();
            $data         = $rs->getData();
            $data->counts = $counts;
            $rs->setData( $data );
            return $rs;
        }
        return view('backend.pages.comment.index', ['post_type'=>$post_type, 'comment_type'=>$comment_type]);
    }

    function postIndex(Request $request, $post_type, $post){

    }

    /**
     * @param CommentPublished $request
     * @param PostType $post_type
     * @return \Illuminate\Http\JsonResponse
     */
    function published(CommentPublished $request, $post_type){
        /** @var Comment $comment_type */
        $comment_type = $post_type::getCommentType();
        if($request->has('pk')){
            $ids = [$request->get('pk')];
        }
        else{
            $ids = $request->get('ids', []);
        }
        $value = $request->get('value', 0);
        /** @var Comment[]|Collection $comments */
        $comments = $comment_type::whereIn('id', $ids)->get();
        foreach ($comments as $comment){
            $comment->published = $value;
            $comment->save();
        }
        return \Response::json('');
    }

    /**
     * @param CommentDestroy $request
     * @param PostType $post_type
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(CommentDestroy $request, $post_type){
        /** @var Comment $comment_type */
        $comment_type = $post_type::getCommentType();
        $ids = $request->get('ids', []);
        /** @var Comment[]|Collection $comments */
        $comments = $comment_type::whereIn('id', $ids)->get();
        foreach ($comments as $comment){
            $comment->delete();
        }
        return \Response::json('');
    }

    /**
     * @param CommentContent $request
     * @param PostType $post_type
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function content(CommentContent $request, $post_type){
        /** @var Comment $comment_type */
        $comment_type = $post_type::getCommentType();
        $id = $request->get('id');
        /** @var Comment[]|Collection $comments */
        $comment = $comment_type::find($id);
        if($comment){
            $comment->content = $request->get('content', '');
            $comment->save();
        }
        return \Response::json('');
    }
}
