<?php

namespace App\Http\Controllers\Backend;

use App\Classes\Theme;
use App\Events\Theme\ThemeActived;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ThemeController extends Controller
{
    function index(Request $request){
        if($request->ajax()){
            /** @var \Nwidart\Modules\Laravel\Module[] $themes */
            $themes = Theme::getThemes();
            $active = $request->get('active', null);
            if($active){
                foreach ($themes as $theme){
                    if($theme->getAlias() == $active){
                        $theme->enable();
                        event(new ThemeActived($theme));
                    }
                    else{
                        $theme->disable();
                    }
                }
                $themes = Theme::getThemes();
            }
            usort($themes, function (\Nwidart\Modules\Laravel\Module $module1, \Nwidart\Modules\Laravel\Module $module2){
                $module1_sts = $module1->get('active', 0);
                $module2_sts = $module2->get('active', 0);

                if($module1_sts == $module2_sts){
                    return 0;
                }

                if($module1_sts > $module2_sts){
                    return -1;
                }

                return 1;
            });
            $rs = [];
            foreach ($themes as $theme){
                $cover = Theme::getCover($theme->getAlias());
                if(!$cover){
                    $cover = asset('assets/ui/images/placeholder.jpg');
                }
                $support_menu = count( Theme::getMenuLocations($theme->getAlias()))>0;
                $support_sidebar = count( Theme::getSidebarLocations($theme->getAlias()))>0;
                $rs [] = [
                    'title' => $theme->getName(),
                    'id' => $theme->getAlias(),
                    'description' => $theme->getDescription(),
                    'cover' => $cover,
                    'active' => $theme->enabled(),
	                'support_menu' => $support_menu,
	                'support_sidebar' => $support_sidebar
                ];
            }
            return \Response::json($rs);
        }
        return view('backend.pages.theme.index');
    }

    function cover($theme){
        /** @var \Nwidart\Modules\Laravel\Module $module */
        $module = \Module::find($theme);
        if(!$module){
            throw new NotFoundHttpException();
        }
        $path = $module->getExtraPath('theme.jpg');
        if(!\File::exists($path)){
            throw new NotFoundHttpException();
        }
        return \Response::file($path);
    }
}
