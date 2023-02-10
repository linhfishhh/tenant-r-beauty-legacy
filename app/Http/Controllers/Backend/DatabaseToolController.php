<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 16-Apr-18
 * Time: 09:02
 */

namespace App\Http\Controllers\Backend;


use App\Events\SiteURLChanged;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DatabaseToolController extends Controller
{
    function changeSiteUrlIndex(Request $request){
        $event = new SiteURLChanged(
            '',
            '');
        event($event);
        $changers = $event->getChangers();
        return view('backend.pages.tools.db_site_url_changer', ['changers'=>$changers]);
    }

    function changeSiteUrlAnalyze(Request $request){
        if($request->ajax()){
            $rs = [];
            $old_url = $request->get('old_url');
            $event = new SiteURLChanged(
                $old_url,
                '');
            $table = $request->get('table');
            event($event);
            $columns = $event->getChangerByTable($table);
            if(!$columns){
                throw new NotFoundHttpException(__('Yêu cầu không hợp lệ'));
            }
            $query = \DB::table($table);
            $keyword = '%'.$old_url.'%';
            foreach ($columns as $k=>$column){
                $query->selectRaw("count($column like '$keyword') as '{$column}_count'");
            }
            return \Response::json($query->toSql());
        }
        throw new NotFoundHttpException();
    }
}