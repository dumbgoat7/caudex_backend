<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserResource;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::get();
        return new UserResource(true, 'All Users', $users);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        if(is_null($user)) {
            return new UserResource(false, 'User not Found', null);
        }
        return new UserResource(true, 'User Found', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'user_photo' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ], [
            'user_name.required' => 'Name must be filled',
            'email.required' => 'Email must be filled',
            'password.required' => 'Password must be filled',
            'user_photo.required' => 'Please input a photo',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $input = $request->all();

        if ($image = $request->file('user_photo')) {
            $destinationPath = 'images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['user_photo'] = "$profileImage";
        } else {
            unset($input['user_photo']);
        }

        $user = User::find($id);
        if(is_null($user)) {
            return new UserResource(false, 'User not Found', null);
        }
        $user->update($input);
        return new UserResource(true, 'Success Update User', $user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if(is_null($user)) {
            return new UserResource(false, 'User not Found', null);
        }
        $user->delete();
        return new UserResource(true, 'Success Delete User', $user);
    }
}
