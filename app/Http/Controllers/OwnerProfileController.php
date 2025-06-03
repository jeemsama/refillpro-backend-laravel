<?php

namespace App\Http\Controllers;

use App\Models\RefillingStationOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnerProfileController extends Controller
{
    public function show(Request $request)
    {
        /** @var RefillingStationOwner $owner */
        $owner = Auth::guard('sanctum')->user();

        return response()->json([
            // Add this line so Flutter can read “shop_id” directly:
            'shop_id'        => $owner->id,

            'shop_name'      => $owner->shop_name,
            'contact_number' => $owner->phone,
            'shop_photo'     => $owner->shop_photo,
            // … any other fields you already return
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
                'shop_id'        => $owner->id,          // return it here too
                'shop_name'      => $owner->shop_name,
                'contact_number' => $owner->phone,
            ],
        ], 200);
    }

    public function updatePhoto(Request $req)
    {
        $req->validate(['shop_photo' => 'required|image|max:2048']);
        $owner = Auth::guard('sanctum')->user();
        $path = $req->file('shop_photo')->store('shop_photo', 'public');
        $owner->shop_photo = $path;
        $owner->save();

        return response()->json([
            'shop_photo' => $path,
            'shop_id'    => $owner->id, // include shop_id here as well
        ], 200);
    }
}
