<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function reset(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email|exists:refilling_station_owners,email',
            'password' => 'required|confirmed|min:8',
        ]);

        // use the 'owners' broker
        $status = Password::broker('owners')->reset(
            $request->only('email','password','password_confirmation','token'),
            function ($owner, $password) {
                $owner->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return response()->json([
            'message' => __($status)
        ], $status === Password::PASSWORD_RESET ? 200 : 400);
    }
}
