<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:refilling_station_owners,email',
    ]);

    $status = Password::broker('owners')
                    ->sendResetLink($request->only('email'));

    return response()->json(
      ['message' => __($status)],
      $status === Password::RESET_LINK_SENT ? 200 : 400
    );
}

}
