<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class OwnerResetPasswordController extends Controller
{
    /**
     * Where to redirect owners after a successful reset.
     */
    protected $redirectTo = '/owner/login';

    /**
     * Show the “set a new password” form.
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset', [
            'token' => $token,
            'email' => $request->email,
            'url'   => 'owner',
            'title' => 'Refilling Station Owner',
        ]);
    }

    /**
     * Handle the new‐password submission.
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token'                 => 'required',
            'email'                 => 'required|email|exists:refilling_station_owners,email',
            'password'              => 'required|confirmed|min:8',
        ]);

        $status = Password::broker('owners')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($owner, $password) {
                $owner->password = Hash::make($password);
                $owner->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect($this->redirectTo)
                   ->with('status', __($status));
        }

        return back()->withErrors(['email' => [__($status)]]);
    }
}
