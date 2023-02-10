<?php

namespace App\Http\Controllers\Backend;

use App\Classes\FieldInput\FieldInputFile;
use App\Events\UserFilterQuery;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserDestroy;
use App\Http\Requests\UserEdit;
use App\Http\Requests\UserPut;
use App\Http\Requests\UserStore;
use App\Http\Requests\UserUpdate;
use App\Role;
use App\UploadedFile;
use App\User;
use Auth;
use DataTables;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Redirect;
use Response;
use Modules\ModHairWorld\Handlers\AuthHandler;

class UserController extends Controller
{
    use AuthHandler;

	function select(Request $request){
		if($request->ajax()){
			$users = User::with( 'role');
			$search = $request->get( 'search',false);
			if($search){
				$users->whereNested( function ($query) use ($search){
					/** @var Builder $query */
					$query->where('name', 'like', '%'.$search.'%');
					$query->orWhere('email', 'like', '%'.$search.'%');
				});
			}
			$users = $users->get();
			return Response::json($users);
		}
	}

    function put(UserPut $request)
    {
        if ($request->ajax()) {
            $user = User::find($request->input('pk'));
            $user->setAttribute($request->input('name'), $request->input('value'));
            $user->save();
            return Response::json('');
        }
        return \Redirect::route('backend.user.index');
    }

    function index(Request $request)
    {
        /** @var Role[] $roles */
        $roles = Role::withCount('users')->get();
        if ($request->ajax()) {
            $users = User::with(['role', 'avatar']);
            if(!Auth::user()->isUltimateUser()){
	            $users->where('role_id', '!=', config('app.ultimate_role_id'));
            }
            $rs =  DataTables::eloquent($users)->addColumn('role', function (User $user) {
                return $user->role->title;
            })
            ->addColumn('avatar', function (User $user) {
                if($user->avatar){
                    return $user->avatar->getThumbnailUrl(config('app.default_thumbnail_name'),getNoAvatarUrl());
                }
                else{
                    return getNoAvatarUrl();
                }
            })
            ->addColumn('link', function (User $user) {
                return route('backend.user.edit', [$user]);
            })
            ->addColumn('is_me', function (User $user) {
	            return Auth::user()->id == $user->id?1:0;
            })
            ->addColumn('is_ultimate_user', function (User $user) {
	            return $user->isUltimateUser()?1:0;
            });
            $rs->filter(function($query) use($request){
                /** @var Builder $query */
                $keyword = "%{$request->get( 'search')['value']}%";
                if($keyword){
                    $query->where(function($query) use ($keyword, $request){
                        /** @var Builder $query */
                        $query->where('name', 'like', $keyword);
                        $query->orWhere('email', 'like', $keyword);
                        $event = new UserFilterQuery($query, $request);
                        \Event::fire($event);
                        $query = $event->query;
                    });
                }
                $role_id = $request->get('role_id', '-1');
                if($role_id != '-1'){
                    $query->where('role_id','=', $role_id);
                }
            });
            $rs = $rs->make(true);
            $counts = [
                '-1' => 0
            ];
            foreach ($roles as $role){
                $count = $role->users_count;
                $counts[''.$role->id] = $count;
                $counts['-1'] += $count;
            }
            $data         = $rs->getData();
            $data->counts = $counts;
            $rs->setData( $data );
            return $rs;
        }
        return view('backend.pages.user.index', ['roles' => $roles]);
    }

    function create()
    {
        return view('backend.pages.user.edit', [
            'model' => null,
        ]);
    }

    function store(UserStore $user_form){
        if($user_form->ajax()){
            $user = new User();
            $user->email = $user_form->get('email');
            $user->name = $user_form->get('name');
            $user->role_id = $user_form->get('role_id');
            if($user_form->get('password')){
                $user->password = bcrypt($user_form->get('password'));
            }
            $user->save();
            $this->syncData($user->password, $user);
            return Response::json(route('backend.user.edit', ['user'=>$user]));
        }
        return Redirect::route('backend.user.index');
    }

    function edit(UserEdit $request, User $user){
	    $avatar_field = new FieldInputFile(
            'avatar_id',
            $user->avatar_id,
            __('Ảnh đại diện'),
            '',
            false,
            FieldInputFile::buildConfigs(
                __('Chọn ảnh đại diện'),
                __('Chọn ảnh'),
                [User::getFileID()],
                ['image'],
                $user->id
                )
            );
        return view('backend.pages.user.edit', [
            'model' => $user,
            'avatar_field' => $avatar_field
        ]);
    }

    function update(UserUpdate $user_form, User $user){
        if($user_form->ajax()){
            $user->name = $user_form->get('name');
            $user->role_id = $user_form->get('role_id');
            if($user_form->get('password')){
                $user->password = bcrypt($user_form->get('password'));
            }
            $avatar_id =  $user_form->get('avatar_id');
            /** @var UploadedFile[] $old */
            $old = UploadedFile::whereUserId($user->id)->where('category', User::getFileID())->where('id', '!=', $avatar_id)->get();
            foreach ($old as $file){
                $file->delete();
            }
            $user->avatar_id = $avatar_id;
            $user->save();
            $this->syncData($user->password, $user);
            return Response::json('');
        }
        return Redirect::route('backend.user.index');
    }

	function destroy(UserDestroy $request)
    {
        if ($request->ajax()) {
	        $ids = Input::get('ids', []);
	        /** @var User[] $users */
	        $users = User::whereIn('id', $ids)->get();
	        foreach ($users as $user){
	            $user->delete();
            }
            return Response::json('');
        }

        return Redirect::route('backend.user.index');
    }

    function getInfo(Request $request){
	    $rs = [];
	    $ids = $request->get('ids', []);
	    if($ids){
	        $rs = User::whereIn('id', $ids)->get();
        }
	    return Response::json($rs);
    }
}
