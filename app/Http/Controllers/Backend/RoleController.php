<?php

namespace App\Http\Controllers\Backend;

use App\Classes\Permission;
use App\Http\Requests\RoleDestroy;
use App\Http\Requests\RoleEdit;
use App\Http\Requests\RoleMove;
use App\Http\Requests\RolePut;
use App\Http\Requests\RoleStore;
use App\Http\Requests\RoleUpdate;
use App\Role;
use App\RolePermission;
use App\User;
use Auth;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;
use Illuminate\Support\Facades\Input;
use Redirect;
use Response;

class RoleController extends Controller
{
    function select(Request $request){
	    if(Auth::user()->isUltimateUser()){
	    	$rs = Role::get(['id', 'title']);
	    }
	    else{
		    $rs = Role::whereKeyNot( config('app.ultimate_role_id'))->get(['id', 'title']);
	    }
        $items = [];
        foreach ($rs as $r){
            $items[] = [
                'id' => $r->id,
                'text' => $r->title
            ];
        }
        return \Response::json($items);
    }

    function put(RolePut $request)
    {
        if ($request->ajax()) {
            $role = Role::find($request->input('pk'));
            $role->setAttribute($request->input('name'), $request->input('value'));
            $role->save();
            return Response::json('');
        }
        return \Redirect::route('backend.user.index');
    }

    function index(Request $request){
        if ($request->ajax()) {
            $roles = Role::withCount('users');
            if(!Auth::user()->isUltimateUser()){
            	$roles->where( 'id', '!=', config('app.ultimate_role_id'));
            }
            return DataTables::eloquent($roles)->addColumn('users_count', function (Role $role) {
                return $role->users_count;
            })
                ->addColumn('link', function (Role $role) {
                    return route('backend.role.edit', [$role]);
                })
	            ->addColumn('is_my_role', function (Role $role) {
		            return $role->id == Auth::user()->role_id ? 1: 0;
	            })
	            ->addColumn('is_ultimate_role', function (Role $role) {
		            return $role->isUltimateRole() ? 1: 0;
	            })
                ->make(true);
        }
        return view('backend.pages.role.index');
    }

    function create(){
        return view('backend.pages.role.edit', [
            'model' => null
        ]);
    }

    private function storePermissions(Role $role, $permissions = []){
	    foreach ($permissions as $permission_id){
	    	if(!Auth::user()->hasPermission( $permission_id)){
	    		continue;
		    }
		    $permission = new RolePermission();
		    $permission->role_id = $role->id;
		    $permission->permission = $permission_id;
		    $permission->save();
	    }
    }

    function store(RoleStore $role_form){
        $role = new Role();
        $role->title = $role_form->get('title');
        $role->desc = $role_form->get('desc');
        if($role->save()){
            $permissions = $role_form->get('permissions', []);
            $this->storePermissions( $role, $permissions);
        }
        return Response::json(route('backend.role.edit', ['role'=>$role]));
    }

    function edit(RoleEdit $request, Role $role){
        return view('backend.pages.role.edit', [
            'model' => $role
        ]);
    }

    function update(RoleUpdate $role_form, Role $role){
        if($role_form->ajax()){
	        $role->title = $role_form->get('title');
	        $role->desc = $role_form->get('desc');
	        if($role->save()){
		        if(!$role->isUltimateRole() && !(Auth::user()->isMyRole( $role->id))){
			        $permissions = $role_form->get('permissions', []);
			        RolePermission::whereRoleId($role->id)->delete();
			        $this->storePermissions( $role, $permissions);
		        }
	        }
	        return Response::json('');
        }
	    return Redirect::route('backend.role.index');
    }

    function move(RoleMove $request){
        if ($request->ajax()) {
	        $ids = Input::get('ids', []);
	        $new_id = Input::get('new_id', null);
            User::whereIn('role_id', $ids)->update([
                'role_id' => $new_id
            ]);
            return Response::json('');
        }

        return Redirect::route('backend.role.index');
    }

    function destroy(RoleDestroy $request){
        if ($request->ajax()) {
	        $ids = Input::get('ids', []);
	        /** @var \Illuminate\Database\Eloquent\Builder $roles */
	        $roles = Role::whereIn('id', $ids);
	        $roles =  $roles->get();
	        foreach ($roles as $role){
		        $role->delete();
	        }
            return Response::json('');
        }

        return Redirect::route('backend.role.index');
    }
}
