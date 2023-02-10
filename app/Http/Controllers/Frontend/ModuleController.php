<?php

namespace App\Http\Controllers\Frontend;

use App\Classes\Module;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ModuleController extends Controller
{
    function assetUrl(
        $module,
        $file
    ) {
        $asset_path = Module::getAsset(
            $module,
            $file
        );
        if ($asset_path) {
            $info = pathinfo($asset_path);
            $extension = strtolower($info['extension']);
            return \Response::file($asset_path, [
                'Content-Type' => getMime($extension)
            ]);
        } else {
            throw new NotFoundHttpException();
        }
    }
}
