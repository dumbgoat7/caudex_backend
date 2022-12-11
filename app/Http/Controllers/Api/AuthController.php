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
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

class AuthController extends Controller
{
    use HasApiTokens;
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {
        $registrationData = $request->all();

        $validate = Validator::make($registrationData, [
            'user_name' => 'required|max:60',
            'user_birthdate' => 'required|date',
            'user_password' => 'required',
            'user_email' => 'required|email:rfc,dns|unique:users',
            'user_role' => 'required',
            'user_photo' => 'required',
            'user_verification' => 'required'
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        // $uploadFolder = 'users';
        // $image = $request->file('user_photo');
        // $image_uploaded_path = $image->store($uploadFolder, 'public');
        // $uploadedImageResponse = basename($image_uploaded_path);
        // "image_url" => Storage::disk('public')->url($image_uploaded_path),
        // "mime" => $image->getClientMimeType()

        // $registrationData['user_photo'] = $uploadedImageResponse;
        $registrationData['user_password'] = Hash::make($registrationData['user_password']);

        $user = User::create($registrationData);


        return new UserResource(true, 'Success Add User', $user);
    }

    public function login(Request $request)
    {
        $loginData = $request->all();

        $validate = Validator::make($loginData, [
            'user_password' => 'required',
            'user_email' => 'required|email:rfc,dns',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        //decrypt password
        $cek = User::where('user_email', $request->user_email)->first();
        if ($cek == null) {
            return response(['message' => 'Email Invalid'], 400);
        } else {
            $password = $cek->user_password;
            if (Hash::check($request->user_password, $password) == false) {
                return response(['message' => 'Wrong Password'], 400);
            }
        }

        $user = User::where('user_email', $request->user_email)->first();
        $token = $user->createToken('authToken')->accessToken;

        return response(['message' => 'Authenticated', 'user' => $user, 'token_type' => 'Bearer', 'access_token' => $token]);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
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
