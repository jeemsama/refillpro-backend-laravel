<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;


Route::get('/', function () {
    return view('welcome');
});



Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/requests', [AdminController::class, 'showRequests'])->name('admin.requests');
    Route::get('/admin-profile', [AdminController::class, 'profile'])->name('admin.profile');

    Route::get('/approve/{id}', [AdminController::class, 'approve'])->name('admin.approve');
    Route::post('/admin/decline-owner', [AdminController::class, 'declineOwner'])->name('admin.decline-owner');


    // routes/web.php
    Route::get('/admin/pending-request-count', function () {
        $count = \App\Models\RefillingStationOwner::where('status', 'pending')->count();
        return response()->json(['count' => $count]);
    })->name('admin.pendingRequestCount');



    
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
