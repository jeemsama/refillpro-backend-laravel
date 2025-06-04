<?php

namespace App\Http\Controllers;

use App\Models\RefillingStationOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class OwnerProfileController extends Controller
{
    public function show(Request $request)
    {
        /** @var RefillingStationOwner $owner */
        $owner = Auth::guard('sanctum')->user();

        return response()->json([
            'shop_id'        => $owner->id,
            'shop_name'      => $owner->shop_name,
            'contact_number' => $owner->phone,
            'address'        => $owner->address,        // ← add this
            'latitude'       => $owner->latitude,       // ← add this
            'longitude'      => $owner->longitude,      // ← add this
            'shop_photo'     => $owner->shop_photo,
        ], 200);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'shop_name'      => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'address'        => 'required|string|max:255',
            'latitude'       => 'required|numeric',
            'longitude'      => 'required|numeric',
        ]);

        // Enforce that address must contain “Carig Sur”
        if (stripos($data['address'], 'Carig Sur') === false) {
            throw ValidationException::withMessages([
                'address' => ['Address must be located in Carig Sur.'],
            ]);
        }

        /** @var RefillingStationOwner $owner */
        $owner = Auth::guard('sanctum')->user();

        // Save every field back to the database
        $owner->shop_name      = $data['shop_name'];
        $owner->phone          = $data['contact_number'];
        $owner->address        = $data['address'];
        $owner->latitude       = $data['latitude'];
        $owner->longitude      = $data['longitude'];
        $owner->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'data'    => [
                'shop_id'        => $owner->id,
                'shop_name'      => $owner->shop_name,
                'contact_number' => $owner->phone,
                'address'        => $owner->address,
                'latitude'       => $owner->latitude,
                'longitude'      => $owner->longitude,
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
            'shop_id'    => $owner->id,
        ], 200);
    }
}
