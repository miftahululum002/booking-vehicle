<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'index')->name('login');
});

// Route::middleware(['auth'])->group(function () {
Route::name('dashboard.')->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::controller(DashboardController::class)->group(function () {
            Route::get('/', 'index')->name('index');
        });
    });
});
// });
