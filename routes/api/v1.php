<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RefillingStationOwnerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\RiderController;




Route::prefix('v1')->group(function () {
    Route::post('/register-owner', [RefillingStationOwnerController::class, 'store']);

    Route::get('/refill-stations', [RefillingStationOwnerController::class, 'approvedStations']);
});

Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/riders', [RiderController::class, 'index']);
    Route::post('/riders', [RiderController::class, 'store']);
    Route::put('/riders/{id}', [RiderController::class, 'update']);
    Route::delete('/riders/{id}', [RiderController::class, 'destroy']);
});

    // Route::get('/riders', [RiderController::class, 'index']);

// Route::post('/riders', [RiderController::class,'store']);