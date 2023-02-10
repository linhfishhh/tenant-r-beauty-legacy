<?php

namespace App\Http\Middleware;

use App\Classes\Attachment;
use App\Classes\PostType;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApplyPostAttachment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var PostType $post_type */
        $post_type = $request->route()->parameter('post_type');
        $attachment_id = $request->route()->parameter('attachment');
        /** @var Attachment $attachment_type */
        $attachment_type = $post_type::getAttachmentType();
        $attachment = $attachment_type::find($attachment_id);

        if(!$attachment){
            throw new NotFoundHttpException();
        }

        $post = $attachment->post;
        if(!$post){
            throw new NotFoundHttpException();
        }

        if(!$attachment->isDownloadable()){
            throw new NotFoundHttpException();
        }

        $file = $attachment->file;
        if(!$file){
            throw new NotFoundHttpException();
        }
        $request->route()->setParameter(
            'attachment',
            $attachment);
        return $next($request);
    }
}
