<?php

namespace App\Http\Controllers\Backend;

use App\Classes\FileTypeGroup;
use App\Events\FileTypeGroupRegister;
use App\Http\Controllers\Controller;
use App\Http\Requests\FileUpload;
use App\UploadedFile;
use DataTables;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FileManagerController extends Controller
{
    private function getExtensionFromGroups($groups){
        /**@var FileTypeGroup[] $groups  */
        $rs = [];
        foreach ($groups as $group){
            $rs = array_merge($rs, $group->getExtensions());
        }
        $rs = array_unique($rs);
        return $rs;
    }

    private function countMode(Request $request){
        $rs = [];
        /** @var FileTypeGroupRegister $groups */
        $groups = app('file_type_groups');
        $groups = $groups->getGroups();
        $settings = $request->get('settings', []);
        $owned = isset($settings['owned'])?$settings['owned']:0;
        $owned = intval($owned);
        $has_limit = false;
        if(isset($settings['limit'])){
            if(count($settings['limit'])>0){
                $has_limit = true;
            }
            $groups = $this->limitTypeGroups($groups, $settings['limit']);
            if(count($groups) == 0){
                $has_limit = false;
                $groups = $groups->getGroups();
            }
        }
        $extensions = [];
        $rs['all'] = UploadedFile::query();
        $rs['all'] = $this->queryFilter($request, $rs['all']);
        $limit_exts = [];
        foreach ($groups as $group_id=>$group){
            $limit_exts = array_merge($limit_exts, $group->getExtensions());
        }
        if($limit_exts){
            $rs['all']->whereIn('extension', $limit_exts);
        }
        if($owned){
            $rs['all']->where('user_id', '=', $owned);
        }
        $rs['all'] = $rs['all']->count();
        foreach ($groups as $group_id=>$group){
            $exts = $group->getExtensions();
            $q = UploadedFile::query();
            $q = $this->queryFilter($request, $q);
            if($owned){
                $q->where('user_id', '=', $owned);
            }
            $rs[$group_id] = $q->whereIn('extension',$exts)->count();
            $extensions = array_merge($extensions, $group->getExtensions());
        }
        if(!$has_limit){
            $extensions = array_unique($extensions);
            $other = UploadedFile::query();
            $other = $this->queryFilter($request, $other);
            if($owned){
                $other->where('user_id', '=', $owned);
            }
            $rs['other'] = $other->whereNotIn('extension', $extensions)->count();
        }
        return $rs;
    }

    private function limitTypeGroups($groups, $limit){
        $rs = collect();
        foreach ($groups as $group_id=>$group){
            if(!in_array($group_id, $limit)){
                continue;
            }
            $rs->put($group_id, $group);
        }
        return $rs;
    }

    private function queryFilter(Request $request, $query){
        /** @var Builder|UploadedFile $query */
        $cat = $request->get('category', 'all');
        if($cat!= 'all'){
            $query->where('category', '=', $cat);
        }
        $keyword = $request->get( 'search')['value'];
        $query->whereRaw("CONCAT(name,'.',extension) like ?", ['%'.$keyword.'%']);
        return $query;
    }

    function getInfo(Request $request){
        $ids = $request->get('ids', []);
        $rs = [];
        if($ids){
            $ids_ordered = implode(',', $ids);
            /** @var UploadedFile[]|Collection $q */
            $q = UploadedFile::whereIn('id', $ids)
                ->orderByRaw(DB::raw("FIELD(id, $ids_ordered)"))
                ->get();
            foreach ($q as $item) {
                $file = $item->toArray();
                $file['link'] = url($item->getUploadFilePath());
                $file['thumbnail'] = url($item->getThumbnailUrl(config('app.default_thumbnail_name')));
                $rs[] = $file;
            }
        }
        return \Response::json($rs);
    }

    function index(Request $request){
    	\Debugbar::disable();
    	/** @var FileTypeGroupRegister $groups */
    	$groups = app('file_type_groups');
    	$groups = $groups->getGroups();
    	if($request->ajax()){
    		$files = UploadedFile::with(['user' => function($query){
    		    $query->select(['id', 'email', 'name']);
            }]);
            $settings = $request->get('settings', []);
            $owned = isset($settings['owned'])?$settings['owned']:0;
            $owned = intval($owned);
    		if($owned){
                $files->where('user_id', '=', $owned);
            }
            //dd($owned);
		    $rs           = DataTables::eloquent( $files );
		    $rs->filter( function($query) use ($request){
                /** @var Builder|UploadedFile $query */
                $view_mode = $request->get( 'view_mode', 'all' );
                $user_id = $request->get('user_id',0);
                if($user_id){
                    $query->where('user_id', $user_id);
                }
                if($view_mode != 'all'){
                    /** @var FileTypeGroupRegister $groups */
                    $groups = app('file_type_groups');
                    $groups = $groups->getGroups();
                    $extensions = [];
                    foreach ($groups as $group_id=>$group){
                        $exts = $group->getExtensions();
                        if($view_mode == $group_id){
                            $query->whereIn('extension',$exts);
                            break;
                        }
                        $extensions = array_merge($extensions, $exts);
                    }
                    if($view_mode == 'other'){
                        $query->whereNotIn('extension',$extensions);
                    }

                }
                else{
                    /** @var FileTypeGroupRegister $groups */
                    $groups = app('file_type_groups');
                    $groups = $groups->getGroups();
                    $extensions = [];
                    $limit_groups = [];
                    $settings = $request->get('settings', []);
                    if(isset($settings['limit'])){
                        $limit_groups = $settings['limit'];
                    }
                    if(count($limit_groups) == 0){
                        $limit_groups = $groups->keys()->all();
                    }
                    foreach ($groups as $group_id=>$group){
                        if(!in_array($group_id, $limit_groups)){
                            continue;
                        }
                        $exts = $group->getExtensions();
                        $extensions = array_merge($extensions, $exts);
                    }
                    $query->whereIn('extension',$extensions);
                }
		    	$query = $this->queryFilter($request, $query);
		    });
		    $rs->addColumn( 'thumbnail', function (UploadedFile $file){
		    	return $file->getThumbnailUrl( config('app.default_thumbnail_name'));
		    });
            $rs->addColumn( 'link', function (UploadedFile $file){
                return url($file->getUploadFilePath());
            });
            $rs->addColumn( 'category', function (UploadedFile $file){
                $cats = getFileCategories();
                return $cats->get($file->category,'N/A');
            });
		    $rs = $rs->make();
            $data         = $rs->getData();
            $data->counts = $this->countMode( $request );
            $rs->setData( $data );
		    return $rs;
	    }
	    $settings = $request->get('settings', []);

    	$has_limit = false;
    	if(isset($settings['limit'])){
            $groups = $this->limitTypeGroups($groups, $settings['limit']);
            $has_limit = true;
        }
        if(count($groups)==0){
            /** @var FileTypeGroupRegister $groups */
            $groups = app('file_type_groups');
    	    $groups = $groups->getGroups();
    	    $has_limit = false;
        }
        $support_extensions = $this->getExtensionFromGroups($groups);
    	$max_size = getFileUploadMaxSize();
    	$saved_max_size = getSetting('file_upload_max_size', $max_size/(1024*1024));
    	$saved_max_size = $saved_max_size *(1024*1024);
    	if($saved_max_size > $max_size){
    	    $saved_max_size = $max_size;
        }
        $saved_max_size = $saved_max_size/1024.0;
    	$select_limit = 0;
    	if(isset($settings['select'])){
    	    $select_limit = $settings['select'];
        }
		return view('backend.pages.file.manager', [
		    'type_groups'=>$groups,
            'settings'=>$settings,
            'has_limit'=>$has_limit,
            'extensions' => $support_extensions,
            'max_file_size' => $saved_max_size,
            'select_limit' => $select_limit
        ]);
    }

	/**
	 * @param Request $request
	 *
	 * @throws \Exception
	 */
	function destroy(Request $request){
		$ids = $request->get( 'ids', []);
		/** @var UploadedFile[] $files */
		$files = UploadedFile::whereIn('id', $ids)->get();
		foreach ($files as $file){
			$file->delete();
		}
        return \Response::json();
    }

    function upload(FileUpload $request){
    	$file = $request->file('upload');
    	$owned_by = $request->get('owned', false);
        $category = $request->get('category');
    	UploadedFile::upload(
            $file,
            $owned_by,
            $category);
		return \Response::json();
    }

    function uploadFormUrl($url, $owned_by, $category){

    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function regenerateThumbnailTool(Request $request){
        if($request->ajax()){
            $img_exts = config('app.thumbnail_support_files');
            /** @var UploadedFile[]|Collection $files */
            $files = UploadedFile::whereIn(
                'extension',
                $img_exts)->get(['id'])->map(function($item){
                return $item->id;
            });
            return \Response::json($files->all());

        }
        return view('backend.pages.tools.regenerate_thumbnails', ['sizes'=>app('thumbnail_sizes')->getSizes()]);
    }

    function regenerateThumbnailToolRun(Request $request, UploadedFile $file){
        if($request->ajax()){
            $result = 1;
            try{
                $file->deleteThumbnails();
                $file->generateThumbnails();
            }
            catch (\Exception $exception){
                $result = 0;
            }
            return \Response::json([
                'result' => $result,
                'file' => $file
                                   ]);
        }
        throw new NotFoundHttpException();
    }
}
