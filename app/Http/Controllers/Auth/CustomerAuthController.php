<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\OtpCode;
use App\Models\Customer;
use App\Mail\OtpMail;

class CustomerAuthController extends Controller
{
    /**
     * Send a 4-digit OTP to the given email, queueing the mail.
     */
    public function sendOtp(Request $req)
    {
        $req->validate(['email' => 'required|email']);

        $code    = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        $expires = now()->addMinutes(10);

        // Store OTP
        OtpCode::create([
            'email'      => $req->email,
            'code'       => $code,
            'expires_at' => $expires,
        ]);

        // Queue the OTP email
        Mail::to($req->email)
            ->queue(new OtpMail($code));

        return response()->json(['message' => 'OTP sent'], 200);
    }

    /**
     * Verify the submitted OTP and issue a Sanctum token.
     */
    public function verifyOtp(Request $req)
    {
        $req->validate([
            'email' => 'required|email',
            'code'  => 'required|digits:4',
        ]);

        $otp = OtpCode::where('email', $req->email)
            ->where('code', $req->code)
            ->where('expires_at', '>=', now())
            ->latest()
            ->first();

        if (!$otp) {
            return response()->json(['message' => 'Invalid or expired code'], 401);
        }

        \Log::info('OTP verified for: ' . $req->email);

        $customer = Customer::where('email', $req->email)->first();

        if (!$customer) {
            $customer = Customer::create([
                'email' => $req->email,
                'name' => '',
                'phone' => '',
                'address' => '',
                'profile_image' => null,
            ]);
        }

        // Reload fresh copy
        $customer->refresh();

        \Log::info('Customer instance: ', $customer->toArray());
        \Log::info('Customer ID: ' . $customer->id);

        $token = $customer->createToken('customer-app')->plainTextToken;

        OtpCode::where('email', $req->email)->delete();

        return response()->json([
            'user' => [
                'id' => $customer->id,
                'email' => $customer->email,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'profile_image_url' => $customer->profile_image 
                    ? asset('storage/' . $customer->profile_image) 
                    : null,
            ],
            'token' => $token,
        ], 200);
    }
}
