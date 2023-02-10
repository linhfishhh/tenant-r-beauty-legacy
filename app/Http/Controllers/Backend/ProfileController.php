<?php

namespace App\Http\Controllers\Backend;

use App\Classes\FieldInput\FieldInputFile;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdate;
use App\UploadedFile;
use App\User;
use Response;

class ProfileController extends Controller
{
    function edit(){
        $permissions = \Auth::user()->role->getPermissionInfo();
        $avatar_field = new FieldInputFile(
            'avatar_id',
            \Auth::user()->avatar_id,
            __('Ảnh đại diện'),
            '',
            false,
            FieldInputFile::buildConfigs(
                __('Chọn ảnh đại diện'),
                __('Chọn ảnh'),
                [User::getFileID()],
                ['image'],
                me()->id
            )
        );
        return view('backend.pages.profile.edit', [
            'permission_info' => $permissions,
            'avatar_field' => $avatar_field
        ]);
    }

    function update(ProfileUpdate $form){
        if($form->ajax()){
            $user = \Auth::user();
            $user->name = $form->get('name');
            if($form->get('password')){
                $user->password = bcrypt($form->get('password'));
            }
            $avatar_id =  $form->get('avatar_id');
            /** @var UploadedFile[] $old */
            $old = UploadedFile::whereUserId(me()->id)->where('category', User::getFileID())->where('id', '!=', $avatar_id)->get();
            foreach ($old as $file){
                $file->delete();
            }
            $user->avatar_id = $avatar_id;
            $user->save();
            return Response::json('');
        }
        return \Redirect::route('backend.index');
    }
}
