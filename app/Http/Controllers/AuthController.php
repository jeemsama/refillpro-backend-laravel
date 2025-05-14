<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\RefillingStationOwner;
use App\Models\Rider;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
    
        // Check if email belongs to owner
        $owner = RefillingStationOwner::where('email', $request->email)->first();
        if ($owner && Hash::check($request->password, $owner->password)) {
            if ($owner->status !== 'approved') {
                return response()->json(['message' => 'Account not approved'], 403);
            }

            $token = $owner->createToken('owner-device')->plainTextToken;
            
            return response()->json([
                'user' => $owner,
                'role' => 'owner',
                'token' => $token,
            ], 200);
        }
    
        // Check if email belongs to rider
        $rider = Rider::where('email', $request->email)->first();
        if ($rider && Hash::check($request->password, $rider->password)) {
            
            $token = $rider->createToken('rider-device')->plainTextToken;

            return response()->json([
                'user' => $rider,
                'role' => 'rider',
                'token' => '$token'
            ], 200);
        }
    
        return response()->json(['message' => 'Invalid credentials'], 401);

        
    }

    


    
    
}
