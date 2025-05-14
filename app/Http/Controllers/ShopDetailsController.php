<?php

namespace App\Http\Controllers;

use App\Models\OwnerShopDetails;
use App\Models\RefillingStationOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopDetailsController extends Controller
{
    /**
     * Display a listing of shop details.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $shopDetails = OwnerShopDetails::with('owner')->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $shopDetails
        ]);
    }

    /**
     * Store a newly created shop details in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'owner_id' => 'required|exists:refilling_station_owners,id',
            'delivery_time_slots' => 'nullable|array',
            'collection_days' => 'nullable|array',
            'has_regular_gallon' => 'boolean',
            'regular_gallon_price' => 'nullable|numeric|min:0',
            'has_dispenser_gallon' => 'boolean',
            'dispenser_gallon_price' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if shop details already exist for this owner
        if (OwnerShopDetails::where('owner_id', $request->owner_id)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Shop details for this owner already exist'
            ], 422);
        }

        $shopDetails = OwnerShopDetails::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Shop details created successfully',
            'data' => $shopDetails
        ], 201);
    }

    /**
     * Display the specified shop details.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $shopDetails = OwnerShopDetails::with('owner')->find($id);
        
        if (!$shopDetails) {
            return response()->json([
                'status' => 'error',
                'message' => 'Shop details not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $shopDetails
        ]);
    }

    /**
     * Display shop details for a specific owner.
     *
     * @param  int  $ownerId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByOwnerId($ownerId)
    {
        $shopDetails = OwnerShopDetails::where('owner_id', $ownerId)->first();
        
        if (!$shopDetails) {
            return response()->json([
                'status' => 'error',
                'message' => 'Shop details not found for this owner'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $shopDetails
        ]);
    }

    /**
     * Display shop details for the authenticated owner.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrentOwnerShopDetails()
    {
        // Get the authenticated user's ID
        $user = auth()->user();
        
        if (!$user || !$user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Authenticated user not found'
            ], 401);
        }

        // For the Refilling Station Owner, we assume they have an 'id' that can be used
        // to fetch their shop details. Adjust this logic if your user-owner relationship is different.
        $ownerId = $user->id;
        
        $shopDetails = OwnerShopDetails::where('owner_id', $ownerId)->first();
        
        if (!$shopDetails) {
            return response()->json([
                'status' => 'error',
                'message' => 'Shop details not found for your account',
                'code' => 'no_shop_details'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $shopDetails
        ]);
    }

    /**
     * Update the specified shop details in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $shopDetails = OwnerShopDetails::find($id);
        
        if (!$shopDetails) {
            return response()->json([
                'status' => 'error',
                'message' => 'Shop details not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'delivery_time_slots' => 'nullable|array',
            'collection_days' => 'nullable|array',
            'has_regular_gallon' => 'boolean',
            'regular_gallon_price' => 'nullable|numeric|min:0',
            'has_dispenser_gallon' => 'boolean',
            'dispenser_gallon_price' => 'nullable|numeric|min:0',
            'rider_count' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $shopDetails->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Shop details updated successfully',
            'data' => $shopDetails
        ]);
    }

    /**
     * Update shop details for the authenticated owner.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCurrentOwnerShopDetails(Request $request)
    {
        // Get the authenticated user's ID
        $user = auth()->user();
        
        if (!$user || !$user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Authenticated user not found'
            ], 401);
        }

        $ownerId = $user->id;
        $shopDetails = OwnerShopDetails::where('owner_id', $ownerId)->first();
        
        if (!$shopDetails) {
            // If shop details don't exist, create new ones
            $request->merge(['owner_id' => $ownerId]);
            return $this->store($request);
        }

        $validator = Validator::make($request->all(), [
            'delivery_time_slots' => 'nullable|array',
            'collection_days' => 'nullable|array',
            'has_regular_gallon' => 'boolean',
            'regular_gallon_price' => 'nullable|numeric|min:0',
            'has_dispenser_gallon' => 'boolean',
            'dispenser_gallon_price' => 'nullable|numeric|min:0',
            'rider_count' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $shopDetails->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Shop details updated successfully',
            'data' => $shopDetails
        ]);
    }

    /**
     * Remove the specified shop details from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $shopDetails = OwnerShopDetails::find($id);
        
        if (!$shopDetails) {
            return response()->json([
                'status' => 'error',
                'message' => 'Shop details not found'
            ], 404);
        }

        $shopDetails->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Shop details deleted successfully'
        ]);
    }

    /**
     * Get delivery time options
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDeliveryTimeOptions()
    {
        return response()->json([
            'status' => 'success',
            'data' => OwnerShopDetails::getDeliveryTimeOptions()
        ]);
    }

    /**
     * Get collection day options
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCollectionDayOptions()
    {
        return response()->json([
            'status' => 'success',
            'data' => OwnerShopDetails::getCollectionDayOptions()
        ]);
    }

    /**
     * Get product types with default prices
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductTypes()
    {
        return response()->json([
            'status' => 'success',
            'data' => OwnerShopDetails::getProductTypes()
        ]);
    }
}
