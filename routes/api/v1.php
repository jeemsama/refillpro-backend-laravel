<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RefillingStationOwnerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShopDetailsController;
use App\Http\Controllers\API\RiderController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\OwnerProfileController;

Route::prefix('v1')->group(function () {
    Route::post('/register-owner', [RefillingStationOwnerController::class, 'store']);

    Route::get('/refill-stations', [RefillingStationOwnerController::class, 'approvedStations']);
    
    // Shop details utility routes (no auth required)
    Route::get('/shop-details/delivery-options', [ShopDetailsController::class, 'getDeliveryTimeOptions']);
    Route::get('/shop-details/collection-days', [ShopDetailsController::class, 'getCollectionDayOptions']);
    Route::get('/shop-details/product-types', [ShopDetailsController::class, 'getProductTypes']);
});

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Rider routes
    Route::get('/riders', [RiderController::class, 'index']);
    Route::post('/riders', [RiderController::class, 'store']);
    Route::put('/riders/{id}', [RiderController::class, 'update']);
    Route::delete('/riders/{id}', [RiderController::class, 'destroy']);
    
    // Shop details routes - standard REST endpoints
    Route::get('/shop-details', [ShopDetailsController::class, 'index']);
    Route::post('/shop-details', [ShopDetailsController::class, 'store']);
    Route::get('/shop-details/{id}', [ShopDetailsController::class, 'show']);
    Route::put('/shop-details/{id}', [ShopDetailsController::class, 'update']);
    Route::delete('/shop-details/{id}', [ShopDetailsController::class, 'destroy']);
    Route::get('/shop-details/owner/{ownerId}', [ShopDetailsController::class, 'getByOwnerId']);
    
    // Owner-specific shop details routes to match frontend URLs
    Route::get('/owner/shop-details', [ShopDetailsController::class, 'getCurrentOwnerShopDetails']);
    Route::post('/owner/shop-details', [ShopDetailsController::class, 'updateCurrentOwnerShopDetails']);
    Route::put('/owner/shop-details', [ShopDetailsController::class, 'updateCurrentOwnerShopDetails']);
});

// Commented routes
// Route::get('/riders', [RiderController::class, 'index']);
// Route::post('/riders', [RiderController::class,'store']);

// Customer email/OTP auth
Route::post('/customer/send-otp',   [App\Http\Controllers\Auth\CustomerAuthController::class,'sendOtp']);
Route::post('/customer/verify-otp', [App\Http\Controllers\Auth\CustomerAuthController::class,'verifyOtp']);

// Protected customer profile
Route::middleware('auth:sanctum')->group(function(){
    Route::get('/customer/profile',  [App\Http\Controllers\CustomerProfileController::class,'show']);
    Route::put('/customer/profile',  [App\Http\Controllers\CustomerProfileController::class,'update']);
    Route::post('/customer/logout',  [App\Http\Controllers\CustomerProfileController::class,'logout']);
});

Route::post('password/forgot', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('password/reset',  [ResetPasswordController::class, 'reset']);

//Profile Edit
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/owner/profile',   [OwnerProfileController::class, 'show']);
    Route::patch('/owner/profile', [OwnerProfileController::class, 'update']);
});

