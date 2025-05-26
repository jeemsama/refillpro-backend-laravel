<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class OwnerForgotPasswordController extends Controller
{
    /**
     * Show the “enter your email” form.
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email', [
            'url'   => 'owner',
            'title' => 'Refilling Station Owner',
        ]);
    }

    /**
     * Handle the form submission and send the reset link.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:refilling_station_owners,email',
        ]);

        $status = Password::broker('owners')
            ->sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        return back()->withErrors(['email' => __($status)]);
    }
}
