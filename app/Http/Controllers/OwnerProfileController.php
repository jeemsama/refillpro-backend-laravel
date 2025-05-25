<?php

namespace App\Http\Controllers;

use App\Models\RefillingStationOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// ← you must extend this Controller
class OwnerProfileController extends Controller
{
    // No need for a constructor here if you apply middleware in routes

    public function show(Request $request)
    {
        /** @var RefillingStationOwner $owner */
        $owner = Auth::guard('sanctum')->user();

        return response()->json([
            'shop_name'      => $owner->shop_name,
            'contact_number' => $owner->phone,
        ], 200);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'shop_name'      => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
        ]);

        /** @var RefillingStationOwner $owner */
        $owner = Auth::guard('sanctum')->user();
        $owner->shop_name = $data['shop_name'];
        $owner->phone     = $data['contact_number'];
        $owner->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'data'    => [
                'shop_name'      => $owner->shop_name,
                'contact_number' => $owner->phone,
            ],
        ], 200);
    }
}
