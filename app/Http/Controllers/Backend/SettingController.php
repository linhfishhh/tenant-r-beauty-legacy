<?php

namespace App\Http\Controllers\Backend;

use App\Classes\BackendSettingPage;
use App\Http\Controllers\Controller;
use App\Http\Requests\BackendSettingPageSave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SettingController extends Controller
{
    function get(Request $request)
    {
        if (!$request->ajax()) {
            throw new NotFoundHttpException();
        }
        $name = Input::get('name', null);
        $default = Input::get('default', null);
        if (!$name) {
            return Response::json($default);
        }
        $value = getSetting($name, $default);
        return Response::json($value);
    }

    function set(Request $request)
    {
        if (!$request->ajax()) {
            throw new NotFoundHttpException();
        }
        $name = Input::get('name', null);
        $value = Input::get('value', null);

        if (!$name) {
            return Response::json('', 500);
        }
        setSetting($name, $value);
        return Response::json(true);
    }

    /**
     * @param Request $request
     * @param BackendSettingPage $page
     * @return \Illuminate\View\View
     */
    function edit(Request $request, $page)
    {
        $settings = $page->getSettings();
        return $page->getView($settings);
    }

    /**
     * @param BackendSettingPageSave $request
     * @param BackendSettingPage $page
     * @return \Illuminate\Http\JsonResponse
     */
    function save(BackendSettingPageSave $request, $page)
    {
        $fields = $request->all();
        foreach ($fields as $field_name=>$value){
            $fields[$field_name] = $page->handleField($field_name, $value);
        }
        $page->saveSettings($fields);
        return Response::json();
    }
}
