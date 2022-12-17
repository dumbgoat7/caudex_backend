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
        if (is_null($user)) {
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //masuk ke method update
        return $this->update($request, $request->id);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $input = $request->all();

        $user = User::find($id);

        if (is_null($user)) {
            return new UserResource(false, 'User not Found', null);
        }

        $image = $request->user_photo;
        if ($image != $user->user_photo) {
            $imageName = $request->file('user_photo')->getClientOriginalName();
            $request->user_photo->move(public_path('images'), $imageName);
            $input['user_photo'] = "$imageName";
            $user->user_photo = $input['user_photo'];
        }

        if ($request->password != null) {
            $input['password'] = bcrypt($request->password);
            $user->password = $input['password'];
        }

        $user->save();

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
        if (is_null($user)) {
            return new UserResource(false, 'User not Found', null);
        }
        $user->delete();
        return new UserResource(true, 'Success Delete User', $user);
    }
}
