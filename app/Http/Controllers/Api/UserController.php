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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::get();
        return new UserResource(true, 'All Users', $users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required',
            'user_email' => 'required',
            'user_password' => 'required',
            'user_role' => 'required',
            'user_photo' => 'required',
            'user_birthdate' => 'required',
        ], [
            'name.required' => 'Name must be filled',
            'email.required' => 'Email must be filled',
            'password.required' => 'Password must be filled',
            'user_role.required' => 'Role must be filled',
            'user_photo.required' => 'Please input a photo',
            'user_birthdate.required' => 'Birthdate must be filled',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $input = $request->all();

        // if ($image = $request->file('user_photo')) {
        //     $destinationPath = 'images/';
        //     $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
        //     $image->move($destinationPath, $profileImage);
        //     $input['user_photo'] = "$profileImage";
        // }

        User::create($input);
        return new UserResource(true, 'Success Add User', $input);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //buat edit
        $user = User::find($id);
        return new UserResource(true, 'All Users', $user);
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
            'user_email' => 'required',
            'user_password' => 'required',
            'user_role' => 'required',
            'user_photo' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ], [
            'name.required' => 'Name must be filled',
            'email.required' => 'Email must be filled',
            'password.required' => 'Password must be filled',
            'user_role.required' => 'Role must be filled',
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
        $user->delete();
        return new UserResource(true, 'Success Delete User', $user);
    }
}
