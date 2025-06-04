<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RefillingStationOwner;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RefillingStationOwnerController extends Controller
{
        public function store(Request $request)
    {
        // Log that registration data was received
        Log::info('Refilling station owner registration data received', [
            'ip' => $request->ip(),
            'email' => $request->input('email'),
            'shop_name' => $request->input('shop_name'),
            'timestamp' => now()->toDateTimeString()
        ]);
        
        $validated = $request->validate([
            'name' => 'required',
            'phone' => 'required|digits:11|unique:refilling_station_owners,phone',
            'email' => 'required|email|unique:refilling_station_owners,email',
            'password' => 'required|min:6',
            'dti_permit_path' => 'nullable|file',
            'business_permit_path' => 'nullable|file',
            'shop_name' => 'required',
            'address' => 'required',
            'shop_photo' => 'nullable|file|image|max:5120', 
            'latitude' => 'required',
            'longitude' => 'required',
            'has_regular_gallon' => 'boolean',
            'regular_gallon_price' => 'nullable|numeric',
            'has_dispenser_gallon' => 'boolean',
            'dispenser_gallon_price' => 'nullable|numeric',
            'delivery_time_slots' => 'array',
            'agreed_to_terms' => 'boolean|required|in:1',
        ]);

        // Store files in public disk
            $validated['dti_permit_path'] = $request->hasFile('dti_permit_path')
            ? $request->file('dti_permit_path')->store('dti_permits', 'public')
            : '';

            $validated['business_permit_path'] = $request->hasFile('business_permit_path')
            ? $request->file('business_permit_path')->store('business_permits', 'public')
            : '';

            // if ($request->hasFile('shop_photo')) {
            //     $validated['shop_photo'] = $request->file('shop_photo')->store('shop_photos', 'public');
            // }

            $validated['shop_photo'] = $request->hasFile('shop_photo')
            ? $request->file('shop_photo')->store('shop_photo', 'public')
            : '';
            

            // Hash the password
            $validated['password'] = Hash::make($validated['password']);

            // Set initial status
            $validated['status'] = 'pending';

            // Create the shop owner
            $owner = RefillingStationOwner::create($validated);

            // Log successful registration
            Log::info('Refilling station owner registration successful', [
                'owner_id' => $owner->id,
                'email' => $owner->email,
                'shop_name' => $owner->shop_name,
                'status' => $owner->status,
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json(['message' => 'Registration submitted for approval.'], 201);
    }



    public function approvedStations(Request $request)
    {
                // start with only approved
        $query = RefillingStationOwner::where('status', 'approved');

        // if they passed owner_id, narrow it to exactly that record
        if ($request->has('owner_id')) {
            $query->where('id', $request->query('owner_id'));
        }
        $stations = RefillingStationOwner::where('status', 'approved')->get()->map(function ($station) {
            return [
                'id' => $station->id,
                'owner_id' => $station->id, // <= i add this line
                'shop_name' => $station->shop_name,
                'owner_name' => $station->name,
                'email' => $station->email,
                'phone' => $station->phone,
                'address' => $station->address,
                'latitude' => $station->latitude,
                'longitude' => $station->longitude,
                'shop_photo' => url('storage/' . $station->shop_photo),
                'gallons' => [
                    'regular' => $station->has_regular_gallon ? [
                        'available' => true,
                        'price' => $station->regular_gallon_price,
                    ] : null,
                    'dispenser' => $station->has_dispenser_gallon ? [
                        'available' => true,
                        'price' => $station->dispenser_gallon_price,
                    ] : null,
                ],
                'delivery_time_slots' => $station->delivery_time_slots,
            ];
        });
    
        return response()->json($stations);
    }

    public function index(Request $request)
    {
        // Fetch only those owners whose status is “approved”
        $stations = RefillingStationOwner::where('status', 'approved')
            ->get()
            ->map(function (RefillingStationOwner $station) {
                return [
                    'id'         => $station->id,
                    'name'       => $station->shop_name,
                    'latitude'   => (float) $station->latitude,
                    'longitude'  => (float) $station->longitude,
                    'address'    => $station->address,
                    // Optional: add any other fields you need for the client,
                    // e.g. shop_photo URL, owner name, phone, etc.
                ];
            });

        return response()->json($stations);
    }



}
