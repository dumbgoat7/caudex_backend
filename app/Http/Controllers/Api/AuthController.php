<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $regintrationData = $request->all();

        $validate = Validator::make($regintrationData, [
            'user_name' => 'required|max:60',
            'user_birthdate' => 'required|date',
            'password' => 'required',
            'email' => 'required|email:rfc,dns|unique:users',
            'user_role' => 'required',
            'user_photo' => 'required',
            'user_verification' => 'required'
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        // $imageName = $request->file('image')->getClientOriginalName();
        // $request->image->move(public_path('images'), $imageName);
        // $regintrationData['image'] = $imageName;

        $regintrationData['password'] = bcrypt($request->password);

        $user = User::create($regintrationData);

        return response(['message' => 'Registration Success', 'user' => $user], 200);
    }

    public function login(Request $request)
    {
        $loginData = $request->all();

        $validate = Validator::make($loginData, [
            'email' => 'required|email:rfc,dns',
            'password' => 'required'
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        if (!Auth::attempt($loginData)) {
            return response(['message' => 'Invalid Credentials'], 401);
        }

        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('Authentication Token')->accessToken;

        return response(['message' => 'Authenticated', 'user' => $user, 'token_tyope' => 'Bearer', 'access_token' => $token]);
    }

    //logout dan hapus token
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response(['message' => 'Logout Success']);
    }
}
