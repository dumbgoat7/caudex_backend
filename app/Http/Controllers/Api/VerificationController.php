<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Models\User;

class VerificationController extends Controller
{
    public function notice()
    {
        return response()->json([
            'message' => 'Please verify your email'
        ], 200);
    }

    public function verify(Request $request)
    {
        $user = User::findOrFail($request->route('id'));
        $user->update([
            'email_verified_at' => now()
        ]);

        return response()->json([
            'message' => 'Email verified'
        ], 200);
    }
}
