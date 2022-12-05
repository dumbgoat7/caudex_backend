<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;


class AuthController extends Controller
{
    public function register(Request $request){
        $registrationData = $request->all();

        $validate = Validator::make($registrationData,[
            'user_name' => 'required|max:60',
            'user_birthdate' => 'required|date',
            'user_password' => 'required',
            'user_email' => 'required|email:rfc,dns|unique:users',
            'user_role' => 'required',
            'user_photo' => 'required|image:jpeg,png,jpg,gif,svg|max:2048',
            'user_verification' => 'required'
        ]);

        if ($validate->fails()) {
            return response(['message'=> $validate->errors()],400);
        }

        $uploadFolder = 'users';
        $image = $request->file('user_photo');
        $image_uploaded_path = $image->store($uploadFolder, 'public');
        $uploadedImageResponse = basename($image_uploaded_path);
            // "image_url" => Storage::disk('public')->url($image_uploaded_path),
            // "mime" => $image->getClientMimeType()

        $registrationData['user_photo'] = $uploadedImageResponse;
        $registrationData['user_password'] = bcrypt($request->password);

        $user = User::create($registrationData);

        return response([
            'message' => 'Register Success',
            'user' => $user 
        ],200);


    }

    public function login(Request $request)
    {
        $loginData = $request->all();

        $validate = Validator::make($loginData,[
            'user_email' => 'required|email:rfc,dns',
            'user_password' => 'required'
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->error()],400);
        }

        if (!Auth::attempt($loginData)) {
            return response(['message'=> 'Invalid Credential'],401);
        }

         /** @var \App\Models\User $user **/
        $user = Auth::user();
        $token = $user->createToken('Authentiucation Token')->accessToken;

        return response([
            'message' => 'Authenticated',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token
        ]);

    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response([
            'message' => 'Logged out'
        ], 200);
    }

}
