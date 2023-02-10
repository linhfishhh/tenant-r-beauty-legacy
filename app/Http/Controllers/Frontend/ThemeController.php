<?php

namespace App\Http\Controllers\Frontend;

use App\Classes\Theme;
use App\Events\ThemeIndexViewData;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    function index(Request $request){
        $data = [];
        $event = new ThemeIndexViewData($request,$data);
        event($event);
        $data = $event->data;
        return Theme::getIndexView($data);
    }
}
