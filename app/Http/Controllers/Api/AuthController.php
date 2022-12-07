<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\UserVerify;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $registrationData = $request->all();

        $validate = Validator::make($registrationData, [
            'user_name' => 'required|max:60',
            'user_birthdate' => 'required|date',
            'user_password' => 'required',
            'user_email' => 'required|email:rfc,dns|unique:users',
            'user_role' => 'required',
            'user_photo' => 'required|image:jpeg,png,jpg,gif,svg|max:2048',
            'user_verification' => 'required'
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
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

        $token = $user->createToken('Authentiucation Token')->accessToken;

        Mail::send($user->user_email, ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Email Verification Mail');
        });

        return response([
            'message' => 'Register Success',
            'user' => $user
        ], 200);
    }

    public function login(Request $request)
    {
        $loginData = $request->all();

        $validate = Validator::make($loginData, [
            'user_email' => 'required|email:rfc,dns',
            'user_password' => 'required'
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->error()], 400);
        }

        if (!Auth::attempt($loginData)) {
            return response(['message' => 'Invalid Credential'], 401);
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

    // public function logout(Request $request)
    // {
    //     $request->user()->token()->revoke();

    //     return response([
    //         'message' => 'Logged out'
    //     ], 200);
    // }

    public function logout()
    {
        Session::flush();
        Auth::logout();

        return response([
            'message' => 'Logged out'
        ], 200);
    }

    public function verifyAccount($token)
    {
        $verifyUser = UserVerify::where('token', $token)->first();

        $message = 'Sorry your email cannot be identified.';

        if (!is_null($verifyUser)) {
            $user = $verifyUser->user;

            if (!$user->is_email_verified) {
                $verifyUser->user->is_email_verified = 1;
                $verifyUser->user->save();
                $message = "Your e-mail is verified. You can now login.";
            } else {
                $message = "Your e-mail is already verified. You can now login.";
            }
        }

        return redirect()->route('login')->with('message', $message);
    }
}
