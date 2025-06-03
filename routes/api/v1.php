<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RefillingStationOwnerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShopDetailsController;
use App\Http\Controllers\API\RiderController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\OwnerProfileController;
// use App\Http\Controllers\API\RiderProfileController;

use App\Http\Controllers\StatsController;



Route::prefix('v1')->group(function () {

        Route::middleware('auth:sanctum')->group(function () {
        // Rider routes now live at /api/v1/riders
        Route::get('/riders',    [RiderController::class, 'index']);
        Route::post('/riders',   [RiderController::class, 'store']);
        Route::put('/riders/{id}',    [RiderController::class, 'update']);
        Route::delete('/riders/{id}', [RiderController::class, 'destroy']);

        // … your orders/accept, orders/decline, etc. …
    });

    


    Route::post('/register-owner', [RefillingStationOwnerController::class, 'store']);

    Route::get('/refill-stations', [RefillingStationOwnerController::class, 'approvedStations']);

    // Route::get('/customer/stores', [RefillingStationOwnerController::class, 'index']);

    
    // Shop details utility routes (no auth required)
    Route::get('/shop-details/delivery-options', [ShopDetailsController::class, 'getDeliveryTimeOptions']);
    Route::get('/shop-details/collection-days', [ShopDetailsController::class, 'getCollectionDayOptions']);
    Route::get('/shop-details/product-types', [ShopDetailsController::class, 'getProductTypes']);
    Route::get('/shop-details/owner/{ownerId}', [ShopDetailsController::class, 'getByOwnerId']);


// — Order routes
    // Customer places a brand-new order (public)
    Route::post('/orders', [OrderController::class, 'store']);

    // Public listings:
    Route::get('/orders',       [OrderController::class, 'getOrdersByCustomers']);
    Route::get('/orders/owner', [OrderController::class, 'getOrdersByOwner']);

    // Protected by sanctum:
    Route::middleware('auth:sanctum')->group(function () {
        // Customer views their own orders
        Route::get('/orders', [OrderController::class, 'index']);

        // Customer cancels one of their orders
        Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel']);

        // Customer deletes one of their orders
        Route::delete('/orders/{id}', [OrderController::class, 'destroy']);

        // Owner actions on customer orders:
        Route::post('/orders/{id}/accept',  [OrderController::class, 'accept']);
        Route::post('/orders/{id}/decline', [OrderController::class, 'decline']);

        
        // Rider’s own assigned orders
       Route::get('/rider/orders', [OrderController::class, 'getOrdersByRider']);

       Route::post('/orders/{id}/complete', [OrderController::class, 'complete']);

    });

    
});

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Rider routes
    // Route::get('/riders', [RiderController::class, 'index']);
    // Route::post('/riders', [RiderController::class, 'store']);
    // Route::put('/riders/{id}', [RiderController::class, 'update']);
    // Route::delete('/riders/{id}', [RiderController::class, 'destroy']);
    
    // Shop details routes - standard REST endpoints
    Route::get('/shop-details', [ShopDetailsController::class, 'index']);
    Route::post('/shop-details', [ShopDetailsController::class, 'store']);
    Route::get('/shop-details/{id}', [ShopDetailsController::class, 'show']);
    Route::put('/shop-details/{id}', [ShopDetailsController::class, 'update']);
    Route::delete('/shop-details/{id}', [ShopDetailsController::class, 'destroy']);

    
    // Owner-specific shop details routes to match frontend URLs
    Route::get('/owner/shop-details', [ShopDetailsController::class, 'getCurrentOwnerShopDetails']);
    Route::post('/owner/shop-details', [ShopDetailsController::class, 'updateCurrentOwnerShopDetails']);
    Route::put('/owner/shop-details', [ShopDetailsController::class, 'updateCurrentOwnerShopDetails']);

    // rider’s own profile
    Route::get('/rider/profile', [RiderController::class,'show']);


});



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

    // GET /api/owner/stats?year=2025
    Route::get('owner/stats', [StatsController::class, 'monthlyStats']);
});

//Owner Profile photo
Route::middleware('auth:sanctum')->post(
  'owner/profile/photo',
  [OwnerProfileController::class, 'updatePhoto']
);

//Stores
Route::middleware('auth:sanctum')->group(function () {
    // ...
    Route::get('/customer/stores', [RefillingStationOwnerController::class, 'index']);
    // ...
});


//Riders Profile
// Route::middleware('auth:sanctum')->get(
//     '/rider/profile',
//     [RiderProfileController::class, 'show']
// );

Route::get('/test-v1', function () {
    return response()->json(['ok' => true]);
});



